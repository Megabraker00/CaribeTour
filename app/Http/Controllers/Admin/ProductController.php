<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\Terminal;
use App\Support\ProductCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(string $kind)
    {
        $catalog = ProductCatalog::fromKind($kind);

        return view('admin.tour.index', [
            'catalog' => $catalog,
        ]);
    }

    public function create(string $kind)
    {
        $catalog = ProductCatalog::fromKind($kind);
        $tour = new Product(['type_id' => $catalog['type_id']]);

        return view('admin.tour.form', $this->formViewData($tour, $catalog, [
            'new' => true,
            'terminals' => [],
        ]));
    }

    public function store(Request $request, string $kind)
    {
        $catalog = ProductCatalog::fromKind($kind);
        $validatedFields = $this->validatedProductFields($request);
        unset($validatedFields['meta_description'], $validatedFields['meta_includes'], $validatedFields['meta_stars']);
        $validatedFields['type_id'] = $catalog['type_id'];
        $validatedFields['created_user_id'] = auth()->id();

        $tour = Product::create($validatedFields);

        $this->syncTourMetaFromRequest($tour, $request);

        return redirect()->route('admin.catalog.show', [$catalog['kind'], $tour->id]);
    }

    public function show(string $kind, $id)
    {
        $catalog = ProductCatalog::fromKind($kind);
        $tour = $this->findCatalogProduct($catalog, $id);

        return view('admin.tour.form', $this->formViewData($tour, $catalog, [
            'show' => true,
            'terminals' => Terminal::all(),
        ]));
    }

    public function edit(string $kind, $id)
    {
        $catalog = ProductCatalog::fromKind($kind);
        $tour = $this->findCatalogProduct($catalog, $id);

        return view('admin.tour.form', $this->formViewData($tour, $catalog, [
            'edit' => true,
            'terminals' => Terminal::all(),
        ]));
    }

    public function update(Request $request, string $kind, $id)
    {
        $catalog = ProductCatalog::fromKind($kind);
        $validatedFields = $this->validatedProductFields($request);
        unset($validatedFields['meta_description'], $validatedFields['meta_includes'], $validatedFields['meta_stars']);
        $validatedFields['type_id'] = $catalog['type_id'];

        $tour = $this->findCatalogProduct($catalog, $id);
        $tour->update($validatedFields);
        $tour->refresh();

        $this->syncTourMetaFromRequest($tour, $request);

        return redirect()->route('admin.catalog.show', [$catalog['kind'], $tour->id])
            ->with('success', $catalog['singular'].' actualizado correctamente.');
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
        if (!is_array($meta)) {
            $meta = [];
        }

        $meta['description'] = $description;
        $meta['includes'] = $includes;
        $meta['stars'] = $stars;

        $tour->metaData()->updateOrCreate([], ['meta_data' => $meta]);
    }

    /**
     * Sube imágenes del producto a public/images/{tipo}/{slug}/ con nombre {slug}-{ms}.{ext}
     * y las registra en la tabla polimórfica images.
     */
    public function storeImages(Request $request, string $kind, $id)
    {
        $catalog = ProductCatalog::fromKind($kind);
        $tour = $this->findCatalogProduct($catalog, $id, false);

        $request->validate([
            'images' => 'required|array|min:1|max:30',
            'images.*' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
        ], [
            'images.required' => 'Selecciona al menos una imagen.',
            'images.*.image' => 'Cada archivo debe ser una imagen válida.',
            'images.*.mimes' => 'Formatos permitidos: JPEG, PNG, GIF, WebP.',
            'images.*.max' => 'Cada imagen no puede superar 10 MB.',
        ]);

        $safeSlug = $this->safeImageFolderSlug($tour->slug, $tour->id, $catalog['kind']);
        $relativeDir = 'images/'.$catalog['image_folder'].'/'.$safeSlug;
        $absoluteDir = public_path($relativeDir);

        if (!File::isDirectory($absoluteDir)) {
            File::makeDirectory($absoluteDir, 0755, true);
        }

        $uploadedBy = auth()->id() ?? 1;
        $hasMain = $tour->images()->where('is_main', true)->exists();
        $mainAssignedInBatch = false;
        $savedCount = 0;

        foreach ($request->file('images') as $file) {
            if (!$file->isValid()) {
                continue;
            }

            $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg');
            $allowedExt = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
            if (!in_array($extension, $allowedExt, true)) {
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
            $isMain = !$hasMain && !$mainAssignedInBatch;

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

    public function destroyImage(string $kind, $id, Image $image)
    {
        $catalog = ProductCatalog::fromKind($kind);
        $tour = $this->findCatalogProduct($catalog, $id, false);

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
     * Marca una imagen como principal del producto (solo una is_main por producto).
     */
    public function setMainImage(string $kind, $id, Image $image)
    {
        $catalog = ProductCatalog::fromKind($kind);
        $tour = $this->findCatalogProduct($catalog, $id, false);

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
     * Actualiza los nombres (título / zona) de todas las imágenes del producto en un solo envío.
     */
    public function updateImagesNames(Request $request, string $kind, $id)
    {
        $catalog = ProductCatalog::fromKind($kind);
        $tour = $this->findCatalogProduct($catalog, $id);

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
                if (!$image) {
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
     * @return array<string, mixed>
     */
    private function validatedProductFields(Request $request): array
    {
        return $request->validate([
            'name' => 'required|min:3',
            'slug' => 'required|min:3',
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
            'status_id.required' => 'Selecciona un estado del producto',
            'meta_stars.between' => 'Las estrellas deben ser un entero entre 0 y 5.',
        ]);
    }

    /**
     * @param  array<string, mixed>  $flags
     * @return array<string, mixed>
     */
    private function formViewData(Product $tour, array $catalog, array $flags): array
    {
        return array_merge([
            'tour' => $tour,
            'catalog' => $catalog,
            'parentCategories' => Category::whereNull('parent_id')->get(),
            'suppliers' => Supplier::all(),
            'productStatuses' => Status::where('statusable', Product::class)->orderBy('name')->get(),
        ], $flags);
    }

    private function findCatalogProduct(array $catalog, $id, bool $withRelations = true): Product
    {
        $query = Product::query()->where('type_id', $catalog['type_id']);

        if ($withRelations) {
            $query->with(['images', 'metaData']);
        }

        return $query->findOrFail($id);
    }

    /**
     * Carpeta segura bajo public/images/{tipo}/ (solo slug del producto).
     */
    private function safeImageFolderSlug(string $slug, int $productId, string $kind): string
    {
        $clean = preg_replace('/[^a-z0-9\-]/', '', strtolower($slug));

        return $clean !== '' ? $clean : $kind.'-'.$productId;
    }
}
