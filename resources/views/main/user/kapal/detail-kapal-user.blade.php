@extends('layouts.app-user')

@section('title', 'Detail Kapal')

@section('content')
    <style>
        .search-input {
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
        }

        .search-input:focus {
            box-shadow: 0 0 5px rgba(62, 134, 241, 0.5);
            border-color: #3e86f1;
        }

        .card {
            border-radius: 10px;
            border: 1px solid #dee2e6;
        }

        .kapal-photo {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #f8f9fa;
        }

        .file-preview {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            height: 250px;
            margin-top: 5px;
        }

        .file-preview i {

            font-size: 24px;
            margin-right: 8px;
        }
    </style>

    <div class="container p-2">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h4 class="fw-bold">DATA KAPAL - DETAIL</h4>
        </div>

        {{-- Tombol Navigasi --}}
        <div class="d-flex justify-content-between align-items-center mb-1 p-2">
            <a href="{{ route('user.kapal-index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <a href="{{ route('user.kapal-edit', $kapal->id_kapal) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil-square"></i> Edit Data
            </a>
        </div>

        <div class="row g-4 p-2">
            {{-- Kolom Kiri: Data Pemilik --}}
            <div class="col-md-6">
                <div class="card h-100 p-3 shadow-sm">
                    <h5 class="fw-bold mb-3">Data Pemilik</h5>
                    <hr class="mt-1 mb-3">

                    <div class="mb-2 border-bottom">
                        <label class="fw-semibold text-muted">Nama Pemilik :</label>
                        <p class="fs-8">{{ $kapal->nama_pemilik }}</p>
                    </div>

                    <div class="mb-2 border-bottom">
                        <label class="fw-semibold text-muted">NIK :</label>
                        <p class="fs-8">{{ $kapal->nik }}</p>
                    </div>

                    <div class="mb-2 border-bottom">
                        <label class="fw-semibold text-muted">Alamat :</label>
                        <p class="fs-8">{{ $kapal->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Data Kapal --}}
            <div class="col-md-6">
                <div class="card h-100 p-3 shadow-sm">
                    <h5 class="fw-bold mb-3">Data Kapal</h5>
                    <hr class="mt-1 mb-2">

                    <div class="mb-2 border-bottom">
                        <label class="fw-semibold text-muted">Nama Kapal :</label>
                        <p class="fs-8">{{ $kapal->nama_kapal }}</p>
                    </div>

                    <div class="mb-2 border-bottom">
                        <label class="fw-semibold text-muted">Tipe Kapal :</label>
                        <p class="fs-8">{{ $kapal->tipe_kapal ?? '-' }}</p>
                    </div>

                    <div class="mb-2 border-bottom">
                        <label class="fw-semibold text-muted">Kapasitas (Orang / Ton) :</label>
                        <p class="fs-8">{{ $kapal->kapasitas ?? '-' }}</p>
                    </div>

                    <div class="mb-2 border-bottom">
                        <label class="fw-semibold text-muted">Nomor Registrasi :</label>
                        <p class="fs-8">{{ $kapal->nomor_registrasi }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- FOTO KAPAL --}}
        <div class="row g-4 p-2">
            <div class="col-md-6">
                <div class="card shadow-sm p-3">
                    <h5 class="fw-bold mb-2">Foto Kapal</h5>
                    <hr class="mt-1 mb-2">

                    @if ($kapal->foto_url)
                        <img src="{{ $kapal->foto_url }}" alt="Foto Kapal" class="kapal-photo">
                        <div class="mt-2 text-center">
                            <a href="{{ $kapal->foto_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> Lihat Foto
                            </a>
                        </div>
                    @else
                        <p class="text-muted fst-italic text-center">Belum ada foto kapal yang diunggah.</p>
                    @endif
                </div>
            </div>

            {{-- DOKUMEN KAPAL --}}
            <div class="col-md-6">
                <div class="card shadow-sm p-3">
                    <h5 class="fw-bold mb-2">Dokumen Kapal</h5>
                    <hr class="mt-1 mb-2">

                    @if ($kapal->dokumen_url)
                        @php
                            $ext = strtolower(pathinfo($kapal->dokumen_url, PATHINFO_EXTENSION));
                            $pdfUrl = $kapal->dokumen_url;
                        @endphp

                        {{-- Preview PDF --}}
                        @if ($ext === 'pdf')
                            <div class="file-preview">
                                <iframe src="{{ $pdfUrl }}" type="application/pdf"
                                    style="width:100%; height:220px; border-radius:8px; border:none;" frameborder="0">
                                </iframe>
                            </div>

                        {{-- Preview Gambar --}}
                        @elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                            <img src="{{ $kapal->dokumen_url }}" class="kapal-photo" alt="Dokumen Kapal">

                        {{-- Dokumen Lain --}}
                        @else
                            <div class="file-preview d-flex align-items-center">
                                <i class="bi bi-file-earmark-text text-primary"></i>
                                <span class="ms-2">Dokumen: {{ strtoupper($ext) }}</span>
                            </div>
                        @endif

                        {{-- Tombol Lihat --}}
                        <div class="mt-2 text-center">
                            <a href="{{ $kapal->dokumen_url }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> Lihat Dokumen
                            </a>
                        </div>

                    @else
                        <p class="text-muted fst-italic text-center">Belum ada dokumen kapal yang diunggah.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIWAYAT PENGAJUAN IZIN --}}
        <div class="p-2 mt-4">

            @include('main.user.kapal.components.index-tabel-riwayat-pengajuan', [
                'riwayat_pengajuan' => $riwayat_pengajuan ?? collect(),
            ])
        </div>
    </div>



    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3 mx-2" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3 mx-2" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
@endsection
