<?php

namespace Database\Seeders;

use App\Models\Terminal;
use Illuminate\Database\Seeder;

class TerminalSeeder extends Seeder
{
    public function run(): void
    {
        $terminals = [
            ['nama_terminal' => 'Terminal Pulo Gebang', 'kode_terminal' => 'TRM-JKT1', 'alamat' => 'Jl. Raya Pulo Gebang, Cakung', 'kota' => 'Jakarta Timur', 'provinsi' => 'DKI Jakarta'],
            ['nama_terminal' => 'Terminal Kampung Rambutan', 'kode_terminal' => 'TRM-JKT2', 'alamat' => 'Jl. Bambu Apus, Ciracas', 'kota' => 'Jakarta Timur', 'provinsi' => 'DKI Jakarta'],
            ['nama_terminal' => 'Terminal Kalideres', 'kode_terminal' => 'TRM-JKT3', 'alamat' => 'Jl. Daan Mogot, Kalideres', 'kota' => 'Jakarta Barat', 'provinsi' => 'DKI Jakarta'],
            ['nama_terminal' => 'Terminal Leuwi Panjang', 'kode_terminal' => 'TRM-BDG1', 'alamat' => 'Jl. Soekarno Hatta, Cibangkong', 'kota' => 'Bandung', 'provinsi' => 'Jawa Barat'],
            ['nama_terminal' => 'Terminal Cicaheum', 'kode_terminal' => 'TRM-BDG2', 'alamat' => 'Jl. Ahmad Yani, Cicaheum', 'kota' => 'Bandung', 'provinsi' => 'Jawa Barat'],
            ['nama_terminal' => 'Terminal Tirtonadi', 'kode_terminal' => 'TRM-SLO1', 'alamat' => 'Jl. Ahmad Yani No. 100', 'kota' => 'Surakarta', 'provinsi' => 'Jawa Tengah'],
            ['nama_terminal' => 'Terminal Terboyo', 'kode_terminal' => 'TRM-SMG1', 'alamat' => 'Jl. Raya Kaligawe', 'kota' => 'Semarang', 'provinsi' => 'Jawa Tengah'],
            ['nama_terminal' => 'Terminal Jombor', 'kode_terminal' => 'TRM-JOG1', 'alamat' => 'Jl. Magelang Km. 5, Sinduadi', 'kota' => 'Sleman', 'provinsi' => 'DIY Yogyakarta'],
            ['nama_terminal' => 'Terminal Giwangan', 'kode_terminal' => 'TRM-JOG2', 'alamat' => 'Jl. Imogiri Timur No. 1, Giwangan', 'kota' => 'Yogyakarta', 'provinsi' => 'DIY Yogyakarta'],
            ['nama_terminal' => 'Terminal Bungurasih', 'kode_terminal' => 'TRM-SBY1', 'alamat' => 'Jl. Letjen Sutoyo, Waru', 'kota' => 'Sidoarjo', 'provinsi' => 'Jawa Timur'],
            ['nama_terminal' => 'Terminal Rajabasa', 'kode_terminal' => 'TRM-BDL1', 'alamat' => 'Jl. Laksamana RE Martadinata', 'kota' => 'Bandar Lampung', 'provinsi' => 'Lampung'],
            ['nama_terminal' => 'Terminal Purwokerto', 'kode_terminal' => 'TRM-PWT1', 'alamat' => 'Jl. Gerilya, Purwokerto Barat', 'kota' => 'Banyumas', 'provinsi' => 'Jawa Tengah'],
            ['nama_terminal' => 'Terminal Manggarai', 'kode_terminal' => 'TRM-BJBR1', 'alamat' => 'Jl. Raya Manggarai', 'kota' => 'Balikpapan', 'provinsi' => 'Kalimantan Timur'],
            ['nama_terminal' => 'Terminal Arjosari', 'kode_terminal' => 'TRM-MLG1', 'alamat' => 'Jl. Arjosari, Gadang', 'kota' => 'Malang', 'provinsi' => 'Jawa Timur'],
            ['nama_terminal' => 'Terminal Bayuangga', 'kode_terminal' => 'TRM-PSB1', 'alamat' => 'Jl. Mastrip, Karanganyar', 'kota' => 'Probolinggo', 'provinsi' => 'Jawa Timur'],
            ['nama_terminal' => 'Terminal Pakupatan', 'kode_terminal' => 'TRM-SRG1', 'alamat' => 'Jl. Lingkar Selatan, Pakupatan', 'kota' => 'Serang', 'provinsi' => 'Banten'],
            ['nama_terminal' => 'Terminal Ciledug', 'kode_terminal' => 'TRM-CLD1', 'alamat' => 'Jl. Raya Ciledug', 'kota' => 'Cirebon', 'provinsi' => 'Jawa Barat'],
            ['nama_terminal' => 'Terminal Kertajati', 'kode_terminal' => 'TRM-KTJ1', 'alamat' => 'Jl. Raya Kertajati', 'kota' => 'Majalengka', 'provinsi' => 'Jawa Barat'],
            ['nama_terminal' => 'Terminal Ubung', 'kode_terminal' => 'TRM-DPS1', 'alamat' => 'Jl. Raya Ubung', 'kota' => 'Denpasar', 'provinsi' => 'Bali'],
            ['nama_terminal' => 'Terminal Batoh', 'kode_terminal' => 'TRM-BNA1', 'alamat' => 'Jl. Raya Batoh', 'kota' => 'Banda Aceh', 'provinsi' => 'Aceh'],
        ];

        foreach ($terminals as $term) {
            Terminal::updateOrCreate(
                ['kode_terminal' => $term['kode_terminal']],
                $term + ['status' => true]
            );
        }
    }
}
