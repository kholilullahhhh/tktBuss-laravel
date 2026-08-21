<?php

namespace Database\Seeders;

use App\Models\Operator;
use Illuminate\Database\Seeder;

class OperatorSeeder extends Seeder
{
    public function run(): void
    {
        $operators = [
            ['nama_operator' => 'PO Sinar Jaya', 'kode_operator' => 'OP-001', 'alamat' => 'Jl. Raya Serpong No. 12, Tangerang Selatan', 'telepon' => '021-5551234', 'email' => 'cs@sinarjaya.co.id'],
            ['nama_operator' => 'PO Lorena', 'kode_operator' => 'OP-002', 'alamat' => 'Jl. Raya Jakarta-Bogor Km. 28, Depok', 'telepon' => '021-5555678', 'email' => 'cs@lorena.co.id'],
            ['nama_operator' => 'PO Haryanto', 'kode_operator' => 'OP-003', 'alamat' => 'Jl. Yos Sudarso No. 88, Semarang', 'telepon' => '024-5554321', 'email' => 'cs@haryanto.co.id'],
            ['nama_operator' => 'PO Rosalia Indah', 'kode_operator' => 'OP-004', 'alamat' => 'Jl. Raya Solo-Yogyakarta Km. 8, Klaten', 'telepon' => '0272-555999', 'email' => 'cs@rosalia.co.id'],
            ['nama_operator' => 'PO Gunung Harta', 'kode_operator' => 'OP-005', 'alamat' => 'Jl. Raya Denpasar-Gilimanuk Km. 20, Tabanan', 'telepon' => '0361-555777', 'email' => 'cs@gunungharta.co.id'],
        ];

        foreach ($operators as $op) {
            Operator::updateOrCreate(
                ['kode_operator' => $op['kode_operator']],
                $op + ['status' => true]
            );
        }
    }
}
