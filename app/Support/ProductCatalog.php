<?php

namespace App\Support;

use App\Models\Product;
use App\Models\Type;

final class ProductCatalog
{
    /**
     * Catálogo admin bajo PRODUCTOS (slug de URL → tipo de producto).
     *
     * @var array<string, array{type_slug: string, label: string, singular: string, image_folder: string}>
     */
    public const KINDS = [
        'tours' => [
            'type_slug' => Type::TOUR,
            'label' => 'Tours',
            'singular' => 'Tour',
            'image_folder' => 'tours',
        ],
        'excursiones' => [
            'type_slug' => Type::EXCURSION,
            'label' => 'Excursiones',
            'singular' => 'Excursión',
            'image_folder' => 'excursiones',
        ],
        'hoteles' => [
            'type_slug' => Type::HOTEL,
            'label' => 'Hoteles',
            'singular' => 'Hotel',
            'image_folder' => 'hoteles',
        ],
        'seguros' => [
            'type_slug' => Type::INSURANCE,
            'label' => 'Seguros',
            'singular' => 'Seguro',
            'image_folder' => 'seguros',
        ],
    ];

    public static function slugPattern(): string
    {
        return implode('|', array_keys(self::KINDS));
    }

    /**
     * @return array{kind: string, type_slug: string, type_id: int, label: string, singular: string, image_folder: string}
     */
    public static function fromKind(string $kind): array
    {
        if (!isset(self::KINDS[$kind])) {
            abort(404);
        }

        $meta = self::KINDS[$kind];

        return array_merge(['kind' => $kind], $meta, [
            'type_id' => Type::idFor(Product::class, $meta['type_slug']),
        ]);
    }

    /**
     * @return array{kind: string, type_slug: string, type_id: int, label: string, singular: string, image_folder: string}
     */
    public static function fromTypeId(int $typeId): array
    {
        $slug = Type::query()->whereKey($typeId)->value('slug');

        foreach (self::KINDS as $kind => $meta) {
            if ($meta['type_slug'] === $slug) {
                return array_merge(['kind' => $kind], $meta, ['type_id' => $typeId]);
            }
        }

        return self::fromKind('tours');
    }

    /**
     * @return array{kind: string, type_slug: string, type_id: int, label: string, singular: string, image_folder: string}
     */
    public static function fromProduct(Product $product): array
    {
        return self::fromTypeId((int) $product->type_id);
    }

    public static function namedRoute(string $action, Product $product, array $params = []): string
    {
        $catalog = self::fromProduct($product);

        return route('admin.catalog.'.$action, array_merge([
            'kind' => $catalog['kind'],
            'id' => $product->id,
        ], $params));
    }

    public static function showUrl(Product $product): string
    {
        return self::namedRoute('show', $product);
    }

    public static function editUrl(Product $product): string
    {
        return self::namedRoute('edit', $product);
    }
}
