<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Kapal;
use App\Models\PengajuanIzin;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ambil id kapal milik user
        $kapalIds = Kapal::where('user_id', $user->id)->pluck('id_kapal');

        // jumlah kapal milik user
        $jumlahKapal = $kapalIds->count();

        // jumlah pengajuan milik user
        $jumlahPengajuan = PengajuanIzin::whereIn('id_kapal', $kapalIds)->count();

        // jumlah pengajuan disetujui
        $jumlahDisetujui = PengajuanIzin::whereIn('id_kapal', $kapalIds)
                            ->where('status', 'disetujui')
                            ->count();

        // jumlah pengajuan ditolak
        $jumlahDitolak = PengajuanIzin::whereIn('id_kapal', $kapalIds)
                            ->where('status', 'ditolak')
                            ->count();

        return view('main.user.dashboard.index-dashboard-user', [
            'title' => 'SIPK : Dashboard',
            'user' => $user,
            'jumlahKapal' => $jumlahKapal,
            'jumlahPengajuan' => $jumlahPengajuan,
            'jumlahDisetujui' => $jumlahDisetujui,
            'jumlahDitolak' => $jumlahDitolak,
        ]);
    }
}