<?php

namespace App\Services;

use Carbon\Carbon;

class ActiveTourDateRefreshResult
{
    /**
     * @param  list<array{name: string, from: Carbon, to: Carbon, segments: int, stockless: int, hidden: bool}>  $updated
     * @param  list<string>  $unchanged
     * @param  list<string>  $withoutSegments
     */
    public function __construct(
        public readonly Carbon $horizon,
        public readonly array $updated,
        public readonly array $unchanged,
        public readonly array $withoutSegments,
        public readonly int $segmentsUpdated,
        public readonly int $itinerariesWithBookings,
    ) {
    }
}
