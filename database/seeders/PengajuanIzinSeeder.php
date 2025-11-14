<?php

namespace Database\Seeders;

use App\Models\PengajuanIzin;
use App\Models\Kapal;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PengajuanIzinSeeder extends Seeder
{
    public function run(): void
    {
        $kapalList = Kapal::all();

        foreach ($kapalList as $kapal) {
            PengajuanIzin::create([
                'id_kapal' => $kapal->id_kapal, 
                'status' => 'menunggu',
            ]);
        }
    }
}
