<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Adm;

class AdditionalUsersSeeder extends Seeder
{
    public function run()
    {
        Adm::create([
            'username' => 'pamela',
            'password' => bcrypt('pm1234'), // Criptografa a senha
        ]);
    }
}
