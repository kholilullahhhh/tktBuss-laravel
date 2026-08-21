<?php

namespace App\Console\Commands;

use App\Services\BookingService;
use Illuminate\Console\Command;

class ExpirePendingBookings extends Command
{
    protected $signature = 'bookings:expire';

    protected $description = 'Batalkan booking pending yang sudah melewati masa expired';

    public function handle(BookingService $bookingService): int
    {
        $count = $bookingService->expirePendingBookings();

        $this->info("{$count} booking pending expired dibatalkan.");

        return self::SUCCESS;
    }
}
