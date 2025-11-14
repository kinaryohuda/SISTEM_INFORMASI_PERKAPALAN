@extends('layouts.app-user')

@section('title', $title)

@section('content')

    <div class="container p-2">

           <div>
            <h5 class="fw-bold text-primary mb-0">
               <i class="bi bi-file-earmark-text me-1"></i>
                <span>PENGAJUAN PERMOHONAN</span>
            </h5>
            <hr class="mt-1 mb-3">
        </div>
        <div class="card shadow border-0 rounded-4">
            <div class="card-body">
                <h5 class="">Form Pengajuan Permohonan Super Admin</h5>
                {{-- Notifikasi --}}
                @if (session('success'))
                    <div class="alert alert-success rounded text-center">{{ session('success') }}</div>
                @elseif (session('error'))
                    <div class="alert alert-danger rounded text-center">{{ session('error') }}</div>
                @endif

                <form id="pengajuanForm" method="POST" action="{{ route('superAdmin.pengajuan-permohonan-store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- ======================== PILIH USER ======================== --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pilih User <span class="text-danger">*</span></label>
                        <select name="id_user" id="id_user" class="form-select @error('id_user') is-invalid @enderror"
                            required>

                            <option value="" disabled selected>Pilih user</option>

                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach

                            <option value="lainnya">Lainnya (Buat User Baru)</option>
                        </select>
                        @error('id_user')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ======================== FORM USER BARU ======================== --}}
                    <div id="formUserBaru" class="p-3 rounded border bg-light mb-4 d-none">
                        <h5 class="fw-bold text-primary mb-3">Tambah User Baru</h5>

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">Nama
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="nama_user" id="nama_user" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">NIK
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="nik_user" id="nik_user" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">No HP
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="no_hp_user" id="no_hp_user" class="form-control">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Alamat
                                    <span class="text-danger">*</span></label>
                                <textarea name="alamat_user" id="alamat_user" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Email
                                    <span class="text-danger">*</span></label>
                                <input type="email" name="email_user" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Password
                                    <span class="text-danger">*</span></label>
                                <input type="password" name="password_user" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Role User</label>
                                <input type="text" name="role_user" value="user" class="form-control" readonly>
                            </div>

                        </div>
                    </div>

                    {{-- ======================== PILIH KAPAL ======================== --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pilih Kapal
                            <span class="text-danger">*</span></label>
                        <select name="id_kapal" id="id_kapal" class="form-select @error('id_kapal') is-invalid @enderror"
                            required>

                            <option value="" disabled selected>Pilih kapal</option>

                            {{-- Akan di-load via JS --}}
                        </select>
                        @error('id_kapal')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ======================== FORM KAPAL BARU ======================== --}}
                    <div id="formKapalBaru" class="p-3 rounded border bg-light mb-4 d-none">
                        <h5 class="fw-bold text-primary mb-3">Tambah Kapal Baru</h5>

                        <div class="row g-3">

                            {{-- ======================== CHECKBOX SALIN DATA USER ======================== --}}
                            <div class="col-md-12 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="copyUserData">
                                    <label class="form-check-label fw-semibold" for="copyUserData">
                                        Sama dengan data User (Nama, NIK, Alamat)
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama Pemilik
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">NIK
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="nik" id="nik" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama Kapal
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kapal" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tipe Kapal
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="tipe_kapal" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kapasitas (GT)
                                    <span class="text-danger">*</span></label>
                                <input type="number" name="kapasitas" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nomor Registrasi
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_registrasi" class="form-control">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Alamat
                                    <span class="text-danger">*</span></label>
                                <textarea name="alamat" id="alamat_kapal" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Foto Kapal
                                    <span class="text-danger">*</span></label>
                                <input type="file" name="foto_kapal" id="foto_kapal"
                                    accept="image" class="form-control">
                                <img id="previewFoto" class="mt-2 d-none rounded" style="max-width: 15%;">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Dokumen Kapal (PDF)
                                    <span class="text-danger">*</span></label>
                                <input type="file" name="dokumen_kapal" id="dokumen_kapal" accept="application/pdf"
                                    class="form-control">

                                <div id="previewDokumen" class="mt-2 d-none">
                                    <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                                    <span id="dokumenFileName" class="ms-2 fw-semibold"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ======================== FORM KOMPONEN PENGAJUAN ======================== --}}
                    <div class="mb-4">
                        <h5 class="fw-bold text-primary mb-3">Data Komponen Pengajuan</h5>

                        @foreach ($komponen_pengajuan as $item)
                            <div class="card shadow-sm border-0 rounded-4 mb-3">
                                <div class="card-body">
                                    <label class="form-label fw-semibold">
                                        {{ $item->nama_komponen }}
                                        @if ($item->is_required)
                                            <span class="text-danger">
                                                <span class="text-danger">*</span></span>
                                        @endif
                                    </label>

                                    @switch($item->tipe)
                                        @case('text')
                                            <input type="text" name="komponen[{{ $item->id_komponen_pengajuan }}]"
                                                class="form-control">
                                        @break

                                        @case('textarea')
                                            <textarea name="komponen[{{ $item->id_komponen_pengajuan }}]" class="form-control"></textarea>
                                        @break

                                        @case('select')
                                            <select name="komponen[{{ $item->id_komponen_pengajuan }}]" class="form-select">
                                                <option disabled selected>Pilih salah satu</option>
                                                @foreach ($item->opsi as $ops)
                                                    <option value="{{ $ops }}">{{ $ops }}</option>
                                                @endforeach
                                            </select>
                                        @break

                                        @case('radio')
                                            @foreach ($item->opsi as $ops)
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input"
                                                        name="komponen[{{ $item->id_komponen_pengajuan }}]"
                                                        value="{{ $ops }}">
                                                    <label>{{ $ops }}</label>
                                                </div>
                                            @endforeach
                                        @break

                                        @case('file')
                                            <input type="file" name="komponen[{{ $item->id_komponen_pengajuan }}]"
                                                class="form-control">
                                        @break

                                        @case('date')
                                            <input type="date" name="komponen[{{ $item->id_komponen_pengajuan }}]"
                                                class="form-control" value="{{ date('Y-m-d') }}">
                                        @break
                                    @endswitch
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary px-4 py-2" type="submit">
                            Simpan Pengajuan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        const idUser = document.getElementById("id_user");
        const idKapal = document.getElementById("id_kapal");
        const formUserBaru = document.getElementById("formUserBaru");
        const formKapalBaru = document.getElementById("formKapalBaru");

        const copyUserDataCheckbox = document.getElementById('copyUserData');
        const namaUserInput = document.getElementById('nama_user');
        const nikUserInput = document.getElementById('nik_user');
        const alamatUserInput = document.getElementById('alamat_user');

        const namaPemilikInput = document.getElementById('nama_pemilik');
        const nikPemilikInput = document.getElementById('nik');
        const alamatPemilikInput = document.getElementById('alamat_kapal');

        idUser.addEventListener("change", function() {
            formUserBaru.classList.toggle("d-none", idUser.value !== "lainnya");

            if (idUser.value !== "lainnya") {
                loadKapal(idUser.value);
            } else {
                idKapal.innerHTML = `<option value="" disabled selected>Pilih Kapal</option>
                                 <option value="lainnya">Lainnya (Tambah Kapal Baru)</option>`;
            }
            clearCopyCheckbox();
        });

        idKapal.addEventListener("change", function() {
            formKapalBaru.classList.toggle("d-none", idKapal.value !== "lainnya");
            clearCopyCheckbox();
        });

        // =================== COPY USER DATA KE KAPAL ===================
        copyUserDataCheckbox.addEventListener('change', function() {
            if (copyUserDataCheckbox.checked) {
                if (idUser.value !== 'lainnya') {
                    fetch(`/superAdmin/user-data/${idUser.value}`)
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                const data = res.data;
                                namaPemilikInput.value = data.nama;
                                nikPemilikInput.value = data.nik;
                                alamatPemilikInput.value = data.alamat;
                            }
                        })
                        .catch(err => console.error(err));
                } else {
                    // Ambil dari form user baru
                    namaPemilikInput.value = namaUserInput.value;
                    nikPemilikInput.value = nikUserInput.value;
                    alamatPemilikInput.value = alamatUserInput.value;
                }
            } else {
                namaPemilikInput.value = '';
                nikPemilikInput.value = '';
                alamatPemilikInput.value = '';
            }
        });

        // Update otomatis jika user baru diketik dan checkbox dicentang
        [namaUserInput, nikUserInput, alamatUserInput].forEach(input => {
            input.addEventListener('input', function() {
                if (copyUserDataCheckbox.checked) {
                    namaPemilikInput.value = namaUserInput.value;
                    nikPemilikInput.value = nikUserInput.value;
                    alamatPemilikInput.value = alamatUserInput.value;
                }
            });
        });

        function clearCopyCheckbox() {
            copyUserDataCheckbox.checked = false;
            namaPemilikInput.value = '';
            nikPemilikInput.value = '';
            alamatPemilikInput.value = '';
        }

        // =================== AJAX Load Kapal ===================
        function loadKapal(userId) {
            fetch(`/superAdmin/pengajuan-permohonan/load-kapal/${userId}`)
                .then(res => res.json())
                .then(result => {
                    const kapalList = Array.isArray(result.data) ? result.data : [];

                    idKapal.innerHTML =
                        `<option disabled selected>Pilih Kapal</option>` +
                        kapalList.map(k =>
                            `<option value="${k.id_kapal}">
                        ${k.nama_kapal} (${k.nomor_registrasi})
                    </option>`
                        ).join('') +
                        `<option value="lainnya">Lainnya (Tambah Kapal Baru)</option>`;
                })
                .catch(err => console.error("Error load kapal:", err));
        }
    </script>

@endsection
