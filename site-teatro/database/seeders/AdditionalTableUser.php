<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Adm;

class AdmTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('adm')->insert([
            'name' => 'pamela',
            'password' => Hash::make('pm1234'),
        ]);
    }
}
