@extends('layouts.app-user')

@section('content')
    <div class="container p-3">
        <div>
            <h5 class="fw-bold text-primary mb-0">
                <i class="bi bi-house-door me-1"></i>
                <span>DASHBOARD</span>
            </h5>
            <hr class="mt-1 mb-3">
        </div>

        <div class="row">

            {{-- Total Admin --}}
            <div class="col-md-3 col-6 mb-3">
                <div class="card shadow-sm border-0 rounded-3 text-center">
                    <div class="card-body">
                        <i class="bi bi-person-badge text-primary icon-dashboard mb-2"></i>
                        <h6 class="text-muted">Admin</h6>
                        <h3 class="fw-bold">{{ $totalAdmin }}</h3>
                    </div>
                </div>
            </div>

            {{-- Total User --}}
            <div class="col-md-3 col-6 mb-3">
                <div class="card shadow-sm border-0 rounded-3 text-center">
                    <div class="card-body">
                        <i class="bi bi-people text-success icon-dashboard mb-2"></i>
                        <h6 class="text-muted">User</h6>
                        <h3 class="fw-bold">{{ $totalUser }}</h3>
                    </div>
                </div>
            </div>
         
            {{-- Akses Hari Ini --}}
            <div class="col-md-3 col-6 mb-3">
                <div class="card shadow-sm border-0 rounded-3 text-center">
                    <div class="card-body">
                        <i class="bi bi-calendar-check text-primary icon-dashboard mb-2"></i>
                        <h6 class="text-muted">Akses Hari Ini</h6>
                        <h3 class="fw-bold text-primary">{{ $aksesHariIni }}</h3>
                    </div>
                </div>
            </div>
   {{-- Total Akses Sistem --}}
            <div class="col-md-3 col-6 mb-3">
                <div class="card shadow-sm border-0 rounded-3 text-center">
                    <div class="card-body">
                        <i class="bi bi-box-arrow-in-right text-info icon-dashboard mb-2"></i>
                        <h6 class="text-muted">Total Akses (Lifetime)</h6>
                        <h3 class="fw-bold text-info">{{ $totalAkses }}</h3>
                    </div>
                </div>
            </div>

            {{-- Total Pengajuan --}}
            <div class="col-md-3 col-6 mb-3">
                <div class="card shadow-sm border-0 rounded-3 text-center">
                    <div class="card-body">
                        <i class="bi bi-file-earmark-text text-dark icon-dashboard mb-2"></i>
                        <h6 class="text-muted">Total Pengajuan</h6>
                        <h3 class="fw-bold">{{ $totalPengajuan }}</h3>
                    </div>
                </div>
            </div>

            {{-- Disetujui --}}
            <div class="col-md-3 col-6 mb-3">
                <div class="card shadow-sm border-0 rounded-3 text-center">
                    <div class="card-body">
                        <i class="bi bi-check-circle text-success icon-dashboard mb-2"></i>
                        <h6 class="text-muted">Disetujui</h6>
                        <h3 class="fw-bold text-success">{{ $disetujui }}</h3>
                    </div>
                </div>
            </div>

            {{-- Ditolak --}}
            <div class="col-md-3 col-6 mb-3">
                <div class="card shadow-sm border-0 rounded-3 text-center">
                    <div class="card-body">
                        <i class="bi bi-x-circle text-danger icon-dashboard mb-2"></i>
                        <h6 class="text-muted">Ditolak</h6>
                        <h3 class="fw-bold text-danger">{{ $ditolak }}</h3>
                    </div>
                </div>
            </div>

            {{-- Menunggu --}}
            <div class="col-md-3 col-6 mb-3">
                <div class="card shadow-sm border-0 rounded-3 text-center">
                    <div class="card-body">
                        <i class="bi bi-hourglass-split text-warning icon-dashboard mb-2"></i>
                        <h6 class="text-muted">Menunggu</h6>
                        <h3 class="fw-bold text-warning">{{ $menunggu }}</h3>
                    </div>
                </div>
            </div>



        </div>

        {{-- Grafik Pengajuan Harian --}}
        <div class="card shadow-sm border-0 rounded-3 mt-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Pengajuan 7 Hari Terakhir</h6>
                <canvas id="chartPengajuan"></canvas>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Chart Pengajuan
        const ctxPengajuan = document.getElementById('chartPengajuan').getContext('2d');
        const chartPengajuan = new Chart(ctxPengajuan, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($grafikPengajuan, 'tanggal')) !!},
                datasets: [{
                    label: 'Jumlah Pengajuan',
                    data: {!! json_encode(array_column($grafikPengajuan, 'jumlah')) !!},
                    fill: true,
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, precision: 0 }
                }
            }
        });
    </script>
@endpush