@php
    // Ambil semua nama komponen unik dari seluruh pengajuan
    $allKomponen = collect();

    if (!empty($riwayat_pengajuan) && $riwayat_pengajuan->isNotEmpty()) {
        foreach ($riwayat_pengajuan as $pengajuan) {
            foreach ($pengajuan->details as $detail) {
                if (!empty($detail->komponen?->nama_komponen)) {
                    $allKomponen->push($detail->komponen->nama_komponen);
                }
            }
        }

        $allKomponen = $allKomponen->unique()->values();
    }
@endphp

<div class="card shadow-sm border-0 p-3">

    <div>
        <h5 class="fw-bold text-primary mb-0">
            <i class="bi bi-clock-history me-2"></i>Riwayat Pengajuan
        </h5>
        <hr class="mt-1 mb-3">
    </div>

    {{-- Baris Tombol + Search --}}
    <div class="row mb-3 p-2">
        <div class="col-12">
            <div class="row align-items-center g-2">

                {{-- Tombol Ajukan --}}
                <div class="col-md-4 col-lg-3 col-12">
                    <a href="{{ route('user.pengajuan-permohonan-index') }}"
                       class="btn btn-outline-success btn-sm w-100 shadow-sm">
                        <i class="bi bi-journal-text me-1"></i> Ajukan Perizinan
                    </a>
                </div>

                {{-- Kolom Search --}}
                <div class="col-md-8 col-lg-9 col-12">
                    <form class="d-flex" role="search">
                        <input type="text" class="form-control form-control-sm shadow-sm"
                            placeholder="Cari riwayat..." name="search" id="search">
                        <button class="btn btn-outline-primary btn-sm ms-2 shadow-sm" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- ======= Tabel Riwayat Pengajuan ======= --}}
    @if(!empty($riwayat_pengajuan) && $riwayat_pengajuan->isNotEmpty())

        <div class="table-responsive mt-2">
            <table class="table table-hover table-bordered align-middle text-sm">
                <thead class="text-center align-middle">
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>

                        {{-- Header dinamis berdasarkan komponen --}}
                        @foreach($allKomponen as $komponen)
                            <th>{{ $komponen }}</th>
                        @endforeach

                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($riwayat_pengajuan as $index => $pengajuan)
                        <tr>
                            <td class="text-center fw-semibold">{{ $index + 1 }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($pengajuan->created_at)
                                    ->timezone('Asia/Jakarta')
                                    ->format('d M Y H:i') }}
                            </td>

                            {{-- Status --}}
                            <td class="text-center">
                                @php
                                    $status = strtolower($pengajuan->status);
                                    $badgeClass = match($status) {
                                        'disetujui' => 'success',
                                        'dibatalkan' => 'danger',
                                        'menunggu' => 'warning',
                                        default => 'secondary'
                                    };
                                @endphp

                                <span class="badge bg-{{ $badgeClass }} px-3 py-2 shadow-sm">
                                    {{ ucfirst($pengajuan->status) }}
                                </span>
                            </td>

                            {{-- Nilai setiap komponen --}}
                            @foreach($allKomponen as $komponen)
                                @php
                                    $detail = $pengajuan->details
                                        ->firstWhere('komponen.nama_komponen', $komponen);

                                    $decoded = $detail ? json_decode($detail->nilai, true) : null;
                                @endphp

                                <td class="text-center">
                                    @if($detail)
                                        {{-- Jika nilai adalah file (berformat JSON: {"url": "..."}) --}}
                                        @if(is_array($decoded) && isset($decoded['url']))
                                            <a href="{{ $decoded['url'] }}"
                                               target="_blank"
                                               class="text-decoration-none text-primary fw-semibold">
                                                <i class="bi bi-paperclip"></i> File
                                            </a>
                                        @else
                                            <span>{{ $detail->nilai ?? '-' }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endforeach

                            {{-- Tombol Aksi --}}
                            <td class="text-center">
                                <a href="#" class="btn btn-outline-primary btn-sm shadow-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    @else
        <div class="alert alert-secondary mt-2" role="alert">
            Belum ada riwayat pengajuan untuk kapal ini.
        </div>
    @endif

</div>

{{-- ====== Styling tambahan ====== --}}
<style>
    .table th, .table td {
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

    .table-hover tbody tr:hover {
        background-color: #f8f9ff;
        transition: 0.2s ease-in-out;
    }

    .badge {
        font-size: 0.75rem;
    }

    .card {
        border-radius: 0.75rem !important;
    }

    input.search-input::placeholder {
        color: #aaa;
        font-style: italic;
    }
</style>
