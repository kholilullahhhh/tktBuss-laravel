<?php

namespace Database\Factories;

use App\Models\Terminal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Terminal>
 */
class TerminalFactory extends Factory
{
    public function definition(): array
    {
        $kota = fake()->city();

        return [
            'nama_terminal' => 'Terminal '.$kota,
            'kode_terminal' => 'TRM-'.strtoupper(Str::random(4)),
            'alamat' => fake()->streetAddress(),
            'kota' => $kota,
            'provinsi' => fake()->state(),
            'status' => true,
        ];
    }
}
