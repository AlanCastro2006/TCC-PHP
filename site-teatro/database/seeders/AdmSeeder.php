<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Adm;

class AdmSeeder extends Seeder
{
    public function run()
    {
        Adm::create([
            'username' => 'robson',
            'password' => bcrypt('rb1234'), // Criptografa a senha
        ]);

    }
}
