<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
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
    }
}
