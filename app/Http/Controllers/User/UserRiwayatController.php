<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PengajuanIzin;
use Illuminate\Support\Facades\Auth;

class UserRiwayatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua pengajuan milik user berdasarkan kapal mereka
        $riwayat = PengajuanIzin::with(['kapal', 'logVerifikator'])
            ->whereHas('kapal', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('main.user.riwayat.index-riwayat-user', compact('riwayat'));
    }
}