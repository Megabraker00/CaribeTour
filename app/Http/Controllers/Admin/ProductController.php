<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\Terminal;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function indexTour()
    {
        return view('admin.tour.index');
    }

    public function createTour()
    {
        $tour = new Product();

        $parentCategories = Category::whereNull('parent_id')->get();

        $suppliers = Supplier::all();

        $productTypes = Type::where('typeable', Product::class)->orderBy('name')->get();
        $productStatuses = Status::where('statusable', Product::class)->orderBy('name')->get();

        return view('admin.tour.form', [
            'tour' => $tour,
            'parentCategories' => $parentCategories,
            'suppliers' => $suppliers,
            'productTypes' => $productTypes,
            'productStatuses' => $productStatuses,
            'terminals' => [],
            'new' => true,
        ]);
    }

    public function storeTour(Request $request)
    {
        $validatedFields = $request->validate([
            'name' => 'required|min:3',
            'slug' => 'required|min:3',
            'type_id' => [
                'required',
                'integer',
                Rule::exists('types', 'id')->where('typeable', Product::class),
            ],
            'category_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'status_id' => [
                'required',
                'integer',
                Rule::exists('statuses', 'id')->where('statusable', Product::class),
            ],
            'meta_description' => 'nullable|string',
            'meta_includes' => 'nullable|string',
            'meta_stars' => 'nullable|integer|between:0,5',
        ], [
            'name.required' => 'El campo nombre es requerido',
            'category_id' => 'Tienes que seleccionar una categoría válida',
            'type_id.required' => 'Selecciona un tipo de producto',
            'status_id.required' => 'Selecciona un estado del producto',
            'meta_stars.between' => 'Las estrellas deben ser un entero entre 0 y 5.',
        ]); // añadir la validación

        unset($validatedFields['meta_description'], $validatedFields['meta_includes'], $validatedFields['meta_stars']);
        $validatedFields['created_user_id'] = 1; // set default value

        $tour = Product::create($validatedFields);

        $this->syncTourMetaFromRequest($tour, $request);

        //return redirect()->route('admin.tour.index')->with('success', 'Tour created successfully.');
        return redirect()->route('admin.tour.show', $tour->id);
    }

    public function showTour($id)
    {
        $tour = Product::with(['images', 'metaData'])->findOrFail($id);

        $parentCategories = Category::whereNull('parent_id')->get();

        $suppliers = Supplier::all();

        $terminals = Terminal::all();

        $productTypes = Type::where('typeable', Product::class)->orderBy('name')->get();
        $productStatuses = Status::where('statusable', Product::class)->orderBy('name')->get();

        return view('admin.tour.form', [
            'tour' => $tour,
            'parentCategories' => $parentCategories,
            'suppliers' => $suppliers,
            'productTypes' => $productTypes,
            'productStatuses' => $productStatuses,
            'terminals' => $terminals,
            'show' => true,
        ]);
    }

    public function editTour($id)
    {
        $tour = Product::with(['images', 'metaData'])->findOrFail($id);

        $parentCategories = Category::whereNull('parent_id')->get();

        $suppliers = Supplier::all();

        $terminals = Terminal::all();

        $productTypes = Type::where('typeable', Product::class)->orderBy('name')->get();
        $productStatuses = Status::where('statusable', Product::class)->orderBy('name')->get();

        return view('admin.tour.form', [
            'tour' => $tour,
            'parentCategories' => $parentCategories,
            'suppliers' => $suppliers,
            'productTypes' => $productTypes,
            'productStatuses' => $productStatuses,
            'terminals' => $terminals,
            'edit' => true,
        ]);
    }

    public function updateTour(Request $request, $id)
    {
        $validatedFields = $request->validate([
            'name' => 'required|min:3',
            'slug' => 'required|min:3',
            'type_id' => [
                'required',
                'integer',
                Rule::exists('types', 'id')->where('typeable', Product::class),
            ],
            'category_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'status_id' => [
                'required',
                'integer',
                Rule::exists('statuses', 'id')->where('statusable', Product::class),
            ],
            'meta_description' => 'nullable|string',
            'meta_includes' => 'nullable|string',
            'meta_stars' => 'nullable|integer|between:0,5',
        ], [
            'name.required' => 'El campo nombre es requerido',
            'category_id' => 'Tienes que seleccionar una categoría válida',
            'type_id.required' => 'Selecciona un tipo de producto',
            'status_id.required' => 'Selecciona un estado del producto',
            'meta_stars.between' => 'Las estrellas deben ser un entero entre 0 y 5.',
        ]); // añadir la validación

        unset($validatedFields['meta_description'], $validatedFields['meta_includes'], $validatedFields['meta_stars']);

        $tour = Product::findOrFail($id);
        $tour->update($validatedFields);
        $tour->refresh();

        $this->syncTourMetaFromRequest($tour, $request);

        return redirect()->route('admin.tour.show', $tour->id)
            ->with('success', 'Tour updated successfully.');
    }

    /**
     * Guarda description, includes y stars en meta_data (tabla meta_data), fusionando con el JSON existente.
     */
    private function syncTourMetaFromRequest(Product $tour, Request $request): void
    {
        $description = $request->input('meta_description', '');
        $includesRaw = $request->input('meta_includes', '');
        $includes = array_values(array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', (string) $includesRaw)),
            static fn (string $line): bool => $line !== ''
        ));

        $starsRaw = $request->input('meta_stars');
        $stars = $starsRaw === null || $starsRaw === '' ? 0 : (int) $starsRaw;
        $stars = max(0, min(5, $stars));

        $tour->unsetRelation('metaData');
        $tour->loadMissing('metaData');
        $meta = $tour->metaData?->meta_data ?? [];
        if (! is_array($meta)) {
            $meta = [];
        }

        $meta['description'] = $description;
        $meta['includes'] = $includes;
        $meta['stars'] = $stars;

        $tour->metaData()->updateOrCreate([], ['meta_data' => $meta]);
    }

    /**
     * Sube imágenes del tour a public/images/{slug}/ con nombre {slug}-{ms}.{ext}
     * y las registra en la tabla polimórfica images.
     */
    public function storeTourImages(Request $request, $id)
    {
        $tour = Product::findOrFail($id);

        $request->validate([
            'images' => 'required|array|min:1|max:30',
            'images.*' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
        ], [
            'images.required' => 'Selecciona al menos una imagen.',
            'images.*.image' => 'Cada archivo debe ser una imagen válida.',
            'images.*.mimes' => 'Formatos permitidos: JPEG, PNG, GIF, WebP.',
            'images.*.max' => 'Cada imagen no puede superar 10 MB.',
        ]);

        $safeSlug = $this->safeImageFolderSlug($tour->slug, $tour->id);
        $relativeDir = 'images/tours/'.$safeSlug;
        $absoluteDir = public_path($relativeDir);

        if (! File::isDirectory($absoluteDir)) {
            File::makeDirectory($absoluteDir, 0755, true);
        }

        $uploadedBy = auth()->id() ?? 1;
        $hasMain = $tour->images()->where('is_main', true)->exists();
        $mainAssignedInBatch = false;
        $savedCount = 0;

        foreach ($request->file('images') as $file) {
            if (! $file->isValid()) {
                continue;
            }

            $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg');
            $allowedExt = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
            if (! in_array($extension, $allowedExt, true)) {
                continue;
            }

            $milliseconds = (int) round(microtime(true) * 1000);
            $base = $safeSlug.'-'.$milliseconds;
            $filename = $base.'.'.$extension;
            $counter = 0;
            while (File::exists($absoluteDir.'/'.$filename)) {
                $counter++;
                $filename = $base.'-'.$counter.'.'.$extension;
            }

            $file->move($absoluteDir, $filename);

            $relativePath = $relativeDir.'/'.$filename;
            $isMain = ! $hasMain && ! $mainAssignedInBatch;

            $tour->images()->create([
                'name' => pathinfo($filename, PATHINFO_FILENAME),
                'path' => $relativePath,
                'is_main' => $isMain,
                'uploaded_user_id' => $uploadedBy,
            ]);

            $savedCount++;
            if ($isMain) {
                $hasMain = true;
                $mainAssignedInBatch = true;
            }
        }

        if ($savedCount === 0) {
            return redirect()
                ->back()
                ->withFragment('tour-images')
                ->withErrors(['images' => 'No se pudo guardar ninguna imagen. Comprueba el formato y el tamaño.']);
        }

        return redirect()
            ->back()
            ->withFragment('tour-images')
            ->with('success', 'Imágenes subidas correctamente.');
    }

    public function destroyTourImage($id, Image $image)
    {
        $tour = Product::findOrFail($id);

        if ($image->imageable_type !== Product::class || (int) $image->imageable_id !== (int) $tour->id) {
            abort(404);
        }

        $wasMain = (bool) $image->is_main;
        $publicPath = public_path($image->path);

        if ($image->path && File::exists($publicPath)) {
            File::delete($publicPath);
        }

        $image->delete();

        if ($wasMain) {
            $next = Image::query()
                ->where('imageable_type', Product::class)
                ->where('imageable_id', $tour->id)
                ->orderBy('id')
                ->first();
            if ($next) {
                Image::query()
                    ->where('imageable_type', Product::class)
                    ->where('imageable_id', $tour->id)
                    ->update(['is_main' => false]);
                $next->update(['is_main' => true]);
            }
        }

        return redirect()
            ->back()
            ->withFragment('tour-images')
            ->with('success', 'Imagen eliminada.');
    }

    /**
     * Marca una imagen como principal del tour (solo una is_main por producto).
     */
    public function setMainTourImage($id, Image $image)
    {
        $tour = Product::findOrFail($id);

        if ($image->imageable_type !== Product::class || (int) $image->imageable_id !== (int) $tour->id) {
            abort(404);
        }

        DB::transaction(function () use ($tour, $image) {
            Image::query()
                ->where('imageable_type', Product::class)
                ->where('imageable_id', $tour->id)
                ->update(['is_main' => false]);

            $image->update(['is_main' => true]);
        });

        return redirect()
            ->back()
            ->withFragment('tour-images')
            ->with('success', 'Imagen principal actualizada.');
    }

    /**
     * Actualiza los nombres (título / zona) de todas las imágenes del tour en un solo envío.
     */
    public function updateTourImagesNames(Request $request, $id)
    {
        $tour = Product::with('images')->findOrFail($id);

        if ($tour->images->isEmpty()) {
            return redirect()
                ->back()
                ->withFragment('tour-images')
                ->withErrors(['image_names' => 'No hay imágenes que actualizar.']);
        }

        $rules = [];
        foreach ($tour->images as $img) {
            $rules['image_names.'.$img->id] = 'required|string|max:255';
        }

        $validated = $request->validate($rules, [
            'image_names.*.required' => 'Cada imagen debe tener un título o zona (no dejes campos vacíos).',
            'image_names.*.max' => 'Cada título no puede superar 255 caracteres.',
        ]);

        DB::transaction(function () use ($tour, $validated) {
            foreach ($validated['image_names'] as $imageId => $name) {
                $imageId = (int) $imageId;
                $image = $tour->images->firstWhere('id', $imageId);
                if (! $image) {
                    continue;
                }
                $image->update(['name' => trim((string) $name)]);
            }
        });

        return redirect()
            ->back()
            ->withFragment('tour-images')
            ->with('success', 'Nombres de las imágenes actualizados.');
    }

    /**
     * Carpeta segura bajo public/images/ (solo slug del producto).
     */
    private function safeImageFolderSlug(string $slug, int $productId): string
    {
        $clean = preg_replace('/[^a-z0-9\-]/', '', strtolower($slug));

        return $clean !== '' ? $clean : 'tour-'.$productId;
    }
}
