<?php

namespace Database\Seeders;

use App\Models\PengajuanIzin;
use App\Models\LogVerifikatorPengajuan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LogVerifikatorPengajuanSeeder extends Seeder
{
    public function run(): void
    {
        $pengajuanList = PengajuanIzin::all();
        $verifikator = User::where('role', 'superadmin')->first();
        foreach ($pengajuanList as $pengajuan) {
            $statusBaru = collect(['disetujui', 'ditolak'])->random();

            LogVerifikatorPengajuan::create([
                'id_pengajuan'     => $pengajuan->id_pengajuan,
                'nama_verifikator' => $verifikator ? $verifikator->name : 'Super Admin',
                'verifikator_id'   => $verifikator ? $verifikator->id : null,
                'status_awal'      => 'menunggu',
                'status_baru'      => $statusBaru,
                'catatan_verifikator'  => $statusBaru === 'disetujui'
                    ? 'Pengajuan telah diverifikasi dan disetujui.'
                    : 'Pengajuan tidak dapat disetujui karena data kurang lengkap.',
                'verified_at'      => Carbon::now(),
            ]);
        }
    }
}
