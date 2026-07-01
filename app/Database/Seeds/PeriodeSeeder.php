<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('periode_penggajian')->insertBatch([
            [
                'bulan'       => 5,
                'tahun'       => 2026,
                'nama_periode' => 'Mei 2026',
                'tanggal_mulai' => '2026-05-01',
                'tanggal_selesai' => '2026-05-31',
                'status'      => 'draft',
            ],
            [
                'bulan'       => 6,
                'tahun'       => 2026,
                'nama_periode' => 'Juni 2026',
                'tanggal_mulai' => '2026-06-01',
                'tanggal_selesai' => '2026-06-30',
                'status'      => 'draft',
            ],
        ]);
    }
}
