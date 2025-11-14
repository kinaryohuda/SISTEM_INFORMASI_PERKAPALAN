<?php

namespace Database\Seeders;

use App\Models\Kapal;
use App\Models\User;
use Illuminate\Database\Seeder;

class KapalSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user dengan role 'user'
        $users = User::where('role', 'user')->get();

        if ($users->isEmpty()) {
            $this->command->info('⚠️ Tidak ada user dengan role "user". Seeder Kapal dilewati.');
            return;
        }

        // Default Cloudinary URLs
        $defaultFotoUrl = "https://res.cloudinary.com/ddrepuzxq/image/upload/v1763136540/pengajuan_izin/Gemini_Generated_Image_j5jizfj5jizfj5ji-removebg-preview-20251114_160854.png";
        $defaultDokumenUrl = "https://res.cloudinary.com/ddrepuzxq/raw/upload/v1763136543/pengajuan_izin/Test%202-20251114_160854.pdf";

        foreach ($users as $user) {
            // Buat 2 kapal per user
            for ($i = 1; $i <= 2; $i++) {
                Kapal::create([
                    'user_id' => $user->id,
                    'nama_pemilik' => $user->name,
                    'nik' => $user->nik ?? fake()->unique()->numerify('################'),
                    'alamat' => $user->alamat ?? fake()->address(),
                    'nama_kapal' => "Kapal {$user->name} #$i",
                    'tipe_kapal' => fake()->randomElement(['Tipe A', 'Tipe B', 'Tipe C']),
                    'kapasitas' => rand(50, 200),
                    'nomor_registrasi' => strtoupper("REG{$user->id}{$i}"),

                    // Foto kapal memakai Cloudinary PNG default
                    'foto_public_id' => "kapal/default_foto",
                    'foto_url' => $defaultFotoUrl,

                    // Dokumen kapal memakai PDF Cloudinary default
                    'dokumen_public_id' => "kapal/default_dokumen",
                    'dokumen_url' => $defaultDokumenUrl,
                ]);
            }
        }
    }
}
