<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Seat>
 */
class SeatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'bus_id' => Bus::factory(),
            'nomor_kursi' => fake()->randomElement(['1A', '1B', '2A', '2B', '3A', '3B']),
            'posisi' => 'kiri',
            'status' => 'aktif',
        ];
    }
}
