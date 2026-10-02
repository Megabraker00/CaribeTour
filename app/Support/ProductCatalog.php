<?php

namespace App\Support;

use App\Models\Product;
use App\Models\Type;

final class ProductCatalog
{
    /**
     * Catálogo admin bajo PRODUCTOS (slug de URL → tipo de producto).
     *
     * @var array<string, array{type_id: int, label: string, singular: string, image_folder: string}>
     */
    public const KINDS = [
        'tours' => [
            'type_id' => Type::TOUR,
            'label' => 'Tours',
            'singular' => 'Tour',
            'image_folder' => 'tours',
        ],
        'excursiones' => [
            'type_id' => Type::EXCURSION,
            'label' => 'Excursiones',
            'singular' => 'Excursión',
            'image_folder' => 'excursiones',
        ],
        'hoteles' => [
            'type_id' => Type::HOTEL,
            'label' => 'Hoteles',
            'singular' => 'Hotel',
            'image_folder' => 'hoteles',
        ],
        'seguros' => [
            'type_id' => Type::INSURANCE,
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
     * @return array{kind: string, type_id: int, label: string, singular: string, image_folder: string}
     */
    public static function fromKind(string $kind): array
    {
        if (!isset(self::KINDS[$kind])) {
            abort(404);
        }

        return array_merge(['kind' => $kind], self::KINDS[$kind]);
    }

    /**
     * @return array{kind: string, type_id: int, label: string, singular: string, image_folder: string}
     */
    public static function fromTypeId(int $typeId): array
    {
        foreach (self::KINDS as $kind => $meta) {
            if ($meta['type_id'] === $typeId) {
                return array_merge(['kind' => $kind], $meta);
            }
        }

        return array_merge(['kind' => 'tours'], self::KINDS['tours']);
    }

    /**
     * @return array{kind: string, type_id: int, label: string, singular: string, image_folder: string}
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
