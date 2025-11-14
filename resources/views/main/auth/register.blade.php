@extends('layouts.auth-app')

@section('title', 'Register')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <!-- Icon kotak yang bisa diklik -->
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('assets/icons/Icons_sistem_infomasi_perkapalan.png') }}" alt="Logo"
                                class="mb-3 square-icon">
                        </a>

                        <!-- Judul Daftar Akun -->
                        <h3 class="card-title mb-4" style="font-family: 'Montserrat', sans-serif;">Daftar Akun</h3>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3 text-start">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autofocus>
                            </div>

                            <div class="mb-3 text-start">
                                <label for="nik" class="form-label">NIK</label>
                                <input id="nik" type="text" class="form-control @error('nik') is-invalid @enderror"
                                    name="nik" value="{{ old('nik') }}" required>
                            </div>

                            <div class="mb-3 text-start">
                                <label for="phone" class="form-label">Nomor HP</label>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" value="{{ old('phone') }}" required>
                            </div>

                            <div class="mb-3 text-start">
                                <label for="address" class="form-label">Alamat</label>
                                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror"
                                    name="address" value="{{ old('address') }}" required>
                            </div>

                            <div class="mb-3 text-start">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3 text-start">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required>
                            </div>

                            <div class="mb-3 text-start">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input id="password_confirmation" type="password" class="form-control"
                                    name="password_confirmation" required>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-success fw-bold">Daftar Sekarang</button>
                            </div>

                            <p class="mb-2">
                                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                            </p>
                            <p class="mb-2">
                                Kembali ke home ? <a href="{{ route('landing') }}">klik di sini</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CSS untuk icon kotak -->
    <style>
        .square-icon {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            border: 0.1rem solid #959697;
            object-fit: cover;
        }
    </style>
@endsection