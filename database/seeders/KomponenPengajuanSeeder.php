<?php

namespace Database\Seeders;

use App\Models\KomponenPengajuan;
use Illuminate\Database\Seeder;

class KomponenPengajuanSeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            [
                'nama_komponen' => 'Pelabuhan Tujuan',
                'tipe' => 'text',
                'is_required' => true,
                'is_active' => true,
            ],
            [
                'nama_komponen' => 'Tanggal Keberangkatan',
                'tipe' => 'date',
                'is_required' => true,
                'is_active' => true,
            ],
            [
                'nama_komponen' => 'Jenis Muatan',
                'tipe' => 'select',
                'is_required' => false,
                'is_active' => true,
                'opsi' =>['Barang', 'Penumpang', 'Campuran'],
            ],
            [
                'nama_komponen' => 'Foto Kapal Terbaru',
                'tipe' => 'file',
                'is_required' => true,
                'is_active' => true,
            ],
            [
                'nama_komponen' => 'Dokumen Izin Lama (Opsional)',
                'tipe' => 'file',
                'is_required' => false,
                'is_active' => true,
            ],
        ];

        foreach ($fields as $f) {
            KomponenPengajuan::create($f);
        }
    }
}
