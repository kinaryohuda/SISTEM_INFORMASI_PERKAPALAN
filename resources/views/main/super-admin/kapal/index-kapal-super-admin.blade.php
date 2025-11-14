@extends('layouts.app-user')

@section('title', 'Daftar Kapal')

@section('content')

    <div class="card shadow-sm border-0 p-3">

        {{-- ======= JUDUL ======= --}}
        <div>
            <h5 class="fw-bold text-primary mb-0">
                   <i class="fa-solid fa-ship me-2"></i></i>Data Semua Kapal
            </h5>
            <hr class="mt-1 mb-3">
        </div>

        {{-- ======= Baris Tombol + Search ======= --}}
        <div class="row mb-3 p-2">
            <div class="col-12">
                <div class="row align-items-center g-2">

                    {{-- Tombol Tambah --}}
                    <div class="col-md-4 col-lg-3 col-12">
                        <a href="#"
                            class="btn btn-outline-success btn-sm w-100 shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Kapal
                        </a>
                    </div>

                    {{-- Search --}}
                    <div class="col-md-8 col-lg-9 col-12">
                        <form class="d-flex" method="GET">
                            <input type="text" name="search" class="form-control form-control-sm shadow-sm"
                                placeholder="Cari kapal..." value="{{ request('search') }}">
                            <button class="btn btn-outline-primary btn-sm ms-2 shadow-sm" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        {{-- ======= TABEL KAPAL ======= --}}
        <div class="table-responsive mt-2">
            <table class="table table-hover table-bordered align-middle text-sm">

                <thead class="text-center align-middle">
                    <tr>
                        <th>No</th>
                        <th>Nama Kapal</th>
                        <th>Pemilik</th>
                        <th>NIK</th>
                        <th>Tipe</th>
                        <th>Kapasitas</th>
                        <th>No Registrasi</th>
                        <th>Perizinan</th>
                        <th>Disetujui</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($kapals as $i => $k)

                        {{-- Hitung pengajuan & disetujui --}}
                        @php
                            $jumlah_pengajuan = \App\Models\PengajuanIzin::where('id_kapal', $k->id_kapal)->count();
                            $jumlah_disetujui = \App\Models\PengajuanIzin::where('id_kapal', $k->id_kapal)
                                ->where('status', 'disetujui')
                                ->count();
                        @endphp

                        <tr>
                            <td class="text-center fw-semibold">{{ $kapals->firstItem() + $i }}</td>

                            <td>{{ $k->nama_kapal }}</td>

                            <td>{{ $k->nama_pemilik }}</td>

                            <td>{{ $k->nik }}</td>

                            <td class="text-center">{{ $k->tipe_kapal ?? '-' }}</td>

                            <td class="text-center">{{ $k->kapasitas ?? '-' }}</td>

                            <td class="text-center">{{ $k->nomor_registrasi }}</td>

                            <td class="text-center">
                                <span class="badge bg-warning px-3 py-2 shadow-sm">
                                    {{ $jumlah_pengajuan }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-success px-3 py-2 shadow-sm">
                                    {{ $jumlah_disetujui }}
                                </span>
                            </td>

                            <td class="text-center">

                                {{-- Show --}}
                                <a href="#"
                                    class="btn btn-outline-primary btn-sm shadow-sm">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Edit --}}
                                <a href="#"
                                    class="btn btn-outline-warning btn-sm shadow-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- Delete --}}
                                <form action="#"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus kapal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm shadow-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-3">
                                Tidak ada data kapal.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-3">
            {{ $kapals->links() }}
        </div>

    </div>

    {{-- ====== CSS KHUSUS STYLE ====== --}}
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
