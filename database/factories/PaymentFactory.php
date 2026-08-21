<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'order_id' => 'BUS-'.Str::random(6),
            'transaction_id' => Str::random(20),
            'payment_type' => fake()->randomElement(['bank_transfer', 'credit_card', 'qris', 'gopay']),
            'gross_amount' => fake()->randomFloat(0, 100000, 1000000),
            'transaction_status' => 'settlement',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ];
    }
}
