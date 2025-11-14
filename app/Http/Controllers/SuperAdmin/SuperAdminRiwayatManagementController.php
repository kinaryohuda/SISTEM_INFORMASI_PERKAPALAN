<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;

class SuperAdminRiwayatManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        // Query riwayat + relasi kapal + log verifikator
        $riwayat = PengajuanIzin::with(['kapal', 'logVerifikator'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('kapal', function ($q) use ($search) {
                    $q->where('nama_pemilik', 'LIKE', "%$search%")
                      ->orWhere('nama_kapal', 'LIKE', "%$search%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $title = "SIPK : Riwayat Permohonan Perizinan";

        return view('main.super-admin.riwayat.index-riwayat-super-admin', compact('riwayat', 'title'));
    }
}
