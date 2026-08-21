<?php

namespace Database\Factories;

use App\Models\Route;
use App\Models\Terminal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Route>
 */
class RouteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'terminal_asal_id' => Terminal::factory(),
            'terminal_tujuan_id' => Terminal::factory(),
            'jarak' => fake()->randomFloat(2, 50, 1500),
            'estimasi_durasi' => fake()->numberBetween(60, 1440),
            'status' => true,
        ];
    }
}
