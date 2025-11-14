<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kapal;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperAdminUserManagementController extends Controller
{
    public function index()
    {
        // Ambil semua user dengan role user
        $users = User::where('role', 'user')->paginate(10);

        // Hitung jumlah kapal tiap user
        foreach ($users as $user) {
            $user->jumlah_kapal = Kapal::where('user_id', $user->id)->count();

            // Hitung jumlah pengajuan terkait kapal user
            $user->jumlah_perizinan = PengajuanIzin::whereIn(
                'id_kapal',
                Kapal::where('user_id', $user->id)->pluck('id_kapal')
            )->count();

            // Hitung jumlah pengajuan yang disetujui
            $user->jumlah_disetujui = PengajuanIzin::whereIn(
                'id_kapal',
                Kapal::where('user_id', $user->id)->pluck('id_kapal')
            )
                ->where('status', 'disetujui')
                ->count();
        }

        return view(
            'main.super-admin.pengguna.user.index-pengguna-user',
            compact(
                'users'
            )
        );
    }
    public function show($id)
{
    // Ambil user sesuai role
    $user = User::where('role', 'user')->findOrFail($id);

    // Ambil semua kapal user
    $kapal = Kapal::where('user_id', $user->id)->get();

    // Hitung jumlah kapal
    $user->jumlah_kapal = $kapal->count();

    // Ambil semua ID kapal untuk filter pengajuan
    $id_kapal = $kapal->pluck('id_kapal');

    // Ambil riwayat pengajuan izinnya
    $pengajuan = PengajuanIzin::whereIn('id_kapal', $id_kapal)
                    ->orderBy('created_at', 'DESC')
                    ->get();

    // Hitung jumlah total pengajuan & yang disetujui
    $user->jumlah_perizinan = $pengajuan->count();
    $user->jumlah_disetujui = $pengajuan->where('status', 'disetujui')->count();

    return view(
        'main.super-admin.pengguna.user.show-pengguna-user',
        compact('user', 'kapal', 'pengajuan')
    );
}


    public function create()
    {
        return view('main.super-admin.pengguna.user.create-pengguna-user');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'nik'      => 'required|string|max:30|unique:users,nik',
            'no_hp'    => 'required|string|max:20',
            'alamat'   => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name'     => $validated['name'],
            'nik'      => $validated['nik'],        // terenkripsi otomatis
            'no_hp'    => $validated['no_hp'],
            'alamat'   => $validated['alamat'],     // terenkripsi otomatis
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'user'
        ]);

        return redirect()->route('superAdmin.pengguna-user-index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        return view('main.super-admin.pengguna.user.edit-pengguna-user',
         compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required|string|max:100',
            'nik'    => [
                'required',
                Rule::unique('users')->ignore($user->id)
            ],
            'no_hp'  => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'email'  => [
                'required',
                Rule::unique('users')->ignore($user->id)
            ],
        ]);

        // Tambahkan password jika ingin update
        if ($request->password) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('superAdmin.pengguna-user-index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}
