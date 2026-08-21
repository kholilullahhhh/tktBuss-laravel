<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = Schedule::with(['bus.seats', 'route'])->where('tanggal_keberangkatan', '>=', now()->toDateString())->get();

        if ($schedules->isEmpty()) {
            return;
        }

        $customerRole = Role::where('slug', 'customer')->first();
        $customer = User::where('email', 'customer@busticket.test')->first();

        // Tambah beberapa customer dummy
        $customers = [$customer];
        foreach (range(1, 6) as $i) {
            $customers[] = User::factory()->create(['role_id' => $customerRole->id]);
        }

        $usedSeats = []; // schedule_id => [seat_id]

        $defs = [
            // [customer_index, jumlah_kursi, status_booking, status_pembayaran]
            [0, 2, 'confirmed', 'paid'],
            [0, 1, 'confirmed', 'paid'],
            [0, 1, 'pending', 'unpaid'],
            [1, 3, 'confirmed', 'paid'],
            [2, 1, 'confirmed', 'paid'],
            [3, 2, 'confirmed', 'paid'],
            [4, 1, 'cancelled', 'unpaid'],
            [5, 2, 'confirmed', 'paid'],
            [6, 1, 'confirmed', 'paid'],
            [1, 2, 'confirmed', 'paid'],
        ];

        foreach ($defs as $i => $def) {
            $customer = $customers[$def[0] % count($customers)];
            $schedule = $schedules[$i % $schedules->count()];

            // Ambil kursi yang masih tersedia
            $bookedIds = $usedSeats[$schedule->id] ?? [];
            $availableSeats = $schedule->bus->seats->filter(fn ($seat) => ! in_array($seat->id, $bookedIds));

            if ($availableSeats->count() < $def[1]) {
                continue;
            }

            $chosenSeats = $availableSeats->take($def[1]);
            foreach ($chosenSeats as $seat) {
                $usedSeats[$schedule->id][] = $seat->id;
            }

            $kodeBooking = 'BUS-'.date('Ymd', strtotime($schedule->tanggal_keberangkatan)).'-'.str_pad((string) ($i + 1), 6, '0', STR_PAD_LEFT);

            $isPaid = $def[3] === 'paid';
            $totalHarga = $schedule->harga * $def[1];

            $booking = Booking::create([
                'user_id' => $customer->id,
                'schedule_id' => $schedule->id,
                'kode_booking' => $kodeBooking,
                'tanggal_booking' => now()->subDays($i % 5),
                'total_harga' => $totalHarga,
                'status_booking' => $def[2],
                'status_pembayaran' => $def[3],
                'payment_method' => $isPaid ? 'midtrans' : null,
                'paid_at' => $isPaid ? now()->subDays($i % 5) : null,
                'expired_at' => $def[2] === 'pending' ? now()->addHours(2) : null,
            ]);

            foreach ($chosenSeats as $seat) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'schedule_id' => $schedule->id,
                    'seat_id' => $seat->id,
                    'harga' => $schedule->harga,
                    'nama_penumpang' => $customer->name,
                    'nik' => (string) rand(3200000000000000, 3299999999999999),
                    'no_hp' => $customer->phone ?: '081234567890',
                    'jenis_kelamin' => ['L', 'P'][$i % 2],
                    'tanggal_lahir' => now()->subYears(rand(20, 50))->toDateString(),
                ]);
            }

            if ($isPaid) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'order_id' => $kodeBooking,
                    'transaction_id' => 'seed-'.strtoupper(substr(md5($kodeBooking), 0, 10)),
                    'payment_type' => ['bank_transfer', 'qris', 'gopay', 'credit_card'][$i % 4],
                    'gross_amount' => $totalHarga,
                    'transaction_status' => 'settlement',
                    'payment_status' => 'paid',
                    'paid_at' => $booking->paid_at,
                ]);
            }
        }
    }
}
