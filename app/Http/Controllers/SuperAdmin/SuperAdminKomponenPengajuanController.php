<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\KomponenPengajuan;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SuperAdminKomponenPengajuanController extends Controller
{
    /**
     * Tampilkan daftar komponen pengajuan.
     */
    public function index()
    {
        $komponen = KomponenPengajuan::orderByDesc('is_active')
            ->latest()
            ->get();

        $title = 'SIPK : Komponen Pengajuan';

        return view('main.super-admin.komponen-pengajuan.index', compact('komponen', 'title'));
    }

    /**
     * Tampilkan form tambah komponen baru.
     */
    public function create()
    {

        $title = 'SIPK : Tambah Komponen Pengajuan';
        return view(
            'main.super-admin.komponen-pengajuan.create',
            compact('title')
        );
    }

    /**
     * Simpan data komponen baru ke database.
     */
    public function store(Request $request)
    {

        Log::info('Request store komponen pengajuan', $request->all()); // log request masuk

        $validator = Validator::make(
            $request->all(),
            [
                'nama_komponen' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[^<>]+$/', // cegah XSS
                    'unique:komponen_pengajuan,nama_komponen',
                ],
                'tipe' => ['required', 'string', 'max:100', 'regex:/^[^<>]+$/'],
                'is_required' => ['required', 'boolean'],
                'is_active' => ['required', 'boolean'],
                'opsi' => ['nullable', 'array'],
                'opsi.*' => ['nullable', 'string', 'max:255', 'regex:/^[^<>]+$/'],
            ],
            [
                'nama_komponen.required' => 'Nama komponen wajib diisi.',
                'nama_komponen.string' => 'Nama komponen harus berupa teks.',
                'nama_komponen.max' => 'Nama komponen tidak boleh lebih dari 255 karakter.',
                'nama_komponen.regex' => 'Nama komponen mengandung karakter tidak valid.',
                'nama_komponen.unique' => 'Nama komponen sudah digunakan, silakan pilih nama lain.',

                'tipe.required' => 'Tipe komponen wajib diisi.',
                'tipe.string' => 'Tipe komponen harus berupa teks.',
                'tipe.max' => 'Tipe komponen tidak boleh lebih dari 100 karakter.',
                'tipe.regex' => 'Tipe komponen mengandung karakter tidak valid.',

                'is_required.required' => 'Kewajiban komponen wajib dipilih.',
                'is_required.boolean' => 'Format is_required tidak valid.',

                'is_active.required' => 'Status komponen wajib dipilih.',
                'is_active.boolean' => 'Format is_active tidak valid.',

                'opsi.array' => 'Opsi harus berupa array.',
                'opsi.*.string' => 'Setiap opsi harus berupa teks.',
                'opsi.*.max' => 'Setiap opsi tidak boleh lebih dari 255 karakter.',
                'opsi.*.regex' => 'Beberapa opsi mengandung karakter tidak valid.',
            ]
        );

        // Validasi tambahan: jika tipe select/radio, opsi wajib
        $validator->after(function ($validator) use ($request) {
            if (in_array($request->tipe, ['select', 'radio'])) {
                if (empty($request->opsi) || !is_array($request->opsi) || count(array_filter($request->opsi)) === 0) {
                    $validator->errors()->add('opsi', 'Opsi wajib diisi untuk tipe select atau radio.');
                }
            }
        });

        if ($validator->fails()) {
            Log::warning('Validasi gagal store komponen', $validator->errors()->toArray()); // log error validasi

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        // Sanitasi XSS
        $validated['nama_komponen'] = e($validated['nama_komponen']);
        $validated['tipe'] = e($validated['tipe']);
        if (isset($validated['opsi'])) {
            $validated['opsi'] = array_map(fn($item) => e($item), $validated['opsi']);
        }

        try {
            $komponen = KomponenPengajuan::create($validated);
            Log::info('Komponen berhasil dibuat', ['id' => $komponen->id]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan komponen pengajuan', ['error' => $e->getMessage()]);
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat menyimpan data.',
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Komponen berhasil ditambahkan.',
                'data' => $komponen,
            ], 201);
        }

        return redirect()->route('superAdmin.komponen-pengajuan-index')
            ->with('success', 'Komponen berhasil ditambahkan.');
    }


    /**
     * Tampilkan detail satu komponen.
     */
    public function show(KomponenPengajuan $komponen_pengajuan)
    {
        return view('main.super-admin.komponen-pengajuan.show', compact('komponen_pengajuan'));
    }

    /**
     * Tampilkan form edit komponen.
     */
    public function edit($id_komponen_pengajuan)
    {
        $komponen = KomponenPengajuan::findOrFail($id_komponen_pengajuan);
        $title = 'SIPK : Edit Komponen Pengajuan';

        return view('main.super-admin.komponen-pengajuan.edit', compact('komponen', 'title'));
    }


    /**
     * Update data komponen yang ada.
     */
    public function update(Request $request, $id_komponen_pengajuan)
{
    // Ambil data komponen berdasarkan ID
    $komponen_pengajuan = KomponenPengajuan::findOrFail($id_komponen_pengajuan);

    // Validasi input
    $validator = Validator::make(
        $request->all(),
        [
            'nama_komponen' => [
                'required',
                'string',
                'max:255',
                'regex:/^[^<>]+$/',
                Rule::unique('komponen_pengajuan', 'nama_komponen')
                    ->ignore($komponen_pengajuan->id_komponen_pengajuan, 'id_komponen_pengajuan'),
            ],
            'tipe' => ['required', 'string', 'max:100', 'regex:/^[^<>]+$/'],
            'is_required' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'opsi' => 'nullable|array',
            'opsi.*' => 'nullable|string|max:255|regex:/^[^<>]+$/',
        ],
        [
            'nama_komponen.required' => 'Nama komponen wajib diisi.',
            'nama_komponen.string' => 'Nama komponen harus berupa teks.',
            'nama_komponen.max' => 'Nama komponen tidak boleh lebih dari 255 karakter.',
            'nama_komponen.regex' => 'Nama komponen mengandung karakter tidak valid.',
            'nama_komponen.unique' => 'Nama komponen sudah digunakan oleh data lain.',

            'tipe.required' => 'Tipe komponen wajib diisi.',
            'tipe.string' => 'Tipe komponen harus berupa teks.',
            'tipe.max' => 'Tipe komponen tidak boleh lebih dari 100 karakter.',
            'tipe.regex' => 'Tipe komponen mengandung karakter tidak valid.',

            'is_required.boolean' => 'Format is_required tidak valid.',
            'is_active.boolean' => 'Format is_active tidak valid.',

            'opsi.array' => 'Opsi harus berupa array.',
            'opsi.*.string' => 'Setiap opsi harus berupa teks.',
            'opsi.*.max' => 'Setiap opsi tidak boleh lebih dari 255 karakter.',
            'opsi.*.regex' => 'Beberapa opsi mengandung karakter tidak valid.',
        ]
    );

    // Jika validasi gagal
    if ($validator->fails()) {
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $validated = $validator->validated();

    // Sanitasi input
    $validated['nama_komponen'] = e($validated['nama_komponen']);
    $validated['tipe'] = e($validated['tipe']);
    $validated['is_required'] = isset($validated['is_required']) ? (bool) $validated['is_required'] : false;
    $validated['is_active'] = isset($validated['is_active']) ? (bool) $validated['is_active'] : true;

    // Tangani opsi (array JSON)
    if (isset($validated['opsi']) && is_array($validated['opsi'])) {
        // Bersihkan nilai kosong dan sanitasi
        $validated['opsi'] = array_values(array_filter(
            array_map(fn($item) => e(trim($item)), $validated['opsi'])
        ));
    } else {
        $validated['opsi'] = null;
    }

    // Update data ke database
    $komponen_pengajuan->update($validated);

    // Respons
    if ($request->wantsJson()) {
        return response()->json([
            'status' => 'success',
            'message' => 'Komponen berhasil diperbarui.',
            'data' => $komponen_pengajuan,
        ], 200);
    }

    return redirect()->route('superAdmin.komponen-pengajuan-index')
        ->with('success', 'Komponen berhasil diperbarui.');
}



    /**
     * Hapus komponen dari database.
     */
    public function destroy(Request $request, KomponenPengajuan $komponen_pengajuan)
    {
        $komponen_pengajuan->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Komponen berhasil dihapus.',
                'id' => $komponen_pengajuan->id_komponen_pengajuan,
            ], 200);
        }

        return redirect()->route('komponen-pengajuan-index')
            ->with('success', 'Komponen berhasil dihapus.');
    }
}
