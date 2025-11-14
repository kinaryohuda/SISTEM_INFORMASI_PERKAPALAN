@extends('layouts.app-user')

@section('title', 'Tambah Komponen Pengajuan')

@section('content')
    <div class="container p-2">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h4 class="fw-bold">TAMBAH KOMPONEN PENGAJUAN</h4>
        </div>

        <div class="card shadow border-0 rounded-4">
            <div class="card-body">
                <form id="komponenForm" method="POST" action="{{ route('superAdmin.komponen-pengajuan-store') }}">
                    @csrf

                    {{-- Nama Komponen --}}
                    <div class="mb-3">
                        <label for="nama_komponen" class="form-label fw-semibold">Nama Komponen</label>
                        <input type="text" class="form-control @error('nama_komponen') is-invalid @enderror"
                            id="nama_komponen" name="nama_komponen" placeholder="Masukkan nama komponen (contoh: Pelabuhan Tujuan)"
                            value="{{ old('nama_komponen') }}">
                        @error('nama_komponen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Nama yang akan muncul di form pengajuan user.</small>
                    </div>

                    {{-- Tipe Input --}}
                    <div class="mb-3">
                        <label for="tipe" class="form-label fw-semibold">Tipe Input</label>
                        <select id="tipe" name="tipe" class="form-select @error('tipe') is-invalid @enderror"
                            onchange="toggleOpsiField()">
                            <option value="" disabled selected>Pilih tipe input</option>
                            <option value="text" {{ old('tipe') == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="textarea" {{ old('tipe') == 'textarea' ? 'selected' : '' }}>Textarea</option>
                            <option value="select" {{ old('tipe') == 'select' ? 'selected' : '' }}>Select</option>
                            <option value="radio" {{ old('tipe') == 'radio' ? 'selected' : '' }}>Radio</option>
                            <option value="file" {{ old('tipe') == 'file' ? 'selected' : '' }}>File</option>
                            <option value="date" {{ old('tipe') == 'date' ? 'selected' : '' }}>Date</option>
                        </select>
                        @error('tipe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Pilih jenis input yang akan digunakan user.</small>
                    </div>

                    {{-- Opsi Pilihan --}}
                    <div class="mb-3" id="opsiField" style="display:none;">
                        <label class="form-label fw-semibold">Daftar Opsi Pilihan</label>
                        <div id="opsiContainer">
                            @if(old('opsi'))
                                @foreach(old('opsi') as $index => $opsi)
                                    <div class="input-group mb-2">
                                        <input type="text" name="opsi[]" class="form-control"
                                            value="{{ $opsi }}" placeholder="Masukkan opsi">
                                        @if($loop->last)
                                            <button type="button" class="btn btn-outline-success" onclick="addOpsi()">
                                                <i class="bi bi-plus-circle"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2">
                                    <input type="text" name="opsi[]" class="form-control" placeholder="Masukkan opsi (contoh: Barang)">
                                    <button type="button" class="btn btn-outline-success" onclick="addOpsi()">
                                        <i class="bi bi-plus-circle"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <small class="text-muted">Masukkan satu atau lebih pilihan yang akan ditampilkan ke user.</small>
                    </div>

                    {{-- Kewajiban --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kewajiban</label>
                        <select name="is_required" class="form-select @error('is_required') is-invalid @enderror">
                            <option value="" disabled selected>Pilih kewajiban</option>
                            <option value="1" {{ old('is_required') == '1' ? 'selected' : '' }}>Wajib diisi oleh user</option>
                            <option value="0" {{ old('is_required') == '0' ? 'selected' : '' }}>Opsional</option>
                        </select>
                        @error('is_required')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Pilih apakah komponen ini wajib diisi oleh pengguna.</small>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Komponen</label>
                        <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                            <option value="" disabled selected>Pilih status</option>
                            <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Komponen nonaktif tidak akan muncul di form pengguna.</small>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('superAdmin.komponen-pengajuan-index') }}"
                            class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <span class="btn-text"><i class="bi bi-save"></i> Simpan Komponen</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        function toggleOpsiField() {
            const tipe = document.getElementById('tipe').value;
            const opsiField = document.getElementById('opsiField');
            opsiField.style.display = (tipe === 'select' || tipe === 'radio') ? 'block' : 'none';
        }

        function addOpsi() {
            const container = document.getElementById('opsiContainer');
            const div = document.createElement('div');
            div.classList.add('input-group', 'mb-2');
            div.innerHTML = `
                <input type="text" name="opsi[]" class="form-control" placeholder="Masukkan opsi tambahan">
                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                    <i class="bi bi-x-circle"></i>
                </button>`;
            container.appendChild(div);

            // pastikan hanya baris terakhir yg pakai tombol tambah (+)
            const groups = container.querySelectorAll('.input-group');
            groups.forEach((group, index) => {
                const btn = group.querySelector('button');
                if (index === groups.length - 1) {
                    btn.className = 'btn btn-outline-success';
                    btn.innerHTML = '<i class="bi bi-plus-circle"></i>';
                    btn.setAttribute('onclick', 'addOpsi()');
                } else {
                    btn.className = 'btn btn-outline-danger';
                    btn.innerHTML = '<i class="bi bi-x-circle"></i>';
                    btn.setAttribute('onclick', 'this.parentElement.remove()');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            toggleOpsiField();

            const form = document.getElementById('komponenForm');
            const submitBtn = document.getElementById('submitBtn');
            const spinner = submitBtn.querySelector('.spinner-border');
            const btnText = submitBtn.querySelector('.btn-text');

            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                spinner.classList.remove('d-none');
                btnText.textContent = " Mengirim...";
            });
        });
    </script>

    {{-- STYLE --}}
    <style>
        .form-label {
            font-size: 0.95rem;
        }
        small.text-muted {
            font-size: 0.8rem;
        }
        .btn i {
            margin-right: 4px;
        }
        .invalid-feedback {
            display: block;
            font-size: 0.85rem;
            margin-top: 4px;
        }
    </style>
@endsection
