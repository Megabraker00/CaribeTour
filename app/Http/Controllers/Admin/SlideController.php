<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SlideController extends Controller
{
    /**
     * Extensión con la que se guarda cada tipo, según el contenido del archivo.
     *
     * @var array<string, string>
     */
    private const IMAGE_EXTENSIONS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    public function index(): View
    {
        $slides = Slide::query()
            ->with(['product.type', 'product.category.parentCategory'])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.slide.index', compact('slides'));
    }

    public function create(Request $request): View
    {
        $this->discardPendingImageUnlessErrors($request);

        return view('admin.slide.create', [
            'slide' => null,
            'products' => $this->productOptions(),
            'nextOrder' => ((int) Slide::query()->max('sort_order')) + 1,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedSlide($request);
        $validated['image_path'] = $this->storedImagePath($request);

        Slide::create($validated);
        $request->session()->forget('slide_pending_image');

        return redirect()
            ->route('admin.slides.index')
            ->with('success', 'Diapositiva creada correctamente.');
    }

    public function edit(Request $request, Slide $slide): View
    {
        $this->discardPendingImageUnlessErrors($request);

        return view('admin.slide.edit', [
            'slide' => $slide,
            'products' => $this->productOptions(),
        ]);
    }

    public function update(Request $request, Slide $slide): RedirectResponse
    {
        $validated = $this->validatedSlide($request, $slide);
        $imagePath = $this->storedImagePath($request);

        if ($imagePath !== null) {
            $this->deleteImageFile($slide->image_path);
            $validated['image_path'] = $imagePath;
            $request->session()->forget('slide_pending_image');
        }

        $slide->update($validated);

        return redirect()
            ->route('admin.slides.index')
            ->with('success', 'Diapositiva actualizada correctamente.');
    }

    public function destroy(Slide $slide): RedirectResponse
    {
        $this->deleteImageFile($slide->image_path);
        $slide->delete();

        return redirect()
            ->route('admin.slides.index')
            ->with('success', 'Diapositiva eliminada correctamente.');
    }

    /**
     * @return Collection<string, Collection<int, Product>>
     */
    private function productOptions(): Collection
    {
        return Product::query()
            ->with('type')
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Product $product) => $product->type->name ?? 'Otros');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedSlide(Request $request, ?Slide $slide = null): array
    {
        foreach (['product_id', 'link_url', 'text', 'starts_on', 'ends_on'] as $field) {
            if (!$request->filled($field)) {
                $request->merge([$field => null]);
            }
        }

        $request->merge([
            'is_active' => $request->boolean('is_active'),
        ]);

        $keepsImage = $this->pendingImage($request) !== null;

        try {
            $validated = $request->validate([
            'title' => 'required|string|max:120',
            'text' => 'nullable|string|max:200',
            'image' => [
                ($slide === null && !$keepsImage) ? 'required' : 'nullable',
                'file',
                'max:10240',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (!$value instanceof UploadedFile) {
                        return;
                    }

                    if ($this->imageExtension($value) === null) {
                        $fail('Formatos permitidos: JPEG, PNG, GIF, WebP.');

                        return;
                    }

                    $size = @getimagesize($value->getPathname());
                    if ($size === false) {
                        $fail('Formatos permitidos: JPEG, PNG, GIF, WebP.');
                    }
                },
            ],
            'product_id' => [
                'nullable',
                'integer',
                Rule::exists('products', 'id'),
                'required_without:link_url',
            ],
            'link_url' => [
                'nullable',
                'string',
                'max:500',
                'required_without:product_id',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($value === null || $value === '') {
                        return;
                    }

                    $url = (string) $value;
                    $internal = str_starts_with($url, '/') && !str_starts_with($url, '//');
                    $external = filter_var($url, FILTER_VALIDATE_URL) !== false
                        && preg_match('#\Ahttps?://#i', $url) === 1;

                    if (!$internal && !$external) {
                        $fail('El enlace debe ser una ruta del sitio o una dirección http(s).');
                    }
                },
            ],
            'sort_order' => 'required|integer|min:0|max:9999',
            'is_active' => 'boolean',
            'starts_on' => 'nullable|date',
            'ends_on' => 'nullable|date|after_or_equal:starts_on',
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.max' => 'El título no puede superar 120 caracteres.',
            'text.max' => 'El texto no puede superar 200 caracteres.',
            'image.required' => 'La imagen es obligatoria.',
            'image.max' => 'La imagen no puede superar 10 MB.',
            'product_id.exists' => 'El producto seleccionado no es válido.',
            'product_id.required_without' => 'Elige un producto o escribe un enlace.',
            'link_url.required_without' => 'Elige un producto o escribe un enlace.',
            'link_url.max' => 'El enlace no puede superar 500 caracteres.',
            'sort_order.required' => 'El orden es obligatorio.',
            'sort_order.integer' => 'El orden debe ser un número.',
            'sort_order.min' => 'El orden no puede ser negativo.',
            'sort_order.max' => 'El orden no puede superar 9999.',
            'ends_on.after_or_equal' => 'La fecha de fin no puede ser anterior a la de inicio.',
            ]);
        } catch (ValidationException $exception) {
            $this->stashValidImage($request, $exception);

            throw $exception;
        }

        unset($validated['image']);

        return $validated;
    }

    private function stashValidImage(Request $request, ValidationException $exception): void
    {
        if ($exception->validator->errors()->has('image')) {
            return;
        }

        $file = $request->file('image');
        if (!$file instanceof UploadedFile || !$file->isValid()) {
            return;
        }

        $extension = $this->imageExtension($file);
        if ($extension === null) {
            return;
        }

        $this->discardPendingImage();

        $token = (string) Str::uuid();
        $relativeDir = 'images/slides/pending';
        $absoluteDir = public_path($relativeDir);
        if (!File::isDirectory($absoluteDir)) {
            File::makeDirectory($absoluteDir, 0755, true);
        }

        $file->move($absoluteDir, $token.'.'.$extension);

        $request->session()->put('slide_pending_image', [
            'token' => $token,
            'path' => $relativeDir.'/'.$token.'.'.$extension,
        ]);
    }

    private function discardPendingImageUnlessErrors(Request $request): void
    {
        if (!$request->session()->has('errors')) {
            $this->discardPendingImage();
        }
    }

    private function discardPendingImage(): void
    {
        $pending = session('slide_pending_image');
        if (is_array($pending) && isset($pending['path']) && is_string($pending['path'])) {
            $this->deletePendingFile($pending['path']);
        }

        session()->forget('slide_pending_image');
    }

    /**
     * @return array{token: string, path: string}|null
     */
    private function pendingImage(Request $request): ?array
    {
        $pending = $request->session()->get('slide_pending_image');
        $token = $request->input('kept_image');
        if (!is_array($pending) || !is_string($token)) {
            return null;
        }

        $storedToken = $pending['token'] ?? '';
        $path = $pending['path'] ?? '';
        if (!is_string($storedToken) || $storedToken === '' || !hash_equals($storedToken, $token)) {
            return null;
        }

        if (!is_string($path) || !preg_match('#\Aimages/slides/pending/[0-9a-fA-F-]{36}\.(jpg|png|gif|webp)\z#', $path)) {
            return null;
        }

        if (!File::exists(public_path($path))) {
            return null;
        }

        return [
            'token' => $storedToken,
            'path' => $path,
        ];
    }

    private function storedImagePath(Request $request): ?string
    {
        if ($request->hasFile('image')) {
            $path = $this->storeImage($request->file('image'));
            $this->discardPendingImage();

            return $path;
        }

        $pending = $this->pendingImage($request);
        if ($pending === null) {
            return null;
        }

        $path = $this->movePendingToSlides($pending);
        $request->session()->forget('slide_pending_image');

        return $path;
    }

    /**
     * @param  array{token: string, path: string}  $pending
     */
    private function movePendingToSlides(array $pending): string
    {
        $extension = pathinfo($pending['path'], PATHINFO_EXTENSION);
        $relativeDir = 'images/slides';
        $absoluteDir = public_path($relativeDir);
        if (!File::isDirectory($absoluteDir)) {
            File::makeDirectory($absoluteDir, 0755, true);
        }

        $milliseconds = (int) round(microtime(true) * 1000);
        $filename = 'slide-'.$milliseconds.'.'.$extension;
        $counter = 0;
        while (File::exists($absoluteDir.'/'.$filename)) {
            $counter++;
            $filename = 'slide-'.$milliseconds.'-'.$counter.'.'.$extension;
        }

        File::move(public_path($pending['path']), $absoluteDir.'/'.$filename);
        $this->cropToSlideRatio($absoluteDir.'/'.$filename);

        return $relativeDir.'/'.$filename;
    }

    private function deletePendingFile(string $path): void
    {
        if (!str_starts_with($path, 'images/slides/pending/')) {
            return;
        }

        $full = public_path($path);
        if (File::exists($full)) {
            File::delete($full);
        }
    }

    private function storeImage(UploadedFile $file): string
    {
        $relativeDir = 'images/slides';
        $absoluteDir = public_path($relativeDir);

        if (!File::isDirectory($absoluteDir)) {
            File::makeDirectory($absoluteDir, 0755, true);
        }

        $extension = $this->imageExtension($file);
        if ($extension === null) {
            throw new \RuntimeException('Formato de imagen no permitido.');
        }
        $milliseconds = (int) round(microtime(true) * 1000);
        $filename = 'slide-'.$milliseconds.'.'.$extension;
        $counter = 0;

        while (File::exists($absoluteDir.'/'.$filename)) {
            $counter++;
            $filename = 'slide-'.$milliseconds.'-'.$counter.'.'.$extension;
        }

        $file->move($absoluteDir, $filename);
        $this->cropToSlideRatio($absoluteDir.'/'.$filename);

        return $relativeDir.'/'.$filename;
    }

    /**
     * Recorta por el centro hasta 16:9. El lado que ya encaja se conserva.
     */
    private function cropToSlideRatio(string $absolutePath): void
    {
        $info = @getimagesize($absolutePath);
        if ($info === false || $info[0] < 1 || $info[1] < 1) {
            return;
        }

        $width = $info[0];
        $height = $info[1];
        if ($width * 9 === $height * 16) {
            return;
        }

        $target = 16 / 9;
        if (($width / $height) > $target) {
            $cropWidth = (int) round($height * $target);
            $cropHeight = $height;
            $x = (int) floor(($width - $cropWidth) / 2);
            $y = 0;
        } else {
            $cropWidth = $width;
            $cropHeight = (int) round($width / $target);
            $x = 0;
            $y = (int) floor(($height - $cropHeight) / 2);
        }

        $cropWidth = max(1, min($cropWidth, $width));
        $cropHeight = max(1, min($cropHeight, $height));
        $x = max(0, min($x, $width - $cropWidth));
        $y = max(0, min($y, $height - $cropHeight));

        $mime = (string) ($info['mime'] ?? '');
        $source = $this->gdImage($absolutePath, $mime);
        if (!$source instanceof \GdImage) {
            return;
        }

        $cropped = imagecrop($source, [
            'x' => $x,
            'y' => $y,
            'width' => $cropWidth,
            'height' => $cropHeight,
        ]);
        imagedestroy($source);
        if (!$cropped instanceof \GdImage) {
            return;
        }

        $this->saveGdImage($cropped, $absolutePath, $mime);
        imagedestroy($cropped);
    }

    private function gdImage(string $path, string $mime): \GdImage|false
    {
        $image = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/gif' => @imagecreatefromgif($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };

        return $image instanceof \GdImage ? $image : false;
    }

    private function saveGdImage(\GdImage $image, string $path, string $mime): void
    {
        if ($mime === 'image/png' || $mime === 'image/webp') {
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        match ($mime) {
            'image/jpeg' => imagejpeg($image, $path, 90),
            'image/png' => imagepng($image, $path),
            'image/gif' => imagegif($image, $path),
            'image/webp' => function_exists('imagewebp') ? imagewebp($image, $path, 90) : false,
            default => false,
        };
    }

    private function imageExtension(UploadedFile $file): ?string
    {
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file->getPathname());
        if ($mime === 'image/jpg') {
            $mime = 'image/jpeg';
        }

        return self::IMAGE_EXTENSIONS[$mime] ?? null;
    }

    private function deleteImageFile(?string $path): void
    {
        if ($path === null || !str_starts_with($path, 'images/slides/')) {
            return;
        }

        $full = public_path($path);
        if (File::exists($full)) {
            File::delete($full);
        }
    }
}
