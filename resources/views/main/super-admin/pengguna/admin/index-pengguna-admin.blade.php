@extends('layouts.app-user')

@section('title', 'Daftar Admin')

@section('content')

    <div class="container p-3">

        {{-- ======= JUDUL ======= --}}
        <div>
            <h5 class="fw-bold text-primary mb-0">
                <i class="bi bi-person-gear me-2"></i>Daftar Admin
            </h5>
            <hr class="mt-1 mb-3">
        </div>

        {{-- ======= Tombol + Search ======= --}}
        <div class="row mb-3 p-2">
            <div class="col-12">
                <div class="row align-items-center g-2">

                    {{-- Tombol Tambah --}}
                    <div class="col-md-4 col-lg-3 col-12">
                        <a href="{{ route('superAdmin.pengguna-admin-create') }}"
                            class="btn btn-outline-success btn-sm w-100 shadow-sm">
                            <i class="bi bi-person-plus me-1"></i> Tambah Admin
                        </a>
                    </div>

                    {{-- Search --}}
                    <div class="col-md-8 col-lg-9 col-12">
                        <form class="d-flex" method="GET">
                            <input type="text" name="search" class="form-control form-control-sm shadow-sm"
                                placeholder="Cari admin..." value="{{ request('search') }}">
                            <button class="btn btn-outline-primary btn-sm ms-2 shadow-sm" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        {{-- ======= TABEL ADMIN ======= --}}
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
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($admins as $i => $a)
                        <tr>
                            <td class="text-center fw-semibold">{{ $admins->firstItem() + $i }}</td>
                            <td>{{ $a->name }}</td>
                            <td>{{ $a->nik }}</td>
                            <td>{{ $a->email }}</td>
                            <td>{{ $a->no_hp }}</td>
                            <td>{{ $a->alamat }}</td>

                            <td class="text-center">
                                <a href="#"
                                    class="btn btn-outline-primary btn-sm shadow-sm ">
                                    <i class="bi bi-eye"></i>
                                </a>


                                {{-- EDIT --}}
                                <a href="{{ route('superAdmin.pengguna-admin-edit', $a->id) }}"
                                    class="btn btn-outline-warning btn-sm shadow-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- DELETE --}}
                                <form action="{{ route('superAdmin.pengguna-admin-delete', $a->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
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
                            <td colspan="8" class="text-center text-muted py-3">
                                Tidak ada data admin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-3">
            {{ $admins->links() }}
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