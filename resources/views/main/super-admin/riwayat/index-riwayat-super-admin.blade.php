@extends('layouts.app-user')

@section('title', 'Riwayat Pengajuan Izin')

@section('content')

    <div class="container p-3">

    <div>
        <h5 class="fw-bold text-primary mb-0">
            <i class="bi bi-journal-check me-2"></i>Riwayat Pengajuan Izin
        </h5>
        <hr class="mt-1 mb-3">
    </div>

    {{-- ======= Search ======= --}}
    <div class="row mb-3 p-2">
        <div class="col-12">
            <form class="d-flex" method="GET">
                <input type="text" name="search" class="form-control form-control-sm shadow-sm"
                    placeholder="Cari nama pemilik atau kapal..." value="{{ request('search') }}">
                <button class="btn btn-outline-primary btn-sm ms-2 shadow-sm" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- ======= TABEL RIWAYAT ======= --}}
    <div class="table-responsive mt-2">
        <table class="table table-hover table-bordered align-middle text-sm">

            <thead class="text-center align-middle">
                <tr>
                    <th>No</th>
                    <th>Nama Pemilik</th>
                    <th>Nama Kapal</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($riwayat as $i => $p)
                    <tr>
                        <td class="text-center fw-semibold">{{ $riwayat->firstItem() + $i ?? $i + 1 }}</td>
                        <td>{{ $p->kapal->nama_pemilik ?? '-' }}</td>
                        <td>{{ $p->kapal->nama_kapal ?? '-' }}</td>
                        <td class="text-center">{{ $p->created_at->format('d-m-Y H:i') }}</td>
                        <td class="text-center">
                            @php
                                $statusClass = match($p->status) {
                                    'menunggu' => 'badge bg-warning text-dark',
                                    'disetujui' => 'badge bg-success',
                                    'ditolak' => 'badge bg-danger',
                                    default => 'badge bg-secondary',
                                };
                            @endphp
                            <span class="{{ $statusClass }}">{{ ucfirst($p->status) }}</span>
                        </td>
                        <td class="text-center">
                            <a href="#"
                                class="btn btn-outline-primary btn-sm shadow-sm">
                                <i class="bi bi-eye"></i> Lihat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            Tidak ada pengajuan izin.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $riwayat->links() }}
    </div>

</div>

{{-- ====== CSS STYLE ====== --}}
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

    .table-hover tbody tr:hover {
        background-color: #f8f9ff !important;
        transition: 0.2s ease-in-out;
    }

    .badge {
        font-size: 0.75rem;
    }

    .card {
        border-radius: 0.75rem !important;
    }
</style>

@endsection
