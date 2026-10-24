<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Status;
use DateTimeInterface;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        return response()
            ->view('sitemap', ['urls' => $this->urls()])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $body = implode("\n", [
            'User-agent: *',
            'Disallow:',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ]);

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    /**
     * @return list<array{loc: string, lastmod: string|null}>
     */
    private function urls(): array
    {
        $urls = [];

        foreach ([
            route('inicio'),
            route('destinos'),
            route('servicios'),
            route('blogs'),
            route('contacto'),
            route('galeria'),
        ] as $loc) {
            $urls[] = $this->entry($loc);
        }

        $countries = Category::query()
            ->whereNull('parent_id')
            ->publiclyAvailable()
            ->whereHas('subCategories.products', function ($products) {
                $products->publicVisibleTour();
            })
            ->orderBy('slug')
            ->get();

        foreach ($countries as $country) {
            $urls[] = $this->entry(route('destinos.pais', ['country' => $country->slug]), $country->updated_at);
        }

        $provinces = Category::query()
            ->publiclyAvailable()
            ->whereNotNull('parent_id')
            ->whereHas('parentCategory', function ($parent) {
                $parent->whereNull('parent_id');
            })
            ->whereHas('products', function ($products) {
                $products->publicVisibleTour();
            })
            ->with('parentCategory')
            ->orderBy('slug')
            ->get();

        foreach ($provinces as $province) {
            $urls[] = $this->entry(route('destinos.provincia', [
                'country' => $province->parentCategory->slug,
                'province' => $province->slug,
            ]), $province->updated_at);
        }

        $tours = Product::query()
            ->publicVisibleTour()
            ->whereHas('category', function ($category) {
                $category->whereNotNull('parent_id');
            })
            ->with('category.parentCategory')
            ->orderBy('slug')
            ->get();

        foreach ($tours as $tour) {
            $urls[] = $this->entry(route('destinos.tour', [
                'country' => $tour->category->parentCategory->slug,
                'province' => $tour->category->slug,
                'tour' => $tour->slug,
            ]), $tour->updated_at);
        }

        $services = Product::query()
            ->publicVisibleService()
            ->orderBy('slug')
            ->get();

        foreach ($services as $service) {
            $urls[] = $this->entry(
                route('servicios.detalle', ['servicio' => $service->slug]),
                $service->updated_at
            );
        }

        $posts = Blog::query()
            ->whereStatusSlug(Status::BLOG_PUBLISHED)
            ->orderBy('slug')
            ->get();

        foreach ($posts as $post) {
            $urls[] = $this->entry(route('blogs.show', ['post' => $post->slug]), $post->updated_at);
        }

        return $urls;
    }

    /**
     * @return array{loc: string, lastmod: string|null}
     */
    private function entry(string $loc, ?DateTimeInterface $updatedAt = null): array
    {
        return [
            'loc' => $loc,
            'lastmod' => $updatedAt?->format(DateTimeInterface::ATOM),
        ];
    }
}
