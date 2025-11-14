<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kapal;
use App\Models\KomponenPengajuan;
use App\Models\PengajuanIzin;
use App\Models\PengajuanIzinDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Cloudinary\Cloudinary;

class UserPengajuanPermohonan extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
    }

    public function index()
    {
        $kapal = Kapal::where('user_id', Auth::id())->get();
        $komponen_pengajuan = KomponenPengajuan::where('is_active', true)->get();
        $title = "SIPK : Pengajuan Permohonan";

        Log::info("User " . Auth::id() . " membuka halaman pengajuan permohonan.");

        return view('main.user.pengajuan-permohonan.create', compact(
            'kapal',
            'komponen_pengajuan',
            'title'
        ));
    }

    public function store(Request $request)
    {
        // Ambil semua komponen aktif
        $komponenList = KomponenPengajuan::where('is_active', true)->get();

        // =========================
        // VALIDASI DINAMIS
        // =========================
        $rules = [
            'id_kapal' => 'required',
            'komponen' => 'required|array',
        ];

        foreach ($komponenList as $komponen) {
            $key = "komponen." . $komponen->id_komponen_pengajuan;

            if ($komponen->tipe === 'file') {
                $fileRule = $komponen->is_required ? 'required|' : 'nullable|';
                $rules[$key] = $fileRule . 'file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx|max:10240';
            } elseif ($komponen->is_required) {
                $rules[$key] = 'required';
            } else {
                $rules[$key] = 'nullable';
            }
        }

        // Validasi tambahan untuk kapal baru
        if ($request->id_kapal === 'lainnya') {
            $rules = array_merge($rules, [
                'nama_pemilik' => 'required|string|max:100',
                'nik' => 'required|string|unique:kapal,nik',
                'alamat' => 'nullable|string|max:255',
                'nama_kapal' => 'required|string|max:100',
                'tipe_kapal' => 'nullable|string|max:50',
                'kapasitas' => 'nullable|integer|min:1',
                'nomor_registrasi' => 'required|string|unique:kapal,nomor_registrasi',
                'foto_kapal' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
                'dokumen_kapal' => 'required|file|mimes:pdf|max:10240',
            ]);
        }

        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'string' => 'Kolom :attribute harus berupa teks.',
            'integer' => 'Kolom :attribute harus berupa angka.',
            'unique' => ':attribute sudah terdaftar.',
            'file' => 'Kolom :attribute harus berupa file.',
            'image' => 'Kolom :attribute harus berupa gambar.',
            'mimes' => 'File :attribute hanya boleh berformat: :values.',
            'max' => 'Ukuran file :attribute maksimal :max KB.',
            'min' => 'Kolom :attribute minimal :min.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            Log::warning("Validasi gagal oleh User " . Auth::id() . ": " . json_encode($validator->errors()->toArray()));
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {

            // ======================================================
            // INISIALISASI CLOUDINARY & TIMESTAMP SEKALI SAJA (FIX)
            // ======================================================
            $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
            $timestamp = date('Ymd_His');

            // ======================================================
            // SIMPAN KAPAL BARU
            // ======================================================
            if ($request->id_kapal === 'lainnya') {

                $namaKapalSlug = Str::slug($request->nama_kapal, '-');

                // Upload Foto Kapal
                $fotoUpload = null;
                if ($request->hasFile('foto_kapal')) {
                    $foto = $request->file('foto_kapal');
                    $fotoName = "{$namaKapalSlug}-foto-{$timestamp}";

                    $fotoUpload = $cloudinary->uploadApi()->upload(
                        $foto->getRealPath(),
                        [
                            'folder' => 'kapal/foto',
                            'resource_type' => 'image',
                            'public_id' => $fotoName,
                            'format' => strtolower($foto->getClientOriginalExtension()),
                            'overwrite' => true,
                            'use_filename' => true,
                        ]
                    );
                }

                // Upload Dokumen Kapal
                $dokumenUpload = null;
                if ($request->hasFile('dokumen_kapal')) {
                    $dokumen = $request->file('dokumen_kapal');
                    $dokumenName = "{$namaKapalSlug}-dokumen-{$timestamp}";

                    $dokumenUpload = $cloudinary->uploadApi()->upload(
                        $dokumen->getRealPath(),
                        [
                            'folder' => 'kapal/dokumen',
                            'resource_type' => 'raw',
                            'public_id' => $dokumenName,
                            'format' => 'pdf',
                            'overwrite' => true,
                        ]
                    );
                }

                // Simpan kapal baru ke database
                $kapal = Kapal::create([
                    'user_id' => Auth::id(),
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

                Log::info("User " . Auth::id() . " menambahkan kapal baru: " . $kapal->nama_kapal);

            } else {
                $kapal = Kapal::findOrFail($request->id_kapal);
            }

            // ======================================================
            // SIMPAN PENGAJUAN IZIN
            // ======================================================
            $pengajuan = PengajuanIzin::create([
                'id_kapal' => $kapal->id_kapal,
                'status' => 'menunggu',
            ]);

            Log::info("User " . Auth::id() . " membuat pengajuan izin ID: " . $pengajuan->id_pengajuan);

            // ======================================================
            // SIMPAN DETAIL KOMPOSISI / KOMPONEN
            // ======================================================
            foreach ($request->komponen as $idKomponen => $value) {
                $komponen = KomponenPengajuan::findOrFail($idKomponen);
                $nilai = $value;

                if ($komponen->tipe === 'file' && $request->hasFile("komponen.$idKomponen")) {

                    $file = $request->file("komponen.$idKomponen");
                    $extension = strtolower($file->getClientOriginalExtension());
                    $resourceType = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? 'image' : 'raw';
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                    $uploaded = $cloudinary->uploadApi()->upload(
                        $file->getRealPath(),
                        [
                            'folder' => 'pengajuan_izin',
                            'resource_type' => $resourceType,
                            'public_id' => $originalName . '-' . $timestamp,
                            'format' => $extension,
                            'overwrite' => true,
                        ]
                    );

                    $nilai = json_encode([
                        'url' => $uploaded['secure_url'],
                        'public_id' => $uploaded['public_id'],
                        'resource_type' => $resourceType,
                        'format' => $extension,
                    ]);

                    Log::info("User " . Auth::id() . " meng-upload file untuk komponen '{$komponen->nama_komponen}' => {$uploaded['secure_url']}");
                }

                PengajuanIzinDetail::create([
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'id_komponen_pengajuan' => $idKomponen,
                    'nilai' => $nilai,
                ]);
            }

            Log::info("User " . Auth::id() . " berhasil menyimpan pengajuan ID: " . $pengajuan->id_pengajuan);

            return redirect()->back()->with('success', 'Pengajuan berhasil dikirim!');

        } catch (\Exception $e) {

            Log::error("Gagal menyimpan pengajuan oleh User " . Auth::id() . ": " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan pengajuan: ' . $e->getMessage());
        }
    }



    public function destroyDetail(PengajuanIzinDetail $detail)
    {
        $nilai = json_decode($detail->nilai, true);

        try {
            if (!empty($nilai['public_id'])) {
                $this->cloudinary->uploadApi()->destroy($nilai['public_id']);
                Log::info("User " . Auth::id() . " menghapus file Cloudinary public_id: " . $nilai['public_id']);
            }

            $detail->delete();
            Log::info("User " . Auth::id() . " menghapus detail pengajuan ID: " . $detail->id_pengajuan_detail);

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error("Gagal menghapus detail pengajuan ID " . $detail->id_pengajuan_detail . ": " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
