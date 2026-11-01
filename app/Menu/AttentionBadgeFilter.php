<?php

namespace App\Menu;

use App\Models\Booking;
use App\Models\ContactMessage;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class AttentionBadgeFilter implements FilterInterface
{
    public function transform($item)
    {
        $count = match ($item['url'] ?? null) {
            'admin/reservas' => Booking::query()->awaitingPayment()->count(),
            'admin/mensajes' => ContactMessage::query()->unread()->count(),
            default => null,
        };

        if ($count === null) {
            return $item;
        }

        unset($item['label'], $item['label_color']);

        if ($count > 0) {
            $item['label'] = $count;
            $item['label_color'] = 'success';
        }

        return $item;
    }
}
