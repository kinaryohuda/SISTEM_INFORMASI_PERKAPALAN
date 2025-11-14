@extends('layouts.app-user')

@section('title', 'Edit Admin')

@section('content')
<div class="container p-3">

    <h4 class="fw-bold mb-3 text-primary">Edit Admin</h4>

    <form action="{{ route('superAdmin.pengguna-admin-update', $admin->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $admin->name) }}">
        </div>

        <div class="mb-3">
            <label>NIK</label>
            <input type="text" name="nik" class="form-control" value="{{ old('nik', $admin->nik) }}">
        </div>

        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $admin->no_hp) }}">
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control">{{ old('alamat', $admin->alamat) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email', $admin->email) }}">
        </div>

        <div class="mb-3">
            <label>Password (Opsional)</label>
            <input type="password" name="password" class="form-control">
            <small class="text-muted">Kosongkan jika tidak ingin mengganti password.</small>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('superAdmin.pengguna-admin-index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button class="btn btn-primary">Perbarui</button>
        </div>

    </form>

</div>
@endsection
