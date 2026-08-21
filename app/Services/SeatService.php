<?php

namespace App\Services;

use App\Models\Seat;
use App\Repositories\SeatRepository;
use Illuminate\Support\Facades\DB;

class SeatService extends BaseService
{
    public function __construct(SeatRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Generate kursi otomatis untuk sebuah bus berdasarkan kapasitas.
     * Format nomor kursi: 1A, 1B, 1C, 1D, 2A, 2B, ...
     */
    public function generateForBus(int $busId, int $kapasitas): int
    {
        $columns = ['A', 'B', 'C', 'D'];
        $created = 0;

        DB::transaction(function () use ($busId, $kapasitas, $columns, &$created) {
            $existing = Seat::where('bus_id', $busId)->count();
            $startRow = intdiv($existing, count($columns)) + 1;

            $remaining = $kapasitas - $existing;
            if ($remaining <= 0) {
                return;
            }

            $rows = [];
            $pos = $existing % count($columns);

            for ($i = 0; $i < $remaining; $i++) {
                $nomor = $startRow.$columns[$pos];
                $posisi = in_array($columns[$pos], ['A', 'B']) ? 'kiri' : 'kanan';
                $rows[] = ['bus_id' => $busId, 'nomor_kursi' => $nomor, 'posisi' => $posisi, 'created_at' => now(), 'updated_at' => now()];

                $pos++;
                if ($pos >= count($columns)) {
                    $pos = 0;
                    $startRow++;
                }
            }

            $created = Seat::insert($rows);
        });

        return $created;
    }

    public function seatsForBus(int $busId)
    {
        return Seat::where('bus_id', $busId)
            ->orderByRaw('CAST(substr(nomor_kursi, 1, length(nomor_kursi)-1) AS UNSIGNED)')
            ->orderBy('nomor_kursi')
            ->get();
    }
}
