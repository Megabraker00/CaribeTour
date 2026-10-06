<?php

namespace App\Console\Commands;

use App\Services\ReservationService;
use Illuminate\Console\Command;

class ReleaseUnpaidReservations extends Command
{
    protected $signature = 'reservations:release-unpaid';

    protected $description = 'Release stock and cancel payment intents for unpaid reservations past the hold.';

    public function handle(ReservationService $reservations): int
    {
        $released = $reservations->releaseExpiredUnpaidReservations();
        $this->info("Released {$released} unpaid reservation(s).");

        return self::SUCCESS;
    }
}
