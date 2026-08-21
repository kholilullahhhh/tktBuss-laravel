<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Schedule>
 */
class ScheduleFactory extends Factory
{
    public function definition(): array
    {
        $tanggal = fake()->dateTimeBetween('today', '+30 days')->format('Y-m-d');
        $jamBerangkat = fake()->time('H:i');
        $jamTiba = date('H:i', strtotime($jamBerangkat) + fake()->numberBetween(2, 14) * 3600);

        return [
            'bus_id' => Bus::factory(),
            'route_id' => Route::factory(),
            'tanggal_keberangkatan' => $tanggal,
            'jam_keberangkatan' => $jamBerangkat,
            'jam_tiba' => $jamTiba,
            'harga' => fake()->randomFloat(0, 75000, 500000),
            'status' => 'aktif',
        ];
    }
}
