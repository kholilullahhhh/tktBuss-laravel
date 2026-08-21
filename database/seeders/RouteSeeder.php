<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\Terminal;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        $routes = [
            // [asal, tujuan, jarak_km, durasi_menit]
            ['Jakarta Timur', 'Bandung', 150, 180],
            ['Jakarta Timur', 'Semarang', 440, 420],
            ['Jakarta Timur', 'Surakarta', 560, 540],
            ['Jakarta Timur', 'Yogyakarta', 580, 540],
            ['Jakarta Timur', 'Surabaya', 790, 660],
            ['Bandung', 'Semarang', 300, 360],
            ['Bandung', 'Surabaya', 660, 600],
            ['Semarang', 'Yogyakarta', 110, 150],
            ['Yogyakarta', 'Surabaya', 330, 360],
            ['Jakarta Barat', 'Yogyakarta', 560, 540],
        ];

        foreach ($routes as $r) {
            $asal = Terminal::where('kota', $r[0])->first();
            $tujuan = Terminal::where('kota', $r[1])->first();

            if (! $asal || ! $tujuan || $asal->id === $tujuan->id) {
                continue;
            }

            $existing = Route::where('terminal_asal_id', $asal->id)
                ->where('terminal_tujuan_id', $tujuan->id)
                ->first();

            if ($existing) {
                continue;
            }

            Route::create([
                'terminal_asal_id' => $asal->id,
                'terminal_tujuan_id' => $tujuan->id,
                'jarak' => $r[2],
                'estimasi_durasi' => $r[3],
                'status' => true,
            ]);

            // Rute sebaliknya
            $reverse = Route::where('terminal_asal_id', $tujuan->id)
                ->where('terminal_tujuan_id', $asal->id)
                ->first();

            if (! $reverse) {
                Route::create([
                    'terminal_asal_id' => $tujuan->id,
                    'terminal_tujuan_id' => $asal->id,
                    'jarak' => $r[2],
                    'estimasi_durasi' => $r[3],
                    'status' => true,
                ]);
            }
        }
    }
}
