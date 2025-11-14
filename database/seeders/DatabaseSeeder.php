<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Jalankan seeder user
        $this->call(UserSeeder::class);
        $this->call(KapalSeeder::class);
        $this->call(PengajuanIzinSeeder::class);
        $this->call(KomponenPengajuanSeeder::class);
        $this->call(PengajuanIzinDetailSeeder::class);
        $this->call(LogVerifikatorPengajuanSeeder::class);
    }
}
