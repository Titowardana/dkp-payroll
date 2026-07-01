<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AppSeeder extends Seeder
{
    public function run()
    {
        $this->call('UserSeeder');
        $this->call('PegawaiSeeder');
        $this->call('PeriodeSeeder');
    }
}
