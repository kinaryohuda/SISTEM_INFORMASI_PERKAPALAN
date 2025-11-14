@extends('layouts.app-user')

@section('title', 'Edit Pengguna User')

@section('content')
<div class="container p-3">

    <h4 class="fw-bold mb-3">Edit Pengguna (User)</h4>

    <form action="{{ route('pengguna-user-update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name) }}">
        </div>

        <div class="mb-3">
            <label>NIK</label>
            <input type="text" name="nik" class="form-control" required value="{{ old('nik', $user->nik) }}">
        </div>

        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control" required value="{{ old('no_hp', $user->no_hp) }}">
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required>{{ old('alamat', $user->alamat) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}">
        </div>

        <div class="mb-3">
            <label>Password (Opsional)</label>
            <input type="password" name="password" class="form-control">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
        </div>

        <button class="btn btn-primary">Perbarui</button>
        <a href="{{ route('superAdmin.pengguna-user-index') }}" class="btn btn-secondary">Kembali</a>
    </form>

</div>
@endsection
