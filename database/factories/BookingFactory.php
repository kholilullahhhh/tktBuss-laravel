<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        $schedule = Schedule::factory()->create();

        return [
            'user_id' => User::factory(),
            'schedule_id' => $schedule->id,
            'kode_booking' => 'BUS-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),
            'tanggal_booking' => now(),
            'total_harga' => fake()->randomFloat(0, 100000, 1000000),
            'status_booking' => 'confirmed',
            'status_pembayaran' => 'paid',
            'payment_method' => 'midtrans',
            'paid_at' => now(),
            'expired_at' => null,
        ];
    }
}
