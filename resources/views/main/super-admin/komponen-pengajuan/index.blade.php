@extends('layouts.app-user')

@section('title', 'Daftar Komponen Pengajuan')

@section('content')
    <div class="container p-2">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h4 class="fw-bold">DAFTAR KOMPONEN PENGAJUAN</h4>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('superAdmin.komponen-pengajuan-create') }}"
                class="btn btn-primary {{ request()->routeIs('superAdmin.komponen-pengajuan-*') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i> Tambah Komponen Pengajuan
            </a>
        </div>

        <div class="row g-3">
            {{-- ====== DAFTAR KOMPONEN ====== --}}
            <div class="col-md-12">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success rounded text-center">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Komponen</th>
                                        <th>Tipe</th>
                                        <th>Kewajiban</th>
                                        <th>Status</th>
                                        <th>Opsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($komponen as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="namaKomponen">{{ $item->nama_komponen }}</td>

                                            {{-- ===== TIPE ===== --}}
                                            <td>
                                                <span class="badge badge-soft badge-tipe">
                                                    {{ strtoupper($item->tipe) }}
                                                </span>
                                            </td>

                                            {{-- ===== KEWAJIBAN ===== --}}
                                            <td>
                                                <span
                                                    class="badge badge-soft badge-kewajiban {{ $item->is_required ? 'badge-required' : 'badge-optional' }}">
                                                    {{ strtoupper($item->kewajiban_label) }}
                                                </span>
                                            </td>

                                            {{-- ===== STATUS ===== --}}
                                            <td>
                                                <span
                                                    class="badge badge-soft badge-status {{ $item->is_active ? 'badge-active' : 'badge-nonactive' }}">
                                                    {{ strtoupper($item->status_label) }}
                                                </span>
                                            </td>

                                            <td class="text-start">
                                                @if (is_array($item->opsi))
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach ($item->opsi as $ops)
                                                            <li>{{ $ops }},</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <em>-</em>
                                                @endif
                                            </td>

                                            <td class="aksi-cell">
                                                <div class="aksi-wrapper">
                                                    <a
                                                        href="{{ route('superAdmin.komponen-pengajuan-edit', ['id_komponen_pengajuan_pengajuan' => $item->id_komponen_pengajuan]) }}">
                                                        <i class="bi bi-pencil-square fs-5 text-warning"></i>
                                                    </a>


                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-muted">Belum ada data komponen pengajuan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ====== PREVIEW FORM UNTUK USER ====== --}}
            <div class="col-md-12">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Preview Tampilan Form untuk User</h5>
                        <p class="text-muted small mb-3">
                            Berikut tampilan simulasi form pengajuan yang akan dilihat oleh pengguna.
                        </p>

                        <form>
                            @foreach ($komponen as $item)
                                @if ($item->is_active)
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            {{ $item->nama_komponen }}
                                            @if ($item->is_required)
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>

                                        @switch($item->tipe)
                                            @case('text')
                                                <input type="text" class="form-control"
                                                    placeholder="Masukkan {{ strtolower($item->nama_komponen) }}" disabled>
                                            @break

                                            @case('textarea')
                                                <textarea class="form-control" rows="3" placeholder="Masukkan {{ strtolower($item->nama_komponen) }}" disabled></textarea>
                                            @break

                                            @case('select')
                                                <select class="form-select" disabled>
                                                    <option selected disabled>Pilih salah satu</option>
                                                    @if (is_array($item->opsi))
                                                        @foreach ($item->opsi as $ops)
                                                            <option value="{{ $ops }}">{{ $ops }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            @break

                                            @case('radio')
                                                @if (is_array($item->opsi))
                                                    @foreach ($item->opsi as $ops)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="radio_{{ $item->id }}"
                                                                id="radio_{{ $item->id }}_{{ $loop->index }}" disabled>
                                                            <label class="form-check-label"
                                                                for="radio_{{ $item->id }}_{{ $loop->index }}">
                                                                {{ $ops }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @break

                                            @case('file')
                                                <input type="file" class="form-control" disabled>
                                            @break

                                            @case('date')
                                                <input type="date" class="form-control" value="{{ date('Y-m-d') }}" disabled>
                                            @break

                                            @default
                                                <input type="text" class="form-control" placeholder="Masukkan nilai" disabled>
                                        @endswitch
                                    </div>
                                @endif
                            @endforeach

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-success" disabled>
                                    <i class="bi bi-check-circle"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==== Tambahan CSS ==== --}}
    <style>
        .table {
            border-collapse: collapse !important;
            border: 1px solid #dee2e6;
            width: 100%;
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6 !important;
            vertical-align: middle !important;
            padding: 0.75rem;
        }

        .table-responsive {
            overflow-x: auto;
            overflow-y: visible;
        }

        .aksi-cell {
            text-align: center;
            width: 60px;
        }

        .aksi-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .table-hover tbody tr:hover td {
            background-color: #f8f9fa !important;
        }

        tr:last-child td {
            border-bottom: 1px solid #dee2e6 !important;
        }

        .badge-soft {
            display: inline-block;
            width: 110px;
            /* seragamkan ukuran */
            text-align: center;
            padding: 6px 0;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Warna lembut pastel */
        .badge-tipe {
            background-color: #e1f0ff;
            color: #0d6efd;
        }

        .badge-required {
            background-color: #fde2e1;
            color: #c0392b;
        }

        .badge-optional {
            background-color: #eaeaea;
            color: #6c757d;
        }

        .badge-active {
            background-color: #d8f3dc;
            color: #198754;
        }

        .badge-nonactive {
            background-color: #fff3cd;
            color: #856404;
        }

        .card-body form label {
            font-size: 0.9rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.5rem;
        }

        .btn i {
            margin-right: 4px;
        }

        td.namaKomponen {
            text-align: left;
        }
    </style>
@endsection
