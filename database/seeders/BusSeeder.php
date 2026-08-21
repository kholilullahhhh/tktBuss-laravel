<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Operator;
use App\Models\Seat;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        $buses = [
            ['nomor_polisi' => 'B 7012 SJ', 'kode_bus' => 'BUS-0001', 'nama_bus' => 'Sinar Jaya Executive', 'kelas' => 'Executive', 'kapasitas' => 32, 'fasilitas' => 'AC, Reclining Seat, Toilet, USB Charger, Wifi'],
            ['nomor_polisi' => 'B 9087 SJ', 'kode_bus' => 'BUS-0002', 'nama_bus' => 'Sinar Jaya Sleeper', 'kelas' => 'Sleeper', 'kapasitas' => 28, 'fasilitas' => 'AC, Sleepr Class, Toilet, USB Charger, Wifi'],
            ['nomor_polisi' => 'B 5534 LR', 'kode_bus' => 'BUS-0003', 'nama_bus' => 'Lorena Bisnis', 'kelas' => 'Bisnis', 'kapasitas' => 44, 'fasilitas' => 'AC, Reclining Seat, Toilet, USB Charger'],
            ['nomor_polisi' => 'H 1188 HR', 'kode_bus' => 'BUS-0004', 'nama_bus' => 'Haryanto Executive', 'kelas' => 'Executive', 'kapasitas' => 32, 'fasilitas' => 'AC, Reclining Seat, Toilet, USB Charger, Wifi'],
            ['nomor_polisi' => 'H 2233 HR', 'kode_bus' => 'BUS-0005', 'nama_bus' => 'Haryanto Bisnis', 'kelas' => 'Bisnis', 'kapasitas' => 44, 'fasilitas' => 'AC, Reclining Seat, USB Charger'],
            ['nomor_polisi' => 'AD 4421 RI', 'kode_bus' => 'BUS-0006', 'nama_bus' => 'Rosalia Indah Executive', 'kelas' => 'Executive', 'kapasitas' => 36, 'fasilitas' => 'AC, Reclining Seat, Toilet, USB Charger, Wifi'],
            ['nomor_polisi' => 'AD 6678 RI', 'kode_bus' => 'BUS-0007', 'nama_bus' => 'Rosalia Indah Ekonomi', 'kelas' => 'Ekonomi', 'kapasitas' => 52, 'fasilitas' => 'AC, Fan, USB Charger'],
            ['nomor_polisi' => 'DK 7733 GH', 'kode_bus' => 'BUS-0008', 'nama_bus' => 'Gunung Harta Executive', 'kelas' => 'Executive', 'kapasitas' => 32, 'fasilitas' => 'AC, Reclining Seat, Toilet, USB Charger, Wifi'],
            ['nomor_polisi' => 'L 9901 SJ', 'kode_bus' => 'BUS-0009', 'nama_bus' => 'Sinar Jaya Bisnis', 'kelas' => 'Bisnis', 'kapasitas' => 44, 'fasilitas' => 'AC, Reclining Seat, USB Charger'],
            ['nomor_polisi' => 'B 3400 LR', 'kode_bus' => 'BUS-0010', 'nama_bus' => 'Lorena Executive', 'kelas' => 'Executive', 'kapasitas' => 36, 'fasilitas' => 'AC, Reclining Seat, Toilet, USB Charger, Wifi'],
        ];

        $operators = Operator::orderBy('id')->get();

        foreach ($buses as $i => $bus) {
            $busData = Bus::updateOrCreate(
                ['kode_bus' => $bus['kode_bus']],
                [
                    'operator_id' => $operators[$i % $operators->count()]->id,
                    'nomor_polisi' => $bus['nomor_polisi'],
                    'nama_bus' => $bus['nama_bus'],
                    'kelas' => $bus['kelas'],
                    'kapasitas' => $bus['kapasitas'],
                    'fasilitas' => $bus['fasilitas'],
                    'status' => true,
                ]
            );

            // Generate kursi secara otomatis (4 kolom per baris: A/B kiri, C/D kanan)
            if (Seat::where('bus_id', $busData->id)->count() === 0) {
                $rows = (int) ceil($bus['kapasitas'] / 4);
                $count = 0;
                foreach (range(1, $rows) as $row) {
                    foreach (['A', 'B', 'C', 'D'] as $pos) {
                        if ($count >= $bus['kapasitas']) {
                            break 2;
                        }
                        Seat::create([
                            'bus_id' => $busData->id,
                            'nomor_kursi' => $row.$pos,
                            'posisi' => in_array($pos, ['A', 'B']) ? 'kiri' : 'kanan',
                            'status' => 'aktif',
                        ]);
                        $count++;
                    }
                }
            }
        }
    }
}
