<?php

namespace Database\Seeders;

use App\Models\PengajuanIzin;
use App\Models\KomponenPengajuan;
use App\Models\PengajuanIzinDetail;
use Illuminate\Database\Seeder;

class PengajuanIzinDetailSeeder extends Seeder
{
    public function run(): void
    {
        $komponenList = KomponenPengajuan::all();
        $pengajuanList = PengajuanIzin::all();

        // Data default untuk tipe "file"
        $defaultFileUrl = "https://res.cloudinary.com/ddrepuzxq/image/upload/v1763136540/pengajuan_izin/Gemini_Generated_Image_j5jizfj5jizfj5ji-removebg-preview-20251114_160854.png";
        $defaultFilePublicId = "pengajuan_izin/default-file";
        $defaultResourceType = "image";
        $defaultFormat = "png";

        foreach ($pengajuanList as $pengajuan) {
            foreach ($komponenList as $komponen) {

                $nilai = match ($komponen->tipe) {
                    'text'   => fake()->sentence(2),
                    'date'   => now()->format('Y-m-d'),
                    'select' => fake()->randomElement(['Barang', 'Penumpang', 'Campuran']),
                    'file'   => json_encode([
                        'url' => $defaultFileUrl,
                        'public_id' => $defaultFilePublicId,
                        'resource_type' => $defaultResourceType,
                        'format' => $defaultFormat,
                    ]),
                    default  => null,
                };

                PengajuanIzinDetail::create([
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'id_komponen_pengajuan' => $komponen->id_komponen_pengajuan,
                    'nilai' => $nilai,
                ]);
            }
        }
    }
}
