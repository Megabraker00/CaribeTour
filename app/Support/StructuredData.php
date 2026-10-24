<?php

namespace App\Support;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;

final class StructuredData
{
    /**
     * @param  list<array{name: string, url: string}>  $items
     * @return array<string, mixed>
     */
    public static function breadcrumbList(array $items): array
    {
        $elements = [];
        foreach (array_values($items) as $index => $item) {
            $elements[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $elements,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function organization(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'CaribeTour',
            'url' => route('inicio'),
            'logo' => asset('images/logo.png'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function article(Blog $post): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->name,
            'description' => $post->excerpt(160),
            'mainEntityOfPage' => url()->current(),
            'author' => filled($post->createdUser?->name)
                ? ['@type' => 'Person', 'name' => $post->createdUser->name]
                : ['@type' => 'Organization', 'name' => 'CaribeTour'],
        ];

        $image = self::blogImageUrl($post);
        if ($image) {
            $data['image'] = $image;
        }

        if ($post->created_at) {
            $data['datePublished'] = $post->created_at->toAtomString();
        }

        if ($post->updated_at) {
            $data['dateModified'] = $post->updated_at->toAtomString();
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    public static function touristTrip(Product $tour, string $description, ?float $price): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'TouristTrip',
            'name' => $tour->name,
            'description' => $description,
            'url' => url()->current(),
            'provider' => [
                '@type' => 'Organization',
                'name' => 'CaribeTour',
                'url' => route('inicio'),
            ],
        ];

        $image = self::productImageUrl($tour);
        if ($image) {
            $data['image'] = $image;
        }

        $place = self::placeName($tour);
        if ($place !== '') {
            $data['itinerary'] = [
                '@type' => 'Place',
                'name' => $place,
            ];
        }

        if ($price !== null) {
            $data['offers'] = [
                '@type' => 'Offer',
                'price' => number_format($price, 2, '.', ''),
                'priceCurrency' => 'EUR',
                'url' => url()->current(),
            ];
        }

        return $data;
    }

    public static function productImageUrl(Product $tour): ?string
    {
        $images = $tour->relationLoaded('images') ? $tour->images : $tour->images()->get();

        return self::absolutePath(self::preferredImagePath($images));
    }

    public static function categoryImageUrl(Category $category): ?string
    {
        $images = $category->relationLoaded('images') ? $category->images : $category->images()->get();

        return self::absolutePath(self::preferredImagePath($images));
    }

    public static function blogImageUrl(Blog $post): ?string
    {
        return self::absolutePath($post->mainImage()?->path);
    }

    /**
     * @param  Collection<int, \App\Models\Image>  $images
     */
    private static function preferredImagePath(Collection $images): ?string
    {
        $image = $images->first(fn ($image) => (bool) $image->is_main) ?? $images->first();

        return filled($image?->path) ? $image->path : null;
    }

    private static function placeName(Product $tour): string
    {
        $province = $tour->category?->name;
        $country = $tour->category?->parentCategory?->name;

        if (!filled($province)) {
            return filled($country) ? $country : '';
        }

        return filled($country) ? $province.', '.$country : $province;
    }

    private static function absolutePath(?string $path): ?string
    {
        return filled($path) ? asset($path) : null;
    }
}
