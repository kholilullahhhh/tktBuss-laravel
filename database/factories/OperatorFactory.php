<?php

namespace Database\Factories;

use App\Models\Operator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Operator>
 */
class OperatorFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'nama_operator' => $name,
            'kode_operator' => 'OP-'.strtoupper(Str::random(5)),
            'alamat' => fake()->address(),
            'telepon' => fake()->phoneNumber(),
            'email' => fake()->unique()->companyEmail(),
            'status' => true,
        ];
    }
}
