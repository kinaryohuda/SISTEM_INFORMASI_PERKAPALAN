<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kapal;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserKapalController extends Controller
{
    /**
     *Sanitasi input untuk mencegah XSS dan tag HTML berbahaya.
     */
    private function sanitizeInput($value)
    {
        if (is_array($value)) {
            return array_map(fn($v) => $this->sanitizeInput($v), $value);
        }

        $value = strip_tags($value);
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        return trim($value);
    }

    /**
     *Tampilkan semua kapal milik user login.
     */
    public function myKapal()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        if (!$user || !$user->isUser()) {
            abort(403, 'Anda tidak memiliki akses ke data kapal.');
        }
        $kapals = Kapal::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $title = 'SIPK : Data Kapal';
        return view(
            'main.user.kapal.index-kapal-user',
            compact(
                'kapals',
                'title'
            )
        );
    }


    /**
     *Detail kapal berdasarkan id_kapal (hanya milik user).
     */
    public function viewDetail($id_kapal)
    {
        try {
            $user = Auth::user();

            $kapal = Kapal::where('id_kapal', $id_kapal)
                ->where('user_id', $user->id)
                ->first();

            if (!$kapal) {
                Log::warning('Akses tidak sah ke detail kapal', [
                    'user_id' => $user->id ?? 'guest',
                    'id_kapal' => $id_kapal,
                ]);

                return request()->expectsJson()
                    ? response()->json(['success' => false, 'message' => 'Data kapal tidak ditemukan atau Anda tidak memiliki akses.'], 404)
                    : abort(404, 'Data kapal tidak ditemukan atau Anda tidak memiliki akses.');
            }

            foreach (['nama_pemilik', 'nik', 'alamat', 'nama_kapal', 'tipe_kapal', 'kapasitas', 'nomor_registrasi'] as $field) {
                $kapal->{$field} = $this->sanitizeInput($kapal->{$field});
            }

            // Ambil riwayat pengajuan izin kapal
            $riwayat = PengajuanIzin::with(['details.komponen'])
                ->where('id_kapal', $kapal->id_kapal)
                ->orderBy('created_at', 'desc')
                ->get();

            // Format file dari JSON
            $riwayat->each(function ($pengajuan) {
                $pengajuan->details->each(function ($detail) {
                    if ($detail->nilai) {
                        $decoded = json_decode($detail->nilai, true);
                        if (is_array($decoded) && isset($decoded['url'])) {
                            $detail->file_url = $decoded['url'];
                            $detail->file_type = $decoded['resource_type'] ?? 'raw';
                            $detail->file_format = $decoded['format'] ?? '';
                        } else {
                            $detail->file_url = null;
                        }
                    }
                });
            });

            Log::info('Detail kapal & riwayat pengajuan berhasil ditampilkan', [
                'user_id' => $user->id,
                'id_kapal' => $kapal->id_kapal,
                'jumlah_pengajuan' => $riwayat->count(),
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'kapal' => $kapal,
                        'riwayat_pengajuan' => $riwayat,
                    ],
                ]);
            }

            return view('main.user.kapal.detail-kapal-user', [
                'title' => 'SIPK : Data Detail Kapal',
                'kapal' => $kapal,
                'riwayat_pengajuan' => $riwayat,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal memuat detail kapal dan riwayat pengajuan', [
                'user_id' => Auth::id(),
                'id_kapal' => $id_kapal,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memuat detail kapal.'], 500)
                : back()->with('error', 'Terjadi kesalahan saat memuat detail kapal.');
        }
    }

    /**
     *Tampilkan semua kapal (khusus admin).
     */
    public function index()
    {
        $kapals = Kapal::with('user')->get();
        return view('kapal.index', compact('kapals'));
    }

    /**
     *Form tambah kapal baru.
     */
    public function create()
    {
        $title = 'Tambah Kapal';
        return view(
            'main.user.kapal.create-kapal-user',
            compact(
                'title'
            )
        );
    }

    /**
     *Simpan kapal baru.
     */
    public function store(Request $request)
    {
        // ============================================================
        // VALIDASI INPUT DENGAN PESAN CUSTOM BAHASA INDONESIA
        // ============================================================
        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:100',
            'nik' => 'required|string|unique:kapal,nik',
            'alamat' => 'nullable|string|max:255',
            'nama_kapal' => 'required|string|max:100',
            'tipe_kapal' => 'nullable|string|max:50',
            'kapasitas' => 'nullable|integer|min:1',
            'nomor_registrasi' => 'required|string|unique:kapal,nomor_registrasi',
            'foto_kapal' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'dokumen_kapal' => 'required|file|mimes:pdf|max:10240',
        ], [
            'nama_pemilik.required' => 'Nama pemilik wajib diisi.',
            'nama_pemilik.string' => 'Nama pemilik harus berupa teks.',
            'nama_pemilik.max' => 'Nama pemilik maksimal 100 karakter.',

            'nik.required' => 'NIK wajib diisi.',
            'nik.string' => 'NIK harus berupa teks.',
            'nik.unique' => 'NIK ini sudah terdaftar.',

            'alamat.string' => 'Alamat harus berupa teks.',
            'alamat.max' => 'Alamat maksimal 255 karakter.',

            'nama_kapal.required' => 'Nama kapal wajib diisi.',
            'nama_kapal.string' => 'Nama kapal harus berupa teks.',
            'nama_kapal.max' => 'Nama kapal maksimal 100 karakter.',

            'tipe_kapal.string' => 'Tipe kapal harus berupa teks.',
            'tipe_kapal.max' => 'Tipe kapal maksimal 50 karakter.',

            'kapasitas.integer' => 'Kapasitas harus berupa angka.',
            'kapasitas.min' => 'Kapasitas minimal 1.',

            'nomor_registrasi.required' => 'Nomor registrasi wajib diisi.',
            'nomor_registrasi.string' => 'Nomor registrasi harus berupa teks.',
            'nomor_registrasi.unique' => 'Nomor registrasi ini sudah terdaftar.',

            'foto_kapal.required' => 'Foto kapal wajib diunggah.',
            'foto_kapal.image' => 'File yang diunggah harus berupa gambar.',
            'foto_kapal.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto_kapal.max' => 'Ukuran foto maksimal 5 MB.',

            'dokumen_kapal.required' => 'Dokumen kapal wajib diunggah.',
            'dokumen_kapal.file' => 'Dokumen harus berupa file.',
            'dokumen_kapal.mimes' => 'Format dokumen harus PDF.',
            'dokumen_kapal.max' => 'Ukuran dokumen maksimal 10 MB.',
        ]);

        // ============================================================
        // SANITASI & UPLOAD KE CLOUDINARY
        // ============================================================
        $sanitized = $this->sanitizeInput($validated);
        $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));

        try {
            $namaKapalSlug = Str::slug($sanitized['nama_kapal'], '-');
            $timestamp = date('Ymd_His');

            // ============================================================
            // UPLOAD FOTO KAPAL
            // ============================================================
            $fotoUpload = null;
            if ($request->hasFile('foto_kapal')) {
                $foto = $request->file('foto_kapal');
                $fotoOriginal = pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME);
                $fotoExt = strtolower($foto->getClientOriginalExtension());
                $fotoName = "{$namaKapalSlug}-{$fotoOriginal}-{$timestamp}";

                $fotoUpload = $cloudinary->uploadApi()->upload(
                    $foto->getRealPath(),
                    [
                        'folder' => 'kapal/foto',
                        'resource_type' => 'image',
                        'public_id' => $fotoName,
                        'format' => $fotoExt,
                        'use_filename' => true,
                        'unique_filename' => false,
                        'overwrite' => true,
                    ]
                );
            }

            // ============================================================
            // UPLOAD PDF (RAW) — FIX AGAR TIDAK .tmp
            // ============================================================
            $dokumenUpload = null;
            if ($request->hasFile('dokumen_kapal')) {
                $dokumen = $request->file('dokumen_kapal');
                $docOriginal = pathinfo($dokumen->getClientOriginalName(), PATHINFO_FILENAME);
                $dokumenPublicId = "{$namaKapalSlug}-{$docOriginal}-{$timestamp}";

                $dokumenUpload = $cloudinary->uploadApi()->upload(
                    $dokumen->getRealPath(),
                    [
                        'folder' => 'kapal/dokumen',
                        'resource_type' => 'raw',
                        'format' => 'pdf',
                        'public_id' => $dokumenPublicId,
                        'overwrite' => true,
                    ]
                );

                Log::info('PDF uploaded successfully', [
                    'secure_url' => $dokumenUpload['secure_url'] ?? null,
                    'public_id' => $dokumenUpload['public_id'] ?? null
                ]);
            }

            // ============================================================
            // SIMPAN DATA KAPAL
            // ============================================================
            $kapal = Kapal::create([
                'user_id' => Auth::id(),
                'nama_pemilik' => $sanitized['nama_pemilik'],
                'nik' => $sanitized['nik'],
                'alamat' => $sanitized['alamat'] ?? null,
                'nama_kapal' => $sanitized['nama_kapal'],
                'tipe_kapal' => $sanitized['tipe_kapal'] ?? null,
                'kapasitas' => $sanitized['kapasitas'] ?? null,
                'nomor_registrasi' => strtoupper($sanitized['nomor_registrasi']),
                'foto_public_id' => $fotoUpload['public_id'] ?? null,
                'foto_url' => $fotoUpload['secure_url'] ?? null,
                'dokumen_public_id' => $dokumenUpload['public_id'] ?? null,
                'dokumen_url' => $dokumenUpload['secure_url'] ?? null,
            ]);

            return $request->expectsJson()
                ? response()->json([
                    'success' => true,
                    'message' => 'Data kapal berhasil ditambahkan.',
                    'id_kapal' => $kapal->id_kapal
                ])
                : redirect()->route('user.kapal-viewDetail', $kapal->id_kapal)
                    ->with('success', 'Data kapal berhasil ditambahkan.');

        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan kapal.', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan data kapal.'], 500)
                : back()->with('error', 'Terjadi kesalahan saat menyimpan data kapal.');
        }
    }


    /**
     *Form edit kapal berdasarkan id_kapal.
     */
    public function edit($id_kapal)
    {
        try {
            $user = Auth::user();

            /** @var \App\Models\User $user */
            if (!$user || !$user->isUser()) {
                abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
            }

            $kapal = Kapal::where('id_kapal', $id_kapal)
                ->where('user_id', $user->id)
                ->first();

            if (!$kapal) {
                Log::warning('Akses tidak sah ke halaman edit kapal', [
                    'user_id' => $user->id ?? 'guest',
                    'id_kapal' => $id_kapal,
                ]);

                return request()->expectsJson()
                    ? response()->json(['success' => false, 'message' => 'Data kapal tidak ditemukan atau Anda tidak memiliki akses.'], 404)
                    : abort(404, 'Data kapal tidak ditemukan atau Anda tidak memiliki akses.');
            }

            foreach (['nama_pemilik', 'nik', 'alamat', 'nama_kapal', 'tipe_kapal', 'kapasitas', 'nomor_registrasi'] as $field) {
                $kapal->{$field} = $this->sanitizeInput($kapal->{$field});
            }

            Log::info('Halaman edit kapal berhasil diakses.', [
                'user_id' => $user->id,
                'id_kapal' => $kapal->id_kapal,
            ]);

            return request()->expectsJson()
                ? response()->json(['success' => true, 'data' => $kapal])
                : view(
                    'main.user.kapal.edit-kapal-user',
                    [
                        'title' => 'SIPK : Edit Data Kapal',
                        'kapal' => $kapal
                    ]
                );
        } catch (\Throwable $e) {
            Log::error('Gagal memuat halaman edit kapal.', [
                'user_id' => Auth::id(),
                'id_kapal' => $id_kapal,
                'error' => $e->getMessage(),
            ]);

            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memuat data kapal.'], 500)
                : back()->with('error', 'Terjadi kesalahan saat memuat data kapal.');
        }
    }
    /**
     *Form update kapal.
     */
    public function update(Request $request, $id_kapal)
    {
        $kapal = Kapal::findOrFail($id_kapal);

        $validated = $request->validate([
            'nama_pemilik' => ['required', 'string', 'max:100'],
            'nik' => [
                'required',
                'string',
                Rule::unique('kapal', 'nik')->ignore($kapal->id_kapal, 'id_kapal'),
            ],
            'alamat' => ['nullable', 'string', 'max:255'],
            'nama_kapal' => ['required', 'string', 'max:100'],
            'tipe_kapal' => ['nullable', 'string', 'max:50'],
            'kapasitas' => ['nullable', 'integer', 'min:1'],
            'nomor_registrasi' => [
                'required',
                'string',
                Rule::unique('kapal', 'nomor_registrasi')->ignore($kapal->id_kapal, 'id_kapal'),
            ],
            'foto_kapal' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'dokumen_kapal' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ], [
            'nama_pemilik.required' => 'Nama pemilik wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK sudah digunakan oleh kapal lain.',
            'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter.',
            'nama_kapal.required' => 'Nama kapal wajib diisi.',
            'nomor_registrasi.required' => 'Nomor registrasi wajib diisi.',
            'nomor_registrasi.unique' => 'Nomor registrasi sudah digunakan oleh kapal lain.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka.',
            'kapasitas.min' => 'Kapasitas minimal adalah 1.',
            'dokumen_kapal.mimes' => 'Dokumen kapal harus berupa file PDF.',
        ]);

        // Sanitasi input
        $sanitized = array_map(fn($v) => is_string($v) ? trim(strip_tags($v)) : $v, $validated);
        $sanitized['nomor_registrasi'] = strtoupper($sanitized['nomor_registrasi']);

        $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
        $namaKapalSlug = Str::slug($sanitized['nama_kapal'], '-');
        $timestamp = date('Ymd_His');

        try {
            // === UPDATE FOTO KAPAL ===
            if ($request->hasFile('foto_kapal')) {

                if ($kapal->foto_public_id) {
                    $cloudinary->uploadApi()->destroy($kapal->foto_public_id, ['invalidate' => true]);
                }

                $foto = $request->file('foto_kapal');
                $fotoOriginal = pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME);
                $fotoExt = strtolower($foto->getClientOriginalExtension());
                $fotoName = "{$namaKapalSlug}-{$fotoOriginal}-{$timestamp}";

                $fotoUpload = $cloudinary->uploadApi()->upload(
                    $foto->getRealPath(),
                    [
                        'folder' => 'kapal/foto',
                        'resource_type' => 'image',
                        'public_id' => $fotoName,
                        'format' => $fotoExt,
                        'use_filename' => true,
                        'unique_filename' => false,
                        'overwrite' => true,
                    ]
                );

                $kapal->foto_public_id = $fotoUpload['public_id'];
                $kapal->foto_url = $fotoUpload['secure_url'];
            }

            // === UPDATE DOKUMEN KAPAL (PDF ONLY) ===
            if ($request->hasFile('dokumen_kapal')) {

                if ($kapal->dokumen_public_id) {
                    $cloudinary->uploadApi()->destroy($kapal->dokumen_public_id, ['invalidate' => true]);
                }

                $dokumen = $request->file('dokumen_kapal');
                $docOriginal = pathinfo($dokumen->getClientOriginalName(), PATHINFO_FILENAME);
                $dokumenName = "{$namaKapalSlug}-{$docOriginal}-{$timestamp}";

                // Karena hanya PDF → resource_type RAW
                $dokumenUpload = $cloudinary->uploadApi()->upload(
                    $dokumen->getRealPath(),
                    [
                        'folder' => 'kapal/dokumen',
                        'resource_type' => 'raw',
                        'public_id' => $dokumenName,
                        'format' => 'pdf',
                        'use_filename' => true,
                        'unique_filename' => false,
                        'overwrite' => true,
                    ]
                );

                $kapal->dokumen_public_id = $dokumenUpload['public_id'];
                $kapal->dokumen_url = $dokumenUpload['secure_url'];
            }

            // === UPDATE DATA UTAMA ===
            $kapal->update([
                'nama_pemilik' => $sanitized['nama_pemilik'],
                'nik' => $sanitized['nik'],
                'alamat' => $sanitized['alamat'] ?? null,
                'nama_kapal' => $sanitized['nama_kapal'],
                'tipe_kapal' => $sanitized['tipe_kapal'] ?? null,
                'kapasitas' => $sanitized['kapasitas'] ?? null,
                'nomor_registrasi' => $sanitized['nomor_registrasi'],
            ]);

            Log::info('Data kapal berhasil diperbarui.', [
                'id_kapal' => $kapal->id_kapal,
                'user_id' => Auth::id(),
            ]);

            return $request->expectsJson()
                ? response()->json([
                    'success' => true,
                    'message' => 'Data kapal berhasil diperbarui.',
                    'redirect' => route('user.kapal-viewDetail', $kapal->id_kapal),
                ])
                : redirect()->route('user.kapal-viewDetail', $kapal->id_kapal)
                    ->with('success', 'Data kapal berhasil diperbarui.');

        } catch (\Throwable $e) {

            Log::error('Gagal update kapal.', [
                'id_kapal' => $kapal->id_kapal,
                'error' => $e->getMessage(),
            ]);

            return $request->expectsJson()
                ? response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data kapal.'
                ], 500)
                : back()->with('error', 'Terjadi kesalahan saat memperbarui data kapal.');
        }
    }


    /**
     * Hapus kapal berdasarkan id_kapal.
     */
    public function destroy(Request $request, Kapal $kapal)
    {
        try {

            // 1. Pastikan user hanya bisa hapus kapal miliknya
            if ($kapal->user_id !== Auth::id()) {
                return $request->expectsJson()
                    ? response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki izin untuk menghapus kapal ini.'
                    ], 403)
                    : back()->with('error', 'Anda tidak memiliki izin untuk menghapus kapal ini.');
            }

            // 2. Hapus file foto & dokumen dari Cloudinary
            $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));

            /* ==========================================================
               HAPUS FOTO KAPAL DARI CLOUDINARY
            ===========================================================*/
            if ($kapal->foto_public_id) {
                try {
                    $cloudinary->uploadApi()->destroy($kapal->foto_public_id);
                } catch (\Throwable $ex) {
                    Log::warning('Gagal menghapus foto kapal dari Cloudinary.', [
                        'id_kapal' => $kapal->id_kapal,
                        'public_id' => $kapal->foto_public_id,
                        'error' => $ex->getMessage()
                    ]);
                }
            }

            /* ==========================================================
               HAPUS DOKUMEN KAPAL DARI CLOUDINARY
            ===========================================================*/
            if ($kapal->dokumen_public_id) {
                try {
                    $cloudinary->uploadApi()->destroy($kapal->dokumen_public_id);
                } catch (\Throwable $ex) {
                    Log::warning('Gagal menghapus dokumen kapal dari Cloudinary.', [
                        'id_kapal' => $kapal->id_kapal,
                        'public_id' => $kapal->dokumen_public_id,
                        'error' => $ex->getMessage()
                    ]);
                }
            }

            // 3. Baru hapus record kapal dari database
            $kapalId = $kapal->id_kapal;
            $kapal->delete();

            // 4. Logging
            Log::info('Data kapal berhasil dihapus.', [
                'id_kapal' => $kapalId,
                'user_id' => Auth::id(),
            ]);

            // 5. Response
            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => 'Data kapal berhasil dihapus.'])
                : redirect()->route('user.kapal-index')->with('success', 'Data kapal berhasil dihapus.');

        } catch (\Throwable $e) {

            // Error log
            Log::error('Gagal menghapus kapal.', [
                'id_kapal' => $kapal->id_kapal ?? null,
                'error' => $e->getMessage(),
            ]);

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus data kapal.'], 500)
                : back()->with('error', 'Terjadi kesalahan saat menghapus data kapal.');
        }
    }




    /**
     * Hapus dokumen terkait kapal (izin/dokumen lainnya)
     */
    public function deleteDokumen(Request $request, $id_detail)
    {
        try {
            $user = Auth::user();

            $detail = \App\Models\PengajuanIzinDetail::findOrFail($id_detail);

            // Pastikan hanya pemilik kapal yang boleh hapus
            $izin = $detail->pengajuanIzin;
            if ($izin->kapal->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus dokumen ini.'
                ], 403);
            }

            // Ambil file dari nilai json (public_id + url)
            $fileData = json_decode($detail->nilai, true);

            if ($fileData && isset($fileData['public_id'])) {

                $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));

                try {
                    // Hapus file dari Cloudinary
                    $cloudinary->uploadApi()->destroy($fileData['public_id']);
                } catch (\Throwable $ex) {
                    Log::warning('Gagal menghapus dokumen di Cloudinary.', [
                        'public_id' => $fileData['public_id'],
                        'error' => $ex->getMessage()
                    ]);
                }
            }

            // Hapus record dokumen dari database
            $detail->delete();

            Log::info('Dokumen berhasil dihapus.', [
                'id_detail' => $id_detail,
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus.'
            ]);

        } catch (\Throwable $e) {

            Log::error('Gagal menghapus dokumen.', [
                'id_detail' => $id_detail,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus dokumen.'
            ], 500);
        }
    }

    /**
     * Hapus foto kapal
     */
    public function deleteFoto(Request $request, $id_kapal)
    {
        try {
            $user = Auth::user();

            // Only owner can delete
            $kapal = Kapal::where('id_kapal', $id_kapal)
                ->where('user_id', $user->id)
                ->firstOrFail();

            if (!$kapal->foto_public_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Foto kapal tidak ditemukan.'
                ], 404);
            }

            $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));

            try {
                // Hapus dari Cloudinary
                $cloudinary->uploadApi()->destroy($kapal->foto_public_id);
            } catch (\Throwable $ex) {
                Log::warning('Gagal menghapus foto kapal di Cloudinary.', [
                    'public_id' => $kapal->foto_public_id,
                    'error' => $ex->getMessage()
                ]);
            }

            // Hilangkan dari database
            $kapal->update([
                'foto_public_id' => null,
                'foto_url' => null,
            ]);

            Log::info('Foto kapal berhasil dihapus.', [
                'id_kapal' => $id_kapal,
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto kapal berhasil dihapus.'
            ]);

        } catch (\Throwable $e) {

            Log::error('Gagal menghapus foto kapal.', [
                'id_kapal' => $id_kapal,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus foto kapal.'
            ], 500);
        }
    }


}
