@extends('layouts.auth-app')

@section('title', 'Login')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <!-- Icon kotak (siku tajam) -->
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('assets/icons/Icons_sistem_infomasi_perkapalan.png') }}" alt="Logo"
                                class="mb-3 square-icon">
                        </a>

                        <!-- Judul Login -->
                        <h3 class="card-title mb-4" style="font-family: 'Montserrat', sans-serif;">Login</h3>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3 text-start">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autofocus>
                            </div>

                            <div class="mb-3 text-start">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required>
                            </div>

                            <div class="mb-3 form-check text-start">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary fw-bold">Masuk Sekarang</button>
                            </div>

                            <p class="mb-2">
                                <a href="{{ route('password.request') }}">Lupa Password</a>
                            </p>
                            <p class="mb-2">
                                Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
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