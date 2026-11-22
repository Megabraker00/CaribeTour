<?php

namespace App\Console\Commands;

use App\Services\ActiveTourDateRefresher;
use App\Services\ActiveTourDateRefreshResult;
use Illuminate\Console\Command;

class RefreshActiveTourDates extends Command
{
    protected $signature = 'tours:refresh-dates
        {--dry-run : Muestra las fechas nuevas sin guardarlas}
        {--force : Permite ejecutarlo cuando APP_ENV es production}';

    protected $description = 'Adelanta las salidas de los tours activos para que la primera quede dentro de un mes';

    public function handle(ActiveTourDateRefresher $refresher): int
    {
        if ($this->laravel->environment('production') && !$this->option('force')) {
            $this->error('Este comando reescribe fechas de salida. En producción hace falta --force.');

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $result = $refresher->refresh($dryRun);
        $this->report($result, $dryRun);

        return self::SUCCESS;
    }

    private function report(ActiveTourDateRefreshResult $result, bool $dryRun): void
    {
        $horizon = $result->horizon->format('d/m/Y');
        $timezone = $result->horizon->timezoneName;

        if ($dryRun) {
            $this->warn('Simulación. No se ha guardado ningún cambio.');
        }

        $this->line("La primera salida de cada tour activo queda el {$horizon} ({$timezone}).");

        if ($result->updated === []) {
            $this->info('Ningún tour activo tiene la primera salida antes de esa fecha.');
        } else {
            $this->table(
                ['Tour', 'Primera salida', 'Nueva salida', 'Tramos'],
                array_map(static fn (array $tour) => [
                    $tour['name'],
                    $tour['from']->format('d/m/Y'),
                    $tour['to']->format('d/m/Y'),
                    $tour['segments'],
                ], $result->updated)
            );
            $this->info(sprintf(
                'Tours actualizados: %d. Tramos actualizados: %d.',
                count($result->updated),
                $result->segmentsUpdated
            ));
        }

        if ($result->unchanged !== []) {
            $this->line('Sin cambios, ya salen en esa fecha o después: '.count($result->unchanged).'.');
        }

        if ($result->withoutSegments !== []) {
            $this->warn(
                'Tours activos sin ningún tramo, no se pueden mostrar: '.implode(', ', $result->withoutSegments).'.'
            );
        }

        $stockless = array_sum(array_map(static fn (array $tour) => $tour['stockless'], $result->updated));
        if ($stockless > 0) {
            $this->warn("{$stockless} salidas siguen con 0 plazas. El calendario del tour no las muestra.");
        }

        $hidden = count(array_filter($result->updated, static fn (array $tour) => $tour['hidden']));
        if ($hidden > 0) {
            $this->warn("{$hidden} tours siguen ocultos porque su categoría o el país no están activos.");
        }

        if ($result->itinerariesWithBookings > 0) {
            $this->warn(
                "{$result->itinerariesWithBookings} salidas tienen reservas. Esas reservas pasan a mostrar la fecha nueva."
            );
        }
    }
}
