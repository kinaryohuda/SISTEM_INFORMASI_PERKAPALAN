@extends('layouts.app-user')

@section('content')

<style>
    .icon-dashboard{
        font-size: 1rem;
    }
</style>
<div class="container p-2">

    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
        <h4 class="fw-bold">DASHBOARD</h4>
    </div>

    <div class="row">

        {{-- CARD: Jumlah Kapal --}}
        <div class="col-md-3 col-6 mb-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <div class="mb-2">
                          <i class="fa-solid fa-ship text-primary icon-dashboard"></i>
                    </div>
                    <h6 class="text-muted mb-1">Jumlah Kapal</h6>
                    <h3 class="fw-bold text-primary">{{ $jumlahKapal }}</h3>
                </div>
            </div>
        </div>

        {{-- CARD: Total Pengajuan --}}
        <div class="col-md-3 col-6 mb-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-file-earmark-text text-dark icon-dashboard"></i>
                    </div>
                    <h6 class="text-muted mb-1">Total Pengajuan</h6>
                    <h3 class="fw-bold text-dark">{{ $jumlahPengajuan }}</h3>
                </div>
            </div>
        </div>

        {{-- CARD: Pengajuan Disetujui --}}
        <div class="col-md-3 col-6 mb-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-check-circle text-success icon-dashboard"></i>
                    </div>
                    <h6 class="text-muted mb-1">Disetujui</h6>
                    <h3 class="fw-bold text-success">{{ $jumlahDisetujui }}</h3>
                </div>
            </div>
        </div>

        {{-- CARD: Pengajuan Ditolak --}}
        <div class="col-md-3 col-6 mb-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-x-circle icon-dashboard text-danger"></i>
                    </div>
                    <h6 class="text-muted mb-1">Ditolak</h6>
                    <h3 class="fw-bold text-danger">{{ $jumlahDitolak }}</h3>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
