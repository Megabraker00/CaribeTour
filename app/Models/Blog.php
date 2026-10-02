<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'content',
        'status_id',
        'created_user_id',
        'author_linkedin',
        'author_facebook',
        'author_instagram',
        'author_x',
    ];

    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }

    public function user(): BelongsTo
    {
        return $this->createdUser();
    }

    public function statusRecord(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function excerpt(int $length = 100): string
    {
        $html = (string) $this->content;
        $html = preg_replace('/<\s*br\s*\/?>/i', "\n", $html) ?? $html;
        $html = preg_replace('/<\/(p|div|h[1-6]|li|blockquote)>/i', "\n", $html) ?? $html;

        $plain = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $plain = preg_replace('/[^\S\n]+/u', ' ', $plain) ?? $plain;
        $plain = preg_replace("/\n{3,}/u", "\n\n", $plain) ?? $plain;

        return Str::limit(trim($plain), $length);
    }

    public function excerptHtml(int $length = 100): string
    {
        return nl2br(e($this->excerpt($length)), false);
    }

    /**
     * @return list<array{key: string, label: string, url: string, icon: string, style: string}>
     */
    public function authorSocialLinks(): array
    {
        $networks = [
            [
                'key' => 'linkedin',
                'label' => 'LinkedIn',
                'url' => $this->author_linkedin,
                'icon' => 'bi bi-linkedin',
                'style' => 'color: #0a66c2;',
            ],
            [
                'key' => 'facebook',
                'label' => 'Facebook',
                'url' => $this->author_facebook,
                'icon' => 'bi bi-facebook',
                'style' => 'color: rgb(6, 82, 223);',
            ],
            [
                'key' => 'instagram',
                'label' => 'Instagram',
                'url' => $this->author_instagram,
                'icon' => 'bi bi-instagram',
                'style' => 'color: rgb(233, 62, 147);',
            ],
            [
                'key' => 'x',
                'label' => 'X',
                'url' => $this->author_x,
                'icon' => 'bi bi-twitter-x',
                'style' => 'color: black;',
            ],
        ];

        return array_values(array_filter($networks, fn (array $network) => filled($network['url'])));
    }

    public function mainImage(): ?Image
    {
        $images = $this->relationLoaded('images') ? $this->images : $this->images()->get();

        return $images->firstWhere('is_main', true) ?? $images->first();
    }

    public function mainImageAlt(): string
    {
        $alt = $this->mainImage()?->alt;

        return filled($alt) ? $alt : (string) $this->name;
    }

    public function persistMainImage(?UploadedFile $file, ?string $alt, ?int $uploadedBy = null): void
    {
        $alt = filled($alt) ? $alt : null;

        if ($file) {
            $this->storeMainImage($file, $uploadedBy, $alt);

            return;
        }

        $image = $this->mainImage();
        if ($image) {
            $image->update(['alt' => $alt]);
        }
    }

    public function storeMainImage(UploadedFile $file, ?int $uploadedBy = null, ?string $alt = null): ?Image
    {
        if (!$file->isValid()) {
            return null;
        }

        $safeSlug = $this->safeImageFolderSlug();
        $relativeDir = 'images/blogs/'.$safeSlug;
        $absoluteDir = public_path($relativeDir);

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg');
        $allowedExt = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
        if (!in_array($extension, $allowedExt, true)) {
            return null;
        }

        if (!File::isDirectory($absoluteDir)) {
            File::makeDirectory($absoluteDir, 0755, true);
        }

        $this->deleteStoredImages();

        $milliseconds = (int) round(microtime(true) * 1000);
        $base = $safeSlug.'-'.$milliseconds;
        $filename = $base.'.'.$extension;
        $counter = 0;
        while (File::exists($absoluteDir.'/'.$filename)) {
            $counter++;
            $filename = $base.'-'.$counter.'.'.$extension;
        }

        $file->move($absoluteDir, $filename);

        return $this->images()->create([
            'name' => pathinfo($filename, PATHINFO_FILENAME),
            'alt' => $alt,
            'path' => $relativeDir.'/'.$filename,
            'is_main' => true,
            'uploaded_user_id' => $uploadedBy ?? auth()->id() ?? 1,
        ]);
    }

    public function deleteStoredImages(): void
    {
        foreach ($this->images()->get() as $img) {
            if ($img->path) {
                $full = public_path($img->path);
                if (File::exists($full)) {
                    File::delete($full);
                }
            }
            $img->delete();
        }
    }

    private function safeImageFolderSlug(): string
    {
        $clean = preg_replace('/[^a-z0-9\-]/', '', strtolower((string) $this->slug));

        return $clean !== '' ? $clean : 'blog-'.$this->id;
    }

    public function state(): MorphOne
    {
        return $this->morphOne(Status::class, 'statusable');
    }

    public function create_date()
    {
        //return date('d-m-Y', strtotime($this->created_at));
        return $this->create_at?->format('d-m-Y');
    }
}
