<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Okul API'si gelene kadar senin test hesabın
        User::factory()->create([
            'name' => 'Gökhan (Öğrenci)',
            'email' => '252331025@ogrenci.yalova.edu.tr',
            // Şifreni veritabanında kimsenin (senin bile) okuyamayacağı şekilde kriptoluyoruz (Bcrypt)
            'password' => Hash::make('DF1F93bb'), 
        ]);
    }
}