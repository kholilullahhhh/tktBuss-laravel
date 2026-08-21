<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\Operator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bus>
 */
class BusFactory extends Factory
{
    public function definition(): array
    {
        $kelas = fake()->randomElement(['Ekonomi', 'Bisnis', 'Executive', 'Sleeper']);

        return [
            'operator_id' => Operator::factory(),
            'nomor_polisi' => 'DD '.fake()->randomNumber(4).' '.fake()->randomElement(['AB', 'CD', 'EF', 'GH']),
            'kode_bus' => strtoupper(fake()->bothify('BUS-####')),
            'nama_bus' => fake()->randomElement(['Harmoni', 'Sinar Jaya', 'Lorena', 'Haryanto', 'Rosalia']).' '.fake()->numerify('##'),
            'kelas' => $kelas,
            'kapasitas' => fake()->randomElement([40, 44, 48, 52]),
            'fasilitas' => 'AC, Reclining Seat, Toilet, USB Charger, Wifi',
            'status' => true,
        ];
    }
}
