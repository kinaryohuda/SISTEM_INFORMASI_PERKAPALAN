@extends('layouts.app-user')

@section('title', 'Riwayat Pengajuan')

@section('content')

    <style>
        .table th,
        .table td {
            vertical-align: middle !important;
            font-size: 0.875rem;
            white-space: nowrap;
        }

        .table thead th {
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            background-color: #D6EAF8 !important;
            color: #0d47a1;
            text-align: center;
        }
    </style>

    <div class="container p-2">
        <h4 class="fw-bold mb-3">Riwayat Pengajuan Izin</h4>

        @if($riwayat->isNotEmpty())

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kapal</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Nama Verifikator</th>
                            <th>Tanggal Verifikasi</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($riwayat as $i => $item)

                            @php
                                $log = $item->logVerifikator->sortByDesc('verified_at')->first();
                                $badge = [
                                    'menunggu' => 'warning',
                                    'disetujui' => 'success',
                                    'ditolak' => 'danger'
                                ][$item->status] ?? 'secondary';
                            @endphp

                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>

                                <td>{{ $item->kapal->nama_kapal }}</td>

                                <td>{{ $item->created_at->format('d M Y H:i') }}</td>

                                <td>{{ $log->nama_verifikator ?? '-' }}</td>

                                <td>
                                    @if($log?->verified_at)
                                        {{ $log->verified_at->format('d M Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="text-center ">
                                    <span class="badge p-2 bg-{{ $badge }}">{{ ucfirst($item->status) }}</span>
                                </td>

                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#detailModal{{ $item->id_pengajuan }}">
                                        Lihat
                                    </button>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>

        @else
            <div class="alert alert-info">Belum ada riwayat pengajuan.</div>
        @endif
    </div>

    {{-- ============================================
    KUMPULKAN SEMUA MODAL DI BAGIAN BAWAH
    ============================================ --}}
    @foreach($riwayat as $item)

        @php
            $badge = [
                'menunggu' => 'warning',
                'disetujui' => 'success',
                'ditolak' => 'danger'
            ][$item->status] ?? 'secondary';
        @endphp

        <div class="modal fade" id="detailModal{{ $item->id_pengajuan }}" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold">
                            Detail Pengajuan – {{ $item->kapal->nama_kapal }}
                        </h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        {{-- INFORMASI UTAMA --}}
                        <div class="mb-4">
                            <p><strong>Tanggal Pengajuan:</strong>
                                {{ $item->created_at->format('d M Y H:i') }}
                            </p>
                        </div>

                        <hr>

                        {{-- ==============================
                        BAGIAN KOMPONEN + NILAI
                        =============================== --}}

                        <h5 class="fw-bold mb-3">Data Komponen</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Komponen</th>
                                    <th>Nilai / Dokumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->details as $detail)
                                    @php
                                        $decoded = json_decode($detail->nilai, true);
                                    @endphp

                                    <tr>
                                        <td class="fw-bold">{{ $detail->komponen->nama_komponen }}</td>

                                        <td>
                                            {{-- Jika file --}}
                                            @if(is_array($decoded) && isset($decoded['url']))
                                                <a href="{{ $decoded['url'] }}" target="_blank" class="text-primary">
                                                    <i class="bi bi-paperclip fs-4"></i>
                                                </a>

                                                {{-- Jika teks biasa --}}
                                            @else
                                                {{ $detail->nilai }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <hr>

                        {{-- ==============================
                        RIWAYAT VERIFIKASI
                        =============================== --}}
                        <h5 class="fw-bold">Riwayat Verifikasi</h5>

                        @forelse ($item->logVerifikator as $log)
                            <div class="p-3 mb-3 border rounded bg-light">
                                <strong>{{ $log->nama_verifikator }}</strong><br>
                                <small class="text-muted">
                                    {{ $log->verified_at?->format('d M Y H:i') }}
                                </small> <br>
                                <span class="badge bg-primary">{{ $log->status_baru }}</span>

                                <p class="mt-2">{{ $log->catatan_verifikator }}</p>

                            </div>

                        @empty
                            <p class="text-muted">Belum ada riwayat verifikasi.</p>
                        @endforelse

                    </div>

                </div>
            </div>
        </div>

    @endforeach

@endsection