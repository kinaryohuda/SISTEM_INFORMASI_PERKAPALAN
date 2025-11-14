@extends('layouts.app-user')

@section('title', 'Tambah Admin')

@section('content')
<div class="container p-3">

    <h4 class="fw-bold mb-3 text-primary">Tambah Admin</h4>

    <form action="{{ route('superAdmin.pengguna-admin-store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label>NIK</label>
            <input type="text" name="nik" class="form-control" value="{{ old('nik') }}">
        </div>

        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control">{{ old('alamat') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('superAdmin.pengguna-admin-index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button class="btn btn-primary">Simpan</button>
        </div>

    </form>

</div>
@endsection
