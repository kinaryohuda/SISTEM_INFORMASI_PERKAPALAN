@extends('layouts.app-user')

@section('title', 'DATA KAPAL')

@section('content')
    <div class="container p-2">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h4 class="fw-bold">DATA KAPAL</h4>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('user.kapal-create') }}"
                class="btn btn-primary {{ request()->routeIs('user.kapal-create*') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i> Tambah Kapal
            </a>
        </div>

        {{-- Alert error --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ e(session('error')) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Jika belum ada data --}}
        @if ($kapals->isEmpty())
            <div class="alert alert-info text-center">Anda belum memiliki kapal.</div>
        @else
            <div class="d-flex flex-column gap-3">
                @foreach ($kapals as $kapal)
                    <div class="card shadow-sm p-3">
                        <div class="row align-items-center">
                            <!-- Kolom kiri: info kapal -->
                            <div class="col-md-9 col-12">
                                <h5 class="fw-bold mb-2" style="font-size: 1rem;">
                                    {{ e($kapal->nama_kapal) }}
                                </h5>
                                <p class="mb-1 small text-muted">
                                    Tipe: {{ e($kapal->tipe_kapal ?? '-') }}
                                </p>
                                <p class="mb-1 small text-muted">
                                    Kapasitas: {{ e($kapal->kapasitas ?? '-') }}
                                </p>
                                <p class="mb-1 small text-muted">
                                    Nomor Registrasi: {{ e($kapal->nomor_registrasi) }}
                                </p>
                                <p class="mb-0 small text-muted">
                                    Pemilik: {{ e($kapal->nama_pemilik ?? 'Tidak Diketahui') }}
                                </p>
                            </div>

                            <!-- Kolom kanan: tombol aksi -->
                            <div class="col-md-3 col-12 text-md-end mt-3 mt-md-0">
                                <a href="{{ route('user.kapal-viewDetail', $kapal->id_kapal) }}"
                                    class="btn btn-outline-primary w-100 w-md-auto">
                                    <i class="bi bi-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection