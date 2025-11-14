<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanIzin;
use Illuminate\Support\Facades\Log;

class SuperAdminVerifikatorController extends Controller
{
    public function index()
    {
        $pengajuans = PengajuanIzin::with(['kapal.user', 'logVerifikator'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('main.super-admin.verifikasi.index-verifikasi', compact('pengajuans'));
    }

    public function show($id_pengajuan)
    {
        try {
            $pengajuan = PengajuanIzin::with([
                'kapal',
                'logVerifikator',
                'details.komponen'
            ])->findOrFail($id_pengajuan);

            $payload = $pengajuan->toArray();

            // Samakan nama relasi log
            $payload['log_verifikator'] = $pengajuan->logVerifikator->map(function ($log) {
                return [
                    'nama_verifikator' => $log->nama_verifikator,
                    'status_baru' => $log->status_baru,
                    'catatan_verifikator' => $log->catatan_verifikator,
                    'created_at' => $log->created_at->format('d M Y H:i'),
                ];
            })->toArray();

            // Proses detail berdasarkan tipe komponen
            $payload['files'] = [];
            $payload['fotos'] = [];
            $payload['inputs'] = [];

            foreach ($pengajuan->details as $detail) {
                $komponen = $detail->komponen;
                $tipe = strtolower($komponen->tipe);
                $nama = $komponen->nama_komponen;
                $nilai = $detail->nilai;

                if (in_array($tipe, ['foto', 'file'])) {
                    $decoded = json_decode($nilai, true);
                    if (is_array($decoded) && isset($decoded['url'])) {
                        if ($tipe === 'foto') {
                            $payload['fotos'][] = [
                                'nama' => $nama,
                                'url' => $decoded['url']
                            ];
                        } else {
                            $payload['files'][] = [
                                'nama' => $nama,
                                'url' => $decoded['url'],
                                'format' => $decoded['format'] ?? null
                            ];
                        }
                    }
                } else {
                    $payload['inputs'][] = [
                        'nama' => $nama,
                        'nilai' => $nilai
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $payload
            ]);
        } catch (\Exception $e) {
            Log::error("Error show pengajuan {$id_pengajuan}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id_pengajuan)
    {
        Log::info("Mulai proses updateStatus pengajuan", [
            'id_pengajuan' => $id_pengajuan,
            'user_id' => Auth::id(),
            'status_request' => $request->status,
            'catatan_request' => $request->catatan_verifikator ?? null
        ]);

        $request->validate([
            'status' => 'required|in:disetujui,ditolak,menunggu',
            'catatan_verifikator' => 'nullable|string'
        ]);

        try {
            //  Ambil data pengajuan
            $pengajuan = PengajuanIzin::findOrFail($id_pengajuan);
            Log::info("Pengajuan ditemukan", ['status_sebelumnya' => $pengajuan->status]);

            $statusAwal = $pengajuan->status;
            $statusBaru = $request->status;

            // Update status pengajuan
            $pengajuan->status = $statusBaru;
            $pengajuan->save();
            Log::info("Status pengajuan berhasil diupdate", [
                'status_awal' => $statusAwal,
                'status_baru' => $statusBaru
            ]);

            // Tentukan default catatan jika tidak diisi
            $defaultCatatan = match ($statusBaru) {
                'disetujui' => 'Pengajuan telah diverifikasi dan disetujui.',
                'ditolak' => 'Pengajuan tidak disetujui karena data tidak lengkap atau tidak valid.',
                'menunggu' => 'Pengajuan sedang menunggu verifikasi.',
                default => null
            };
            $catatanAkhir = $request->catatan_verifikator ?? $defaultCatatan;

            Log::info("Catatan verifikator ditentukan", [
                'catatan' => $catatanAkhir
            ]);

            // Catat log verifikator
            $pengajuan->logVerifikator()->create([
                'verifikator_id' => Auth::id(),
                'nama_verifikator' => Auth::user()->name,
                'status_awal' => $statusAwal,
                'status_baru' => $statusBaru,
                'catatan_verifikator' => $catatanAkhir,
                'verified_at' => now()
            ]);
            Log::info("Log verifikator berhasil dicatat", [
                'status_awal' => $statusAwal,
                'status_baru' => $statusBaru,
                'catatan' => $catatanAkhir
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui menjadi: ' . strtoupper($statusBaru)
            ]);
        } catch (\Exception $e) {
            Log::error("Error update status pengajuan", [
                'id_pengajuan' => $id_pengajuan,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }
}
