<?php

namespace App\Support;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;

final class PublicBreadcrumbs
{
    /**
     * @return list<array{name: string, url: string}>
     */
    public static function items(): array
    {
        $route = request()->route()?->getName();

        $trail = match ($route) {
            'destinos' => [self::crumb('Destinos', route('destinos'))],
            'destinos.pais' => self::countryTrail(),
            'destinos.provincia' => self::provinceTrail(),
            'destinos.tour' => self::tourTrail(),
            'blogs' => [self::crumb('Blog', route('blogs'))],
            'blogs.show' => self::blogTrail(),
            'servicios' => [self::crumb('Servicios', route('servicios'))],
            'servicios.detalle' => self::serviceTrail(),
            'contacto' => [self::crumb('Contacto', route('contacto'))],
            'galeria' => [self::crumb('Galería', route('galeria'))],
            default => self::segmentTrail(),
        };

        return array_merge([self::crumb('Inicio', route('inicio'))], $trail);
    }

    /**
     * @return list<array{name: string, url: string}>
     */
    private static function countryTrail(): array
    {
        $slug = (string) request()->route('country');
        $country = self::rootCategory($slug);

        return [
            self::crumb('Destinos', route('destinos')),
            self::crumb($country?->name ?? self::label($slug), route('destinos.pais', $slug)),
        ];
    }

    /**
     * @return list<array{name: string, url: string}>
     */
    private static function provinceTrail(): array
    {
        $countrySlug = (string) request()->route('country');
        $provinceSlug = (string) request()->route('province');
        $country = self::rootCategory($countrySlug);
        $province = $country
            ? Category::query()->where('parent_id', $country->id)->where('slug', $provinceSlug)->first()
            : null;

        return [
            self::crumb('Destinos', route('destinos')),
            self::crumb($country?->name ?? self::label($countrySlug), route('destinos.pais', $countrySlug)),
            self::crumb(
                $province?->name ?? self::label($provinceSlug),
                route('destinos.provincia', ['country' => $countrySlug, 'province' => $provinceSlug])
            ),
        ];
    }

    /**
     * @return list<array{name: string, url: string}>
     */
    private static function tourTrail(): array
    {
        $countrySlug = (string) request()->route('country');
        $provinceSlug = (string) request()->route('province');
        $tourSlug = (string) request()->route('tour');
        $country = self::rootCategory($countrySlug);
        $province = $country
            ? Category::query()->where('parent_id', $country->id)->where('slug', $provinceSlug)->first()
            : null;
        $tour = Product::query()
            ->where('slug', $tourSlug)
            ->when($province, fn ($query) => $query->where('category_id', $province->id))
            ->first();

        return [
            ...self::provinceTrail(),
            self::crumb(
                $tour?->name ?? self::label($tourSlug),
                route('destinos.tour', [
                    'country' => $countrySlug,
                    'province' => $provinceSlug,
                    'tour' => $tourSlug,
                ])
            ),
        ];
    }

    /**
     * @return list<array{name: string, url: string}>
     */
    private static function blogTrail(): array
    {
        $slug = (string) request()->route('post');
        $post = Blog::query()->where('slug', $slug)->first();

        return [
            self::crumb('Blog', route('blogs')),
            self::crumb($post?->name ?? self::label($slug), route('blogs.show', $slug)),
        ];
    }

    /**
     * @return list<array{name: string, url: string}>
     */
    private static function serviceTrail(): array
    {
        $slug = (string) request()->route('servicio');
        $service = Product::query()->where('slug', $slug)->first();

        return [
            self::crumb('Servicios', route('servicios')),
            self::crumb($service?->name ?? self::label($slug), route('servicios.detalle', $slug)),
        ];
    }

    /**
     * @return list<array{name: string, url: string}>
     */
    private static function segmentTrail(): array
    {
        $crumbs = [];
        $url = url('/');

        foreach (request()->segments() as $segment) {
            $url .= '/'.$segment;
            $crumbs[] = self::crumb(self::label($segment), $url);
        }

        return $crumbs;
    }

    private static function rootCategory(string $slug): ?Category
    {
        if ($slug === '') {
            return null;
        }

        return Category::query()->where('slug', $slug)->whereNull('parent_id')->first();
    }

    private static function label(string $segment): string
    {
        return ucwords(str_replace('-', ' ', $segment));
    }

    /**
     * @return array{name: string, url: string}
     */
    private static function crumb(string $name, string $url): array
    {
        return ['name' => $name, 'url' => $url];
    }
}
