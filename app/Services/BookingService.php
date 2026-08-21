<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    /**
     * Buat booking baru dengan pencegahan double-booking yang aman
     * terhadap concurrent request (row locking + validasi ulang).
     *
     * @param  array<int>  $seatIds
     * @param  array<int, array<string, mixed>>  $passengers
     */
    public function book(User $user, Schedule $schedule, array $seatIds, array $passengers): Booking
    {
        if (count($seatIds) !== count($passengers)) {
            throw new \InvalidArgumentException('Jumlah kursi dan data penumpang tidak sesuai.');
        }

        $seatIds = array_values(array_unique($seatIds));

        return DB::transaction(function () use ($user, $schedule, $seatIds, $passengers) {
            // Kunci baris jadwal sehingga pembuatan booking untuk jadwal yang sama
            // terserialisasi (mencegah dua customer memesan kursi yang sama).
            Schedule::whereKey($schedule->id)->lockForUpdate()->first();

            $seatIdsFromBus = $schedule->bus->seats()
                ->whereIn('id', $seatIds)
                ->where('status', 'aktif')
                ->pluck('id')
                ->all();

            if (count($seatIdsFromBus) !== count($seatIds)) {
                throw new \RuntimeException('Salah satu kursi tidak ditemukan atau tidak tersedia pada bus jadwal ini.');
            }

            // Kunci baris kursi yang akan dipesan.
            Seat::whereIn('id', $seatIdsFromBus)->lockForUpdate()->get();

            // Validasi ulang ketersediaan di dalam transaksi.
            $conflict = array_intersect($this->bookedSeatIds($schedule), $seatIdsFromBus);
            if (! empty($conflict)) {
                $nomor = Seat::whereIn('id', $conflict)->pluck('nomor_kursi')->implode(', ');
                throw new \RuntimeException("Kursi {$nomor} sudah dipesan oleh penumpang lain.");
            }

            $harga = (float) $schedule->harga;
            $total = $harga * count($seatIdsFromBus);

            $booking = Booking::create([
                'user_id' => $user->id,
                'schedule_id' => $schedule->id,
                'kode_booking' => $this->generateBookingCode(),
                'tanggal_booking' => now(),
                'total_harga' => $total,
                'status_booking' => 'pending',
                'status_pembayaran' => 'unpaid',
                'payment_method' => null,
                'paid_at' => null,
                'expired_at' => now()->addHours(2),
            ]);

            foreach ($passengers as $i => $p) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'schedule_id' => $schedule->id,
                    'seat_id' => $seatIdsFromBus[$i],
                    'harga' => $harga,
                    'nama_penumpang' => $p['nama_penumpang'] ?? '',
                    'nik' => $p['nik'] ?? null,
                    'no_hp' => $p['no_hp'] ?? null,
                    'jenis_kelamin' => $p['jenis_kelamin'] ?? null,
                    'tanggal_lahir' => $p['tanggal_lahir'] ?? null,
                ]);
            }

            return $booking;
        });
    }

    /**
     * Kode booking unik: BUS-YYYYMMDD-XXXXXX
     */
    public function generateBookingCode(): string
    {
        $prefix = 'BUS-'.now()->format('Ymd').'-';

        do {
            $code = $prefix.strtoupper(Str::random(6));
        } while (Booking::where('kode_booking', $code)->exists());

        return $code;
    }

    /**
     * ID kursi yang sedang dipesan oleh booking aktif pada jadwal tertentu.
     * Booking pending yang sudah lewat masa expired dianggap tersedia kembali.
     */
    public function bookedSeatIds(Schedule $schedule): array
    {
        return BookingSeat::where('schedule_id', $schedule->id)
            ->whereHas('booking', function ($q) {
                $q->where(function ($active) {
                    $active->whereIn('status_booking', ['confirmed', 'completed']);
                })->orWhere(function ($pending) {
                    $pending->where('status_booking', 'pending')
                        ->where(fn ($notExpired) => $notExpired
                            ->whereNull('expired_at')
                            ->orWhere('expired_at', '>', now()));
                });
            })
            ->pluck('seat_id')
            ->all();
    }

    /**
     * Semua kursi bus jadwal beserta status ketersediaannya.
     *
     * @return array<int, array{id:int, nomor_kursi:string, posisi:string, status:string, is_booked:bool}>
     */
    public function seatAvailability(Schedule $schedule): array
    {
        $booked = array_flip($this->bookedSeatIds($schedule));

        return $schedule->bus->seats()
            ->orderByRaw('CAST(substr(nomor_kursi, 1, length(nomor_kursi)-1) AS UNSIGNED)')
            ->orderBy('nomor_kursi')
            ->get()
            ->map(fn (Seat $seat) => [
                'id' => $seat->id,
                'nomor_kursi' => $seat->nomor_kursi,
                'posisi' => $seat->posisi,
                'status' => $seat->status,
                'is_booked' => isset($booked[$seat->id]),
            ])
            ->all();
    }

    /**
     * Batalkan booking (kursi dilepas) jika masih pending/unpaid.
     */
    public function cancel(Booking $booking): bool
    {
        if (! in_array($booking->status_booking, ['pending', 'confirmed'])) {
            throw new \RuntimeException('Booking tidak dapat dibatalkan pada status ini.');
        }

        if ($booking->status_pembayaran === 'paid') {
            throw new \RuntimeException('Booking yang sudah lunas tidak dapat dibatalkan secara mandiri. Hubungi admin.');
        }

        return DB::transaction(function () use ($booking) {
            $booking->update([
                'status_booking' => 'cancelled',
                'status_pembayaran' => 'expired',
            ]);

            $booking->payment?->update([
                'payment_status' => 'expired',
                'transaction_status' => 'expire',
            ]);

            return true;
        });
    }

    /**
     * Batalkan semua booking pending yang sudah lewat masa expired.
     */
    public function expirePendingBookings(): int
    {
        $expired = Booking::where('status_booking', 'pending')
            ->where('expired_at', '<', now())
            ->get();

        foreach ($expired as $booking) {
            $booking->update([
                'status_booking' => 'cancelled',
                'status_pembayaran' => 'expired',
            ]);
            $booking->payment?->update(['payment_status' => 'expired']);
        }

        return $expired->count();
    }
}
