<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait ReadsPlainMetaDescription
{
    public function plainMetaDescription(int $limit = 160): string
    {
        $html = (string) ($this->meta['description'] ?? '');
        $plain = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $plain = trim(preg_replace('/\s+/u', ' ', $plain) ?? '');

        if ($plain === '') {
            return '';
        }

        return Str::limit($plain, $limit);
    }
}
