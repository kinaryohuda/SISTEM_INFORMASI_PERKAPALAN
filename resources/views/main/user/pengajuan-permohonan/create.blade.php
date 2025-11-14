@extends('layouts.app-user')

@section('title', 'Pengajuan Permohonan')

@section('content')
    <div class="container p-2">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h4 class="fw-bold">FORM PENGAJUAN PERMOHONAN</h4>
        </div>

        <div class="card shadow border-0 rounded-4">
            <div class="card-body">
                {{-- Notifikasi --}}
                @if (session('success'))
                    <div class="alert alert-success rounded text-center">
                        {{ session('success') }}
                    </div>
                @elseif (session('error'))
                    <div class="alert alert-danger rounded text-center">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Form Pengajuan --}}
                <form id="pengajuanForm" method="POST" action="{{ route('user.pengajuan-permohonan-store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- === PILIH KAPAL === --}}
                    <div class="mb-4">
                        <label for="id_kapal" class="form-label fw-semibold">Pilih Kapal</label>
                        <select name="id_kapal" id="id_kapal" class="form-select @error('id_kapal') is-invalid @enderror"
                            required>
                            <option value="" disabled {{ old('id_kapal') ? '' : 'selected' }}>Pilih kapal yang akan
                                diajukan</option>
                            @foreach ($kapal as $k)
                                <option value="{{ $k->id_kapal }}" {{ old('id_kapal') == $k->id_kapal ? 'selected' : '' }}>
                                    {{ $k->nama_kapal }} ({{ $k->nomor_registrasi }})
                                </option>
                            @endforeach
                            <option value="lainnya" {{ old('id_kapal') == 'lainnya' ? 'selected' : '' }}>Lainnya (Tambahkan
                                Kapal Baru)</option>
                        </select>
                        @error('id_kapal')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- === FORM KAPAL BARU (HIDDEN) === --}}
                    <div id="kapalBaruForm"
                        class="p-3 rounded border bg-light mb-4 {{ old('id_kapal') == 'lainnya' ? '' : 'd-none' }}">
                        <h5 class="fw-bold mb-3 text-primary">Data Kapal Baru</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pemilik"
                                    value="{{ old('nama_pemilik', Auth::user()->name ?? '') }}"
                                    class="form-control @error('nama_pemilik') is-invalid @enderror"
                                    placeholder="Masukkan nama pemilik kapal">
                                @error('nama_pemilik')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">NIK <span class="text-danger">*</span></label>
                                <input type="text" name="nik" value="{{ old('nik', Auth::user()->nik ?? '') }}"
                                    class="form-control @error('nik') is-invalid @enderror"
                                    placeholder="Masukkan NIK pemilik kapal">
                                @error('nik')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama Kapal <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kapal" value="{{ old('nama_kapal') }}"
                                    class="form-control @error('nama_kapal') is-invalid @enderror"
                                    placeholder="Masukkan nama kapal">
                                @error('nama_kapal')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tipe Kapal <span class="text-danger">*</span></label>
                                <input type="text" name="tipe_kapal" value="{{ old('tipe_kapal') }}"
                                    class="form-control @error('tipe_kapal') is-invalid @enderror"
                                    placeholder="Contoh: Motor Nelayan">
                                @error('tipe_kapal')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kapasitas (GT) <span class="text-danger">*</span></label>
                                <input type="number" name="kapasitas" value="{{ old('kapasitas') }}"
                                    class="form-control @error('kapasitas') is-invalid @enderror"
                                    placeholder="Masukkan kapasitas kapal">
                                @error('kapasitas')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nomor Registrasi <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_registrasi" value="{{ old('nomor_registrasi') }}"
                                    class="form-control @error('nomor_registrasi') is-invalid @enderror"
                                    placeholder="Masukkan nomor registrasi kapal">
                                @error('nomor_registrasi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2"
                                    placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- FOTO & DOKUMEN --}}
                            <div class="col-md-6">
                                <label class="form-label">Foto Kapal <span class="text-danger">*</span></label>
                                <input type="file" name="foto_kapal" id="foto_kapal" accept="image/*"
                                    class="form-control">
                                <div class="mt-2">
                                    <img id="previewFoto" src="#" alt="Preview Foto"
                                        style="max-width: 10%; height: auto; display: none; border-radius: 8px;">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Dokumen Kapal (PDF) <span class="text-danger">*</span></label>
                                <input type="file" name="dokumen_kapal" id="dokumen_kapal" accept="application/pdf"
                                    class="form-control">
                                <div class="file-preview mt-2" id="previewDokumen" style="display:none;">
                                    <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                                    <span id="dokumenFileName" class="fw-semibold ms-1"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- === FORM KOMPONEN PENGAJUAN === --}}
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3 text-primary">Data Komponen Pengajuan</h5>

                        @foreach ($komponen_pengajuan as $item)
                            @if ($item->is_active)
                                <div class="card shadow-sm border-0 rounded-4 mb-3">
                                    <div class="card-body">
                                        <label class="form-label fw-semibold">
                                            {{ $item->nama_komponen }}
                                            @if ($item->is_required)
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>

                                        @switch($item->tipe)
                                            @case('text')
                                                <input type="text" name="komponen[{{ $item->id_komponen_pengajuan }}]"
                                                    value="{{ old('komponen.' . $item->id_komponen_pengajuan) }}"
                                                    class="form-control @error('komponen.' . $item->id_komponen_pengajuan) is-invalid @enderror"
                                                    placeholder="Masukkan {{ strtolower($item->nama_komponen) }}">
                                            @break

                                            @case('textarea')
                                                <textarea name="komponen[{{ $item->id_komponen_pengajuan }}]" rows="3"
                                                    class="form-control @error('komponen.' . $item->id_komponen_pengajuan) is-invalid @enderror"
                                                    placeholder="Masukkan {{ strtolower($item->nama_komponen) }}">{{ old('komponen.' . $item->id_komponen_pengajuan) }}</textarea>
                                            @break

                                            @case('select')
                                                <select name="komponen[{{ $item->id_komponen_pengajuan }}]"
                                                    class="form-select @error('komponen.' . $item->id_komponen_pengajuan) is-invalid @enderror">
                                                    <option value="" disabled selected>Pilih salah satu</option>
                                                    @foreach ($item->opsi ?? [] as $ops)
                                                        <option value="{{ $ops }}"
                                                            {{ old('komponen.' . $item->id_komponen_pengajuan) == $ops ? 'selected' : '' }}>
                                                            {{ $ops }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @break

                                            @case('radio')
                                                @foreach ($item->opsi ?? [] as $ops)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="komponen[{{ $item->id_komponen_pengajuan }}]"
                                                            value="{{ $ops }}"
                                                            {{ old('komponen.' . $item->id_komponen_pengajuan) == $ops ? 'checked' : '' }}>
                                                        <label class="form-check-label">{{ $ops }}</label>
                                                    </div>
                                                @endforeach
                                            @break

                                            @case('file')
                                                <input type="file" name="komponen[{{ $item->id_komponen_pengajuan }}]"
                                                    class="form-control @error('komponen.' . $item->id_komponen_pengajuan) is-invalid @enderror">
                                            @break

                                            @case('date')
                                                <input type="date" name="komponen[{{ $item->id_komponen_pengajuan }}]"
                                                    class="form-control @error('komponen.' . $item->id_komponen_pengajuan) is-invalid @enderror"
                                                    value="{{ old('komponen.' . $item->id_komponen_pengajuan, date('Y-m-d')) }}">
                                            @break

                                            @default
                                                <input type="text" name="komponen[{{ $item->id_komponen_pengajuan }}]"
                                                    class="form-control @error('komponen.' . $item->id_komponen_pengajuan) is-invalid @enderror"
                                                    placeholder="Masukkan nilai"
                                                    value="{{ old('komponen.' . $item->id_komponen_pengajuan) }}">
                                        @endswitch

                                        {{-- Tampilkan Error --}}
                                        @error('komponen.' . $item->id_komponen_pengajuan)
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- === TOMBOL KIRIM === --}}
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" id="submitBtn" class="btn btn-primary mx-2">
                            <span class="btn-text">Kirim Pengajuan</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const selectKapal = document.getElementById('id_kapal');
            const kapalBaruForm = document.getElementById('kapalBaruForm');

            const fotoInput = document.getElementById('foto_kapal');
            const dokumenInput = document.getElementById('dokumen_kapal');

            // === FIX: Aktifkan required jika pilih "lainnya" ===
            function toggleRequired() {
                const isNew = selectKapal.value === 'lainnya';

                kapalBaruForm.classList.toggle('d-none', !isNew);

                fotoInput.required = isNew;
                dokumenInput.required = isNew;
            }

            toggleRequired();
            selectKapal.addEventListener('change', toggleRequired);

            // === PREVIEW GAMBAR ===
            const previewFoto = document.getElementById('previewFoto');
            fotoInput?.addEventListener('change', e => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = ev => {
                        previewFoto.src = ev.target.result;
                        previewFoto.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else previewFoto.style.display = 'none';
            });

            // === PREVIEW PDF ===
            const previewDokumen = document.getElementById('previewDokumen');
            const dokumenFileName = document.getElementById('dokumenFileName');

            dokumenInput?.addEventListener('change', e => {
                const file = e.target.files[0];

                if (!file) {
                    previewDokumen.style.display = 'none';
                    return;
                }

                if (file.type !== "application/pdf") {
                    dokumenInput.value = '';
                    previewDokumen.style.display = 'none';
                    Swal.fire({
                        icon: 'error',
                        title: 'Format Tidak Valid',
                        text: 'Dokumen kapal hanya boleh PDF'
                    });
                    return;
                }

                dokumenFileName.textContent = file.name;
                previewDokumen.style.display = 'flex';
            });

            // === DISABLE BUTTON SAAT SUBMIT ===
            const form = document.getElementById('pengajuanForm');
            const submitBtn = document.getElementById('submitBtn');
            const spinner = submitBtn.querySelector('.spinner-border');
            const btnText = submitBtn.querySelector('.btn-text');

            form.addEventListener('submit', () => {
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

        .form-control,
        .form-select {
            border-radius: 0.6rem;
        }

        .btn i {
            margin-right: 4px;
        }

        .card-body {
            background-color: #fafafa;
        }

        .card {
            transition: all 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
