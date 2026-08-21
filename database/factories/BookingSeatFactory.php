<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingSeat>
 */
class BookingSeatFactory extends Factory
{
    public function definition(): array
    {
        $booking = Booking::factory()->create();
        $seat = Seat::factory()->create(['bus_id' => $booking->schedule->bus_id]);

        return [
            'booking_id' => $booking->id,
            'schedule_id' => $booking->schedule_id,
            'seat_id' => $seat->id,
            'harga' => $booking->schedule->harga,
            'nama_penumpang' => fake()->name(),
            'nik' => fake()->numerify('################'),
            'no_hp' => fake()->phoneNumber(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'tanggal_lahir' => fake()->date(),
        ];
    }
}
