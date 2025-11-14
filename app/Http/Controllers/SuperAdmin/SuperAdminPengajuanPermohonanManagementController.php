<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kapal;
use App\Models\KomponenPengajuan;
use App\Models\PengajuanIzin;
use App\Models\PengajuanIzinDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Crypt;

class SuperAdminPengajuanPermohonanManagementController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
    }

    // ==========================================================
    // 1. HALAMAN UTAMA
    // ==========================================================
    public function index()
    {
        $users = User::where('role', 'user')->get();
        $komponen_pengajuan = KomponenPengajuan::where('is_active', true)->get();
        $title = "SIPK : Pengajuan Permohonan (Super Admin)";
        // dd($users);
        return view(
            'main.super-admin.pengajuan-permohonan.index-pengajuan-permohonan-super-admin',
            compact('users', 'komponen_pengajuan', 'title')
        );
    }

    // ==========================================================
    // 2. AJAX LOAD KAPAL
    // ==========================================================
    public function loadKapal($id_user)
    {
        try {
            Log::info("SuperAdmin loadKapal() dipanggil", ['id_user' => $id_user]);

            $kapal = Kapal::where('user_id', $id_user)->get();

            if ($kapal->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User tidak memiliki data kapal',
                    'data' => []
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data kapal berhasil diambil',
                'data' => $kapal
            ]);
        } catch (\Exception $e) {
            Log::error("Error loadKapal(): " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil kapal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


public function getUserData($id)
{
    try {
        $user = User::findOrFail($id);

        try {
            $nik = isset($user->nik) ? Crypt::decryptString($user->nik) : null;
        } catch (\Exception $e) {
            $nik = $user->nik; // fallback, mungkin sudah plaintext
        }

        try {
            $alamat = isset($user->alamat) ? Crypt::decryptString($user->alamat) : null;
        } catch (\Exception $e) {
            $alamat = $user->alamat; // fallback
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $user->name,
                'nik' => $nik,
                'alamat' => $alamat
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Data user tidak ditemukan',
            'error' => $e->getMessage()
        ], 404);
    }
}


    // ==========================================================
    // 3. STORE PENGAJUAN IZIN
    // ==========================================================
   
   public function store(Request $request)
{
    $komponenList = KomponenPengajuan::where('is_active', true)->get();

    // RULE DASAR
    $rules = [
        'id_user' => 'required',
        'id_kapal' => 'required',
        'komponen' => 'required|array',
    ];

    // VALIDASI USER BARU
    if ($request->id_user === 'lainnya') {
        $rules = array_merge($rules, [
            'nama_user' => 'required|string|max:100',
            'nik_user' => 'required|string|unique:users,nik',
            'no_hp_user' => 'required|string|max:20',
            'alamat_user' => 'required|string|max:255',
            'email_user' => 'required|email|unique:users,email',
            'password_user' => 'required|min:6',
        ]);
    }

    // VALIDASI KAPAL BARU
    if ($request->id_kapal === 'lainnya') {
        $rules = array_merge($rules, [
            'nama_pemilik' => 'required|string|max:100',
            'nik' => 'required|string|unique:kapal,nik',
            'alamat' => 'required|string|max:255',
            'nama_kapal' => 'required|string|max:100',
            'tipe_kapal' => 'nullable|string|max:50',
            'kapasitas' => 'nullable|integer|min:1',
            'nomor_registrasi' => 'required|string|unique:kapal,nomor_registrasi',
            'foto_kapal' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'dokumen_kapal' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }

    // VALIDASI KOMPONEN DINAMIS
    foreach ($komponenList as $komponen) {
        $key = "komponen." . $komponen->id_komponen_pengajuan;

        if ($komponen->tipe === 'file') {
            $rules[$key] = ($komponen->is_required ? 'required|' : 'nullable|') .
                'file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx|max:10240';
        } else {
            $rules[$key] = $komponen->is_required ? 'required' : 'nullable';
        }
    }

    // VALIDATOR
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    try {
        $timestamp = date('Ymd_His');

        // SIMPAN USER BARU
        if ($request->id_user === 'lainnya') {
            $user = User::create([
                'name' => $request->nama_user,
                'nik' => $request->nik_user,
                'no_hp' => $request->no_hp_user,
                'alamat' => $request->alamat_user,
                'email' => $request->email_user,
                'password' => Hash::make($request->password_user),
                'role' => 'user',
            ]);
        } else {
            $user = User::findOrFail($request->id_user);
        }

        // SIMPAN KAPAL BARU
        if ($request->id_kapal === 'lainnya') {
            $slug = Str::slug($request->nama_kapal);

            // FOTO
            $fotoUpload = null;
            if ($request->hasFile('foto_kapal')) {
                $fotoUpload = $this->cloudinary->uploadApi()->upload(
                    $request->file('foto_kapal')->getRealPath(),
                    [
                        'folder' => 'kapal/foto',
                        'resource_type' => 'image',
                        'public_id' => "$slug-foto-$timestamp",
                        'format' => strtolower($request->file('foto_kapal')->getClientOriginalExtension()), // Tambahkan format
                        'overwrite' => true,
                    ]
                );
            }

            // DOKUMEN
            $dokumenUpload = null;
            if ($request->hasFile('dokumen_kapal')) {
                $ext = strtolower($request->file('dokumen_kapal')->getClientOriginalExtension());
                $dokumenUpload = $this->cloudinary->uploadApi()->upload(
                    $request->file('dokumen_kapal')->getRealPath(),
                    [
                        'folder' => 'kapal/dokumen',
                        'resource_type' => 'raw',
                        'public_id' => "$slug-doc-$timestamp",
                        'format' => $ext, // Tambahkan format agar PDF/Word tidak jadi temp
                        'overwrite' => true,
                    ]
                );
            }

            $kapal = Kapal::create([
                'user_id' => $user->id,
                'nama_pemilik' => $request->nama_pemilik,
                'nik' => $request->nik,
                'alamat' => $request->alamat,
                'nama_kapal' => $request->nama_kapal,
                'tipe_kapal' => $request->tipe_kapal,
                'kapasitas' => $request->kapasitas,
                'nomor_registrasi' => strtoupper($request->nomor_registrasi),
                'foto_url' => $fotoUpload['secure_url'] ?? null,
                'foto_public_id' => $fotoUpload['public_id'] ?? null,
                'dokumen_url' => $dokumenUpload['secure_url'] ?? null,
                'dokumen_public_id' => $dokumenUpload['public_id'] ?? null,
            ]);
        } else {
            $kapal = Kapal::findOrFail($request->id_kapal);
        }

        // BUAT PENGAJUAN IZIN
        $pengajuan = PengajuanIzin::create([
            'id_kapal' => $kapal->id_kapal,
            'status' => 'menunggu'
        ]);

        // SIMPAN DETAIL KOMPONEN
        foreach ($request->komponen as $idKomponen => $value) {
            $komponen = KomponenPengajuan::find($idKomponen);
            $nilai = $value;

            if ($komponen->tipe === 'file' && $request->hasFile("komponen.$idKomponen")) {
                $file = $request->file("komponen.$idKomponen");
                $ext = strtolower($file->getClientOriginalExtension());

                $upload = $this->cloudinary->uploadApi()->upload(
                    $file->getRealPath(),
                    [
                        'folder' => 'pengajuan_izin',
                        'resource_type' => in_array($ext, ['jpg','jpeg','png','webp','gif']) ? 'image' : 'raw',
                        'public_id' => "komponen-$idKomponen-$timestamp",
                        'format' => $ext, // Fix format agar file PDF/Word tetap utuh
                        'overwrite' => true,
                    ]
                );

                $nilai = json_encode([
                    'url' => $upload['secure_url'],
                    'public_id' => $upload['public_id'],
                    'format' => $ext
                ]);
            }

            PengajuanIzinDetail::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'id_komponen_pengajuan' => $idKomponen,
                'nilai' => $nilai
            ]);
        }

        return back()->with('success', 'Pengajuan berhasil dikirim!');
    } catch (\Exception $e) {
        Log::error("Error Pengajuan Super Admin: " . $e->getMessage());
        return back()->with('error', 'Gagal menyimpan pengajuan: ' . $e->getMessage());
    }
}

}
