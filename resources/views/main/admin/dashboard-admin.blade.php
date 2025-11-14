@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body text-center p-5">
            <h1 class="fw-bold mb-3" style="font-family: 'Montserrat', sans-serif; color:#003366;">
                Dashboard Admin ⚓
            </h1>
            <p class="lead" style="font-family: 'Poppins', sans-serif;">
                Hai, {{ auth()->user()->name }} — Anda masuk sebagai <strong>Admin</strong>.
            </p>
            <hr class="my-4">
            <p style="font-family: 'Poppins', sans-serif;">
                Di sini Anda dapat mengelola data pengguna, data kapal, laporan pelayaran, dan konfigurasi lainnya.
            </p>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="#" class="btn btn-outline-primary px-4 rounded-pill">Kelola Pengguna</a>
                <a href="#" class="btn btn-outline-primary px-4 rounded-pill">Data Kapal</a>
                <a href="#" class="btn btn-outline-primary px-4 rounded-pill">Laporan</a>
            </div>

            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
               class="btn btn-primary mt-5 px-4 py-2 rounded-pill">
                Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>
@endsection
