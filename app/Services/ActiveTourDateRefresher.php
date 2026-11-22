<?php

namespace App\Services;

use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Status;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActiveTourDateRefresher
{
    /**
     * La primera salida de cada tour activo queda en hoy + 1 mes.
     * El resto de tramos y salidas de ese tour se mueven los mismos días,
     * así que la duración y la distancia entre salidas no cambian.
     */
    public function refresh(bool $dryRun = false): ActiveTourDateRefreshResult
    {
        $horizon = now()->startOfDay()->addMonthNoOverflow();
        $plans = $this->plans($horizon);

        if (!$dryRun) {
            DB::transaction(function () use ($plans) {
                foreach ($plans as $plan) {
                    if ($plan['kind'] !== 'update') {
                        continue;
                    }

                    foreach ($plan['segments'] as $segment) {
                        $this->shift($segment, $plan['days']);
                    }
                }
            });
        }

        $updated = array_values(array_filter($plans, static fn (array $plan) => $plan['kind'] === 'update'));
        $itineraryIds = [];
        foreach ($updated as $plan) {
            array_push($itineraryIds, ...$plan['itineraryIds']);
        }

        return new ActiveTourDateRefreshResult(
            horizon: $horizon,
            updated: array_map(static fn (array $plan) => [
                'name' => $plan['name'],
                'from' => $plan['from'],
                'to' => $plan['to'],
                'segments' => $plan['segmentCount'],
                'stockless' => $plan['stockless'],
                'hidden' => $plan['hidden'],
            ], $updated),
            unchanged: array_values(array_map(
                static fn (array $plan) => $plan['name'],
                array_filter($plans, static fn (array $plan) => $plan['kind'] === 'unchanged')
            )),
            withoutSegments: array_values(array_map(
                static fn (array $plan) => $plan['name'],
                array_filter($plans, static fn (array $plan) => $plan['kind'] === 'empty')
            )),
            segmentsUpdated: array_sum(array_map(static fn (array $plan) => $plan['segmentCount'], $updated)),
            itinerariesWithBookings: $this->bookedItineraryCount($itineraryIds),
        );
    }

    /**
     * @return list<array{
     *     kind: string,
     *     name: string,
     *     segments: \Illuminate\Support\Collection<int, Segment>,
     *     segmentCount: int,
     *     days: int,
     *     from: Carbon|null,
     *     to: Carbon|null,
     *     stockless: int,
     *     itineraryIds: list<int>,
     *     hidden: bool
     * }>
     */
    private function plans(Carbon $horizon): array
    {
        $tours = Product::query()
            ->whereStatusSlug(Status::PRODUCT_ACTIVE)
            ->whereHas('type', static fn ($type) => $type->where('slug', Type::TOUR))
            ->with(['itineraries.segments'])
            ->orderBy('id')
            ->get();

        return $tours->map(fn (Product $tour) => $this->planFor($tour, $horizon))->all();
    }

    /**
     * @return array{
     *     kind: string,
     *     name: string,
     *     segments: \Illuminate\Support\Collection<int, Segment>,
     *     segmentCount: int,
     *     days: int,
     *     from: Carbon|null,
     *     to: Carbon|null,
     *     stockless: int,
     *     itineraryIds: list<int>,
     *     hidden: bool
     * }
     */
    private function planFor(Product $tour, Carbon $horizon): array
    {
        $segments = $tour->itineraries->flatMap(
            static fn (Itinerary $itinerary) => $itinerary->segments
        );
        $earliest = $this->earliestOpening($tour);

        $plan = [
            'kind' => 'empty',
            'name' => $tour->name,
            'segments' => $segments,
            'segmentCount' => $segments->count(),
            'days' => 0,
            'from' => $earliest,
            'to' => null,
            'stockless' => $tour->itineraries
                ->filter(static fn (Itinerary $itinerary) => (int) $itinerary->available_stock <= 0)
                ->count(),
            'itineraryIds' => $tour->itineraries->pluck('id')->map(static fn ($id) => (int) $id)->all(),
            'hidden' => !$tour->isInPublicCategory(),
        ];

        if ($earliest === null) {
            return $plan;
        }

        if ($earliest->greaterThanOrEqualTo($horizon)) {
            $plan['kind'] = 'unchanged';

            return $plan;
        }

        $days = (int) $earliest->diffInDays($horizon);
        $plan['kind'] = 'update';
        $plan['days'] = $days;
        $plan['to'] = $earliest->copy()->addDays($days);

        return $plan;
    }

    private function earliestOpening(Product $tour): ?Carbon
    {
        $earliest = null;

        foreach ($tour->itineraries as $itinerary) {
            $departure = $itinerary->openingSegment()?->departure_date;
            if ($departure === null) {
                continue;
            }

            $day = $departure->copy()->startOfDay();
            if ($earliest === null || $day->lessThan($earliest)) {
                $earliest = $day;
            }
        }

        return $earliest;
    }

    private function shift(Segment $segment, int $days): void
    {
        $segment->departure_date = $segment->departure_date->copy()->addDays($days);
        if ($segment->arrival_date !== null) {
            $segment->arrival_date = $segment->arrival_date->copy()->addDays($days);
        }

        $segment->save();
    }

    /**
     * @param  list<int>  $itineraryIds
     */
    private function bookedItineraryCount(array $itineraryIds): int
    {
        if ($itineraryIds === []) {
            return 0;
        }

        return (int) DB::table('booking_itinerary')
            ->whereIn('itinerary_id', $itineraryIds)
            ->distinct()
            ->count('itinerary_id');
    }
}
