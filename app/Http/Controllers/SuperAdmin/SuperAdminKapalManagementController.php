<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Kapal;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SuperAdminKapalManagementController extends Controller
{
    /**
     * Sanitasi input dasar
     */
    private function sanitizeInput($value)
    {
        if (is_array($value)) {
            return array_map(fn($v) => $this->sanitizeInput($v), $value);
        }

        return trim(htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8'));
    }

    /**
     * LIST SEMUA KAPAL (Super Admin)
     */
    public function index()
    {
        $kapals = Kapal::with('user')->orderBy('created_at', 'desc')->paginate(15);

        return view('main.super-admin.kapal.index-kapal-super-admin', [
            'title' => 'SIPK : Data Semua Kapal',
            'kapals' => $kapals
        ]);
    }

    /**
     * DETAIL KAPAL
     */
    public function show($id_kapal)
    {
        $kapal = Kapal::with('user')->where('id_kapal', $id_kapal)->firstOrFail();

        $riwayat = PengajuanIzin::with(['details.komponen'])
            ->where('id_kapal', $kapal->id_kapal)
            ->orderBy('created_at', 'desc')
            ->get();

        $riwayat->each(function ($pengajuan) {
            $pengajuan->details->each(function ($detail) {
                if ($detail->nilai) {
                    $decoded = json_decode($detail->nilai, true);
                    $detail->file_url = $decoded['url'] ?? null;
                    $detail->file_type = $decoded['resource_type'] ?? 'raw';
                }
            });
        });

        return view('superadmin.kapal.detail', [
            'title' => 'Detail Kapal',
            'kapal' => $kapal,
            'riwayat_pengajuan' => $riwayat,
        ]);
    }

    /**
     * FORM TAMBAH KAPAL
     */
    public function create()
    {
        return view('superadmin.kapal.create', [
            'title' => 'Tambah Kapal Baru'
        ]);
    }

    /**
     * SIMPAN KAPAL BARU
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',

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

        $sanitized = $this->sanitizeInput($validated);
        $sanitized['nomor_registrasi'] = strtoupper($sanitized['nomor_registrasi']);

        $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
        $timestamp = date('Ymd_His');
        $slug = Str::slug($sanitized['nama_kapal'], '-');

        // === Upload Foto ===
        $fotoUpload = $cloudinary->uploadApi()->upload(
            $request->file('foto_kapal')->getRealPath(),
            [
                'folder' => 'kapal/foto',
                'resource_type' => 'image',
                'use_filename' => true,
                'unique_filename' => false,
                'overwrite' => true,
            ]
        );

        // === Upload Dokumen PDF ===
        $dokumenUpload = $cloudinary->uploadApi()->upload(
            $request->file('dokumen_kapal')->getRealPath(),
            [
                'folder' => 'kapal/dokumen',
                'resource_type' => 'raw',
                'format' => 'pdf',
                'overwrite' => true,
            ]
        );

        $kapal = Kapal::create([
            'user_id' => $sanitized['user_id'],
            'nama_pemilik' => $sanitized['nama_pemilik'],
            'nik' => $sanitized['nik'],
            'alamat' => $sanitized['alamat'] ?? null,
            'nama_kapal' => $sanitized['nama_kapal'],
            'tipe_kapal' => $sanitized['tipe_kapal'],
            'kapasitas' => $sanitized['kapasitas'],
            'nomor_registrasi' => $sanitized['nomor_registrasi'],

            'foto_public_id' => $fotoUpload['public_id'],
            'foto_url' => $fotoUpload['secure_url'],

            'dokumen_public_id' => $dokumenUpload['public_id'],
            'dokumen_url' => $dokumenUpload['secure_url'],
        ]);

        return redirect()->route('superadmin.kapal.show', $kapal->id_kapal)
            ->with('success', 'Data kapal berhasil ditambahkan.');
    }

    /**
     * FORM EDIT
     */
    public function edit($id_kapal)
    {
        $kapal = Kapal::findOrFail($id_kapal);

        return view('superadmin.kapal.edit', [
            'title' => 'Edit Kapal',
            'kapal' => $kapal
        ]);
    }

    /**
     * UPDATE KAPAL
     */
    public function update(Request $request, $id_kapal)
    {
        $kapal = Kapal::findOrFail($id_kapal);

        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:100',
            'nik' => [
                'required', 'string',
                Rule::unique('kapal', 'nik')->ignore($kapal->id_kapal, 'id_kapal')
            ],
            'alamat' => 'nullable|string|max:255',
            'nama_kapal' => 'required|string|max:100',
            'tipe_kapal' => 'nullable|string|max:50',
            'kapasitas' => 'nullable|integer|min:1',
            'nomor_registrasi' => [
                'required', 'string',
                Rule::unique('kapal', 'nomor_registrasi')->ignore($kapal->id_kapal, 'id_kapal')
            ],

            'foto_kapal' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'dokumen_kapal' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $sanitized = $this->sanitizeInput($validated);

        $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));

        // === Update Foto ===
        if ($request->hasFile('foto_kapal')) {

            if ($kapal->foto_public_id) {
                $cloudinary->uploadApi()->destroy($kapal->foto_public_id);
            }

            $fotoUpload = $cloudinary->uploadApi()->upload(
                $request->file('foto_kapal')->getRealPath(),
                [
                    'folder' => 'kapal/foto',
                    'resource_type' => 'image',
                ]
            );

            $kapal->foto_public_id = $fotoUpload['public_id'];
            $kapal->foto_url = $fotoUpload['secure_url'];
        }

        // === Update Dokumen ===
        if ($request->hasFile('dokumen_kapal')) {

            if ($kapal->dokumen_public_id) {
                $cloudinary->uploadApi()->destroy($kapal->dokumen_public_id);
            }

            $dokumenUpload = $cloudinary->uploadApi()->upload(
                $request->file('dokumen_kapal')->getRealPath(),
                [
                    'folder' => 'kapal/dokumen',
                    'resource_type' => 'raw',
                    'format' => 'pdf'
                ]
            );

            $kapal->dokumen_public_id = $dokumenUpload['public_id'];
            $kapal->dokumen_url = $dokumenUpload['secure_url'];
        }

        $kapal->update($sanitized);

        return redirect()->route('superadmin.kapal.show', $kapal->id_kapal)
            ->with('success', 'Data kapal berhasil diperbarui.');
    }

    /**
     * DELETE KAPAL
     */
    public function destroy($id_kapal)
    {
        $kapal = Kapal::findOrFail($id_kapal);
        $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));

        // Hapus foto
        if ($kapal->foto_public_id) {
            $cloudinary->uploadApi()->destroy($kapal->foto_public_id);
        }

        // Hapus dokumen
        if ($kapal->dokumen_public_id) {
            $cloudinary->uploadApi()->destroy($kapal->dokumen_public_id);
        }

        $kapal->delete();

        return redirect()->route('superadmin.kapal.index')
            ->with('success', 'Data kapal berhasil dihapus.');
    }
}
