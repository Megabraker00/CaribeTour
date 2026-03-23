<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->with(['parentCategory', 'statusRecord', 'images'])
            ->orderByRaw('parent_id is null desc')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.category.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.category.create', [
            'parentOptions' => $this->parentOptions(),
            'statuses' => $this->categoryStatuses(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedCategory($request);
        $metaDescription = $validated['meta_description'] ?? '';
        unset($validated['meta_description'], $validated['category_image']);

        $category = Category::create($validated);
        $this->syncCategoryMetaDescription($category, $metaDescription);
        $this->storeCategoryMainImage($category, $request);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category): View
    {
        $category->load(['subCategories', 'metaData', 'images']);

        return view('admin.category.edit', [
            'category' => $category,
            'parentOptions' => $this->parentOptions(excludeCategory: $category),
            'statuses' => $this->categoryStatuses(),
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $this->validatedCategory($request, $category);

        if (isset($validated['parent_id']) && (int) $validated['parent_id'] === $category->id) {
            return redirect()
                ->route('admin.categories.edit', $category)
                ->withInput()
                ->withErrors(['parent_id' => 'La categoría no puede ser padre de sí misma.']);
        }

        $parentId = $validated['parent_id'] ?? null;
        if ($parentId !== null && in_array((int) $parentId, $this->descendantIds($category), true)) {
            return redirect()
                ->route('admin.categories.edit', $category)
                ->withInput()
                ->withErrors(['parent_id' => 'No puedes asignar como padre una subcategoría de esta categoría.']);
        }

        $metaDescription = $validated['meta_description'] ?? '';
        unset($validated['meta_description'], $validated['category_image']);

        $category->update($validated);
        $this->syncCategoryMetaDescription($category, $metaDescription);
        $this->storeCategoryMainImage($category, $request);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Guarda la descripción (HTML) en meta_data, fusionando con el JSON existente (misma clave que tours: description).
     */
    private function syncCategoryMetaDescription(Category $category, string $html): void
    {
        $category->loadMissing('metaData');
        $meta = $category->metaData?->meta_data ?? [];
        if (! is_array($meta)) {
            $meta = [];
        }
        $meta['description'] = $html;

        $category->metaData()->updateOrCreate([], ['meta_data' => $meta]);
    }

    /**
     * Sustituye la imagen principal de la categoría (una sola) en public/images/categories/{slug}/.
     */
    private function storeCategoryMainImage(Category $category, Request $request): void
    {
        if (! $request->hasFile('category_image')) {
            return;
        }

        $file = $request->file('category_image');
        if (! $file->isValid()) {
            return;
        }

        $safeSlug = $this->safeCategoryFolderSlug($category->slug, $category->id);
        $relativeDir = 'images/categories/'.$safeSlug;
        $absoluteDir = public_path($relativeDir);

        if (! File::isDirectory($absoluteDir)) {
            File::makeDirectory($absoluteDir, 0755, true);
        }

        foreach ($category->images()->get() as $img) {
            if ($img->path) {
                $full = public_path($img->path);
                if (File::exists($full)) {
                    File::delete($full);
                }
            }
            $img->delete();
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg');
        $allowedExt = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
        if (! in_array($extension, $allowedExt, true)) {
            return;
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
        $uploadedBy = auth()->id() ?? 1;

        $category->images()->create([
            'name' => pathinfo($filename, PATHINFO_FILENAME),
            'path' => $relativePath,
            'is_main' => true,
            'uploaded_user_id' => $uploadedBy,
        ]);
    }

    private function safeCategoryFolderSlug(string $slug, int $categoryId): string
    {
        $clean = preg_replace('/[^a-z0-9\-]/', '', strtolower($slug));

        return $clean !== '' ? $clean : 'category-'.$categoryId;
    }

    /**
     * @return \Illuminate\Support\Collection<int, Category>
     */
    private function parentOptions(?Category $excludeCategory = null)
    {
        $query = Category::query()->orderBy('name');

        if ($excludeCategory) {
            $invalid = array_merge(
                [$excludeCategory->id],
                $this->descendantIds($excludeCategory)
            );
            $query->whereNotIn('id', $invalid);
        }

        return $query->get();
    }

    /**
     * @return list<int>
     */
    private function descendantIds(Category $category): array
    {
        $ids = [];
        foreach ($category->subCategories as $child) {
            $ids[] = $child->id;
            $child->load('subCategories');
            $ids = array_merge($ids, $this->descendantIds($child));
        }

        return $ids;
    }

    private function categoryStatuses()
    {
        return Status::where('statusable', Category::class)->orderBy('name')->get();
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedCategory(Request $request, ?Category $category = null): array
    {
        if (! $request->filled('parent_id')) {
            $request->merge(['parent_id' => null]);
        }

        $parentId = $request->input('parent_id');

        $slugUnique = Rule::unique('categories', 'slug')
            ->where(function ($query) use ($parentId) {
                if ($parentId === null) {
                    return $query->whereNull('parent_id');
                }

                return $query->where('parent_id', $parentId);
            });

        if ($category) {
            $slugUnique = $slugUnique->ignore($category->id);
        }

        return $request->validate([
            'name' => 'required|string|max:100',
            'slug' => ['required', 'string', 'max:150', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slugUnique],
            'parent_id' => 'nullable|integer|exists:categories,id',
            'status_id' => [
                'required',
                'integer',
                Rule::exists('statuses', 'id')->where('statusable', Category::class),
            ],
            'meta_description' => 'nullable|string',
            'category_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'slug.required' => 'El slug es obligatorio.',
            'slug.regex' => 'El slug solo puede tener minúsculas, números y guiones.',
            'status_id.required' => 'Selecciona un estado.',
            'category_image.image' => 'El archivo debe ser una imagen válida.',
            'category_image.mimes' => 'Formatos permitidos: JPEG, PNG, GIF, WebP.',
            'category_image.max' => 'La imagen no puede superar 10 MB.',
        ]);
    }
}
