<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Status;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        return view('admin.blog.index');
    }

    public function create(): View
    {
        return view('admin.blog.create', [
            'statuses' => $this->blogStatuses(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedBlog($request);
        $validated['created_user_id'] = auth()->id();

        $imageAlt = $validated['blog_image_alt'] ?? null;
        unset($validated['blog_image'], $validated['blog_image_alt']);

        $blog = Blog::create($validated);
        $blog->persistMainImage($request->file('blog_image'), $imageAlt, auth()->id());

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Post creado correctamente.');
    }

    public function show(Blog $blog): View
    {
        $blog->load(['statusRecord', 'createdUser', 'images']);

        return view('admin.blog.show', compact('blog'));
    }

    public function edit(Blog $blog): View
    {
        $blog->load('images');

        return view('admin.blog.edit', [
            'blog' => $blog,
            'statuses' => $this->blogStatuses(),
        ]);
    }

    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $validated = $this->validatedBlog($request, $blog);
        $imageAlt = $validated['blog_image_alt'] ?? null;
        unset($validated['blog_image'], $validated['blog_image_alt']);

        $blog->update($validated);
        $blog->persistMainImage($request->file('blog_image'), $imageAlt, auth()->id());

        return redirect()
            ->route('admin.blogs.show', $blog)
            ->with('success', 'Post actualizado correctamente.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        $blog->deleteStoredImages();
        $blog->delete();

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Post eliminado correctamente.');
    }

    private function blogStatuses()
    {
        return Status::query()
            ->where('statusable', Blog::class)
            ->orderBy('name')
            ->get();
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedBlog(Request $request, ?Blog $blog = null): array
    {
        if (!$request->filled('slug') && $request->filled('name')) {
            $request->merge(['slug' => Str::slug($request->string('name'))]);
        }

        if (!$request->filled('blog_image_alt')) {
            $request->merge(['blog_image_alt' => null]);
        }

        foreach (['author_linkedin', 'author_facebook', 'author_instagram', 'author_x'] as $socialField) {
            if (!$request->filled($socialField)) {
                $request->merge([$socialField => null]);
            }
        }

        $slugUnique = Rule::unique('blogs', 'slug');
        if ($blog) {
            $slugUnique = $slugUnique->ignore($blog->id);
        }

        return $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slugUnique],
            'content' => 'required|string',
            'status_id' => [
                'required',
                'integer',
                Rule::exists('statuses', 'id')->where('statusable', Blog::class),
            ],
            'blog_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
            'blog_image_alt' => 'nullable|string|max:255',
            'author_linkedin' => 'nullable|url|max:255',
            'author_facebook' => 'nullable|url|max:255',
            'author_instagram' => 'nullable|url|max:255',
            'author_x' => 'nullable|url|max:255',
        ], [
            'name.required' => 'El título es obligatorio.',
            'slug.required' => 'El slug es obligatorio.',
            'slug.regex' => 'El slug solo puede tener minúsculas, números y guiones.',
            'content.required' => 'El contenido es obligatorio.',
            'status_id.required' => 'Selecciona un estado.',
            'blog_image.image' => 'El archivo debe ser una imagen válida.',
            'blog_image.mimes' => 'Formatos permitidos: JPEG, PNG, GIF, WebP.',
            'blog_image.max' => 'La imagen no puede superar 10 MB.',
            'author_linkedin.url' => 'La URL de LinkedIn no es válida.',
            'author_facebook.url' => 'La URL de Facebook no es válida.',
            'author_instagram.url' => 'La URL de Instagram no es válida.',
            'author_x.url' => 'La URL de X no es válida.',
        ]);
    }
}
