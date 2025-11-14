<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PengajuanIzin;
use App\Models\LogLogin;
use Carbon\Carbon;

class SuperAdminDashboardManagementController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // total pengguna
        $totalAdmin = User::where('role', 'admin')->count();
        $totalUser = User::where('role', 'user')->count();

        // total pengajuan
        $totalPengajuan = PengajuanIzin::count();
        $disetujui = PengajuanIzin::where('status', 'disetujui')->count();
        $ditolak = PengajuanIzin::where('status', 'ditolak')->count();
        $menunggu = PengajuanIzin::where('status', 'menunggu')->count();

        // grafik pengajuan 7 hari terakhir
        $grafikPengajuan = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i)->format('Y-m-d');
            $jumlah = PengajuanIzin::whereDate('created_at', $tanggal)->count();
            $grafikPengajuan[] = [
                'tanggal' => $tanggal,
                'jumlah' => $jumlah
            ];
        }

        // total akses seumur hidup
        $totalAkses = LogLogin::count();

        // akses hari ini saja
        $aksesHariIni = LogLogin::whereDate('created_at', Carbon::today())->count();

        return view('main.super-admin.dashboard', [
            'title' => 'SIPK : Dashboard',
            'user' => $user,
            'totalAdmin' => $totalAdmin,
            'totalUser' => $totalUser,
            'totalPengajuan' => $totalPengajuan,
            'disetujui' => $disetujui,
            'ditolak' => $ditolak,
            'menunggu' => $menunggu,
            'grafikPengajuan' => $grafikPengajuan,
            'totalAkses' => $totalAkses,
            'aksesHariIni' => $aksesHariIni
        ]);
    }
}
