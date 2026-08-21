<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $buses = Bus::orderBy('id')->get();
        $routes = Route::orderBy('id')->get();

        if ($buses->isEmpty() || $routes->isEmpty()) {
            return;
        }

        $rateByKelas = [
            'Ekonomi' => 120,
            'Bisnis' => 180,
            'Executive' => 250,
            'Sleeper' => 300,
        ];

        $jamKeberangkatan = ['06:00', '07:00', '09:00', '11:00', '13:00', '15:00', '18:00', '20:00', '22:00'];

        // Buat 30 jadwal untuk 30 hari ke depan
        foreach (range(0, 29) as $i) {
            $bus = $buses[$i % $buses->count()];
            $route = $routes[$i % $routes->count()];

            $tanggal = now()->addDays($i)->format('Y-m-d');
            $jamBerangkat = $jamKeberangkatan[$i % count($jamKeberangkatan)];
            $durasi = $route->estimasi_durasi;
            $jamTiba = date('H:i', strtotime($jamBerangkat) + $durasi * 60);

            $rate = $rateByKelas[$bus->kelas] ?? 150;
            $harga = (int) round(($route->jarak * $rate) / 1000) * 1000;

            Schedule::updateOrCreate(
                [
                    'bus_id' => $bus->id,
                    'route_id' => $route->id,
                    'tanggal_keberangkatan' => $tanggal,
                    'jam_keberangkatan' => $jamBerangkat,
                ],
                [
                    'jam_tiba' => $jamTiba,
                    'harga' => max($harga, 50000),
                    'status' => 'aktif',
                ]
            );
        }
    }
}
