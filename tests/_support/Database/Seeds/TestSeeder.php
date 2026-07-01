<?php namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('users')->insertBatch([
            [
                'username'  => 'admin',
                'password'  => password_hash('admin123', PASSWORD_DEFAULT),
                'full_name' => 'Administrator DKP',
                'role'      => 'admin',
                'is_active' => 1,
            ],
            [
                'username'  => 'bendahara',
                'password'  => password_hash('bendahara123', PASSWORD_DEFAULT),
                'full_name' => 'Bendahara DKP',
                'role'      => 'bendahara',
                'is_active' => 1,
            ],
        ]);

        $this->db->table('pegawai')->insert([
            'nip' => '198001012010011001',
            'nama' => 'Test Pegawai',
            'gaji_pokok' => 5000000,
            'is_active' => 1,
        ]);

        $this->db->table('periode_penggajian')->insert([
            'bulan' => 7,
            'tahun' => 2026,
            'nama_periode' => 'Juli 2026',
            'status' => 'draft',
        ]);
    }
}
