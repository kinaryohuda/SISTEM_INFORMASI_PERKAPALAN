@extends('layouts.app-user')

@section('title', 'Daftar Pengguna User')

@section('content')

    <div class="container p-3">

        {{-- ======= JUDUL ======= --}}
        <div>
            <h5 class="fw-bold text-primary mb-0">
                <i class="bi bi-people-fill me-2"></i>Daftar Pengguna (User)
            </h5>
            <hr class="mt-1 mb-3">
        </div>

        {{-- ======= Baris Tombol + Search ======= --}}
        <div class="row mb-3 p-2">
            <div class="col-12">
                <div class="row align-items-center g-2">

                    {{-- Tombol Tambah --}}
                    <div class="col-md-4 col-lg-3 col-12">
                        <a href="{{ route('superAdmin.pengguna-user-create') }}"
                            class="btn btn-outline-success btn-sm w-100 shadow-sm">
                            <i class="bi bi-person-plus me-1"></i> Tambah User
                        </a>
                    </div>

                    {{-- Search --}}
                    <div class="col-md-8 col-lg-9 col-12">
                        <form class="d-flex" method="GET">
                            <input type="text" name="search" class="form-control form-control-sm shadow-sm"
                                placeholder="Cari pengguna..." value="{{ request('search') }}">
                            <button class="btn btn-outline-primary btn-sm ms-2 shadow-sm" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        {{-- ======= TABEL USER ======= --}}
        <div class="table-responsive mt-2">
            <table class="table table-hover table-bordered align-middle text-sm">

                <thead class="text-center align-middle">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Alamat</th>
                        <th>Jumlah Kapal</th>
                        <th>Pengajuan</th>
                        <th>Disetujui</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $i => $u)
                        <tr>
                            <td class="text-center fw-semibold">{{ $users->firstItem() + $i }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->nik }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->no_hp }}</td>
                            <td>{{ $u->alamat }}</td>

                            <td class="text-center">
                                <span class="badge bg-info px-3 py-2 shadow-sm">
                                    {{ $u->jumlah_kapal }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-warning px-3 py-2 shadow-sm">
                                    {{ $u->jumlah_perizinan }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-success px-3 py-2 shadow-sm">
                                    {{ $u->jumlah_disetujui }}
                                </span>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('superAdmin.pengguna-user-show', $u->id) }}"
                                    class="btn btn-outline-primary btn-sm shadow-sm ">
                                    <i class="bi bi-eye"></i>
                                </a>


                                <a href="{{ route('superAdmin.pengguna-user-edit', $u->id) }}"
                                    class="btn btn-outline-warning btn-sm shadow-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('superAdmin.pengguna-user-delete', $u->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
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
                                Tidak ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-3">
            {{ $users->links() }}
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