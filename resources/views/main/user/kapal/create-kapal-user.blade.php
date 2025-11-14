@extends('layouts.app-user')

@section('title', 'Tambah Kapal Baru')

@section('content')
<div class="container p-2">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
        <h4 class="fw-bold">TAMBAH DATA KAPAL</h4>
    </div>

    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <h6 class="text-primary">Tambah Kapal Baru :</h6>

            <form id="formKapal" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- DATA PEMILIK --}}
                <h6 class="fw-bold text-secondary mt-2">Data Pemilik</h6>
                <hr class="mt-1 mb-3">

                <div class="mb-3">
                    <label for="nama_pemilik" class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                    <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control"
                        value="{{ old('nama_pemilik', Auth::user()->name) }}" placeholder="Masukkan nama pemilik" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                    <input type="text" name="nik" id="nik" class="form-control"
                        value="{{ old('nik', Auth::user()->nik) }}" placeholder="Masukkan NIK" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea name="alamat" id="alamat" class="form-control"
                        rows="3" placeholder="Masukkan alamat lengkap" required>{{ old('alamat', Auth::user()->alamat) }}</textarea>
                    <div class="invalid-feedback"></div>
                </div>

                {{-- DATA KAPAL --}}
                <h6 class="fw-bold text-secondary mt-4">Data Kapal</h6>
                <hr class="mt-1 mb-3">

                <div class="mb-3">
                    <label for="nama_kapal" class="form-label">Nama Kapal <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kapal" id="nama_kapal" class="form-control"
                        value="{{ old('nama_kapal') }}" placeholder="Masukkan nama kapal" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="tipe_kapal" class="form-label">Tipe Kapal <span class="text-danger">*</span></label>
                    <input type="text" name="tipe_kapal" id="tipe_kapal" class="form-control"
                        value="{{ old('tipe_kapal') }}" placeholder="Masukkan tipe kapal" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="kapasitas" class="form-label">Kapasitas (Orang / Ton) <span class="text-danger">*</span></label>
                    <input type="number" name="kapasitas" id="kapasitas" class="form-control"
                        value="{{ old('kapasitas') }}" placeholder="Masukkan kapasitas" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="nomor_registrasi" class="form-label">Nomor Registrasi <span class="text-danger">*</span></label>
                    <input type="text" name="nomor_registrasi" id="nomor_registrasi" class="form-control"
                        value="{{ old('nomor_registrasi') }}" placeholder="Masukkan nomor registrasi" required>
                    <div class="invalid-feedback"></div>
                </div>

                {{-- FILE FOTO & DOKUMEN --}}
                <h6 class="fw-bold text-secondary mt-4">File Pendukung Kapal</h6>
                <hr class="mt-1 mb-3">

                <div class="mb-3">
                    <label for="foto_kapal" class="form-label">Foto Kapal <span class="text-danger">*</span></label>
                    <input type="file" name="foto_kapal" id="foto_kapal" accept="image/*" class="form-control" required>
                    <div class="invalid-feedback"></div>
                    <div class="mt-2">
                        <img id="previewFoto" src="#" alt="Preview Foto"
                            style="max-width: 10%; height: auto; display: none; border-radius: 8px;">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="dokumen_kapal" class="form-label">Dokumen Kapal <span class="text-danger">*</span></label>
                    <input type="file" name="dokumen_kapal" id="dokumen_kapal"
                        accept="application/pdf" class="form-control" required>
                    <div class="invalid-feedback"></div>

                    <div class="file-preview mt-2" id="previewDokumen" style="display:none;">
                        <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                        <span id="dokumenFileName" class="fw-semibold ms-1"></span>
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('user.kapal-index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" id="btnSimpan" class="btn btn-primary mx-2">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('formKapal');
    const btnSimpan = document.getElementById('btnSimpan');

    const fotoInput = document.getElementById('foto_kapal');
    const previewFoto = document.getElementById('previewFoto');

    const dokumenInput = document.getElementById('dokumen_kapal');
    const previewDokumen = document.getElementById('previewDokumen');
    const dokumenFileName = document.getElementById('dokumenFileName');

    // Foto Preview
    fotoInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                previewFoto.src = event.target.result;
                previewFoto.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else previewFoto.style.display = 'none';
    });

    // Dokumen Preview (PDF)
    dokumenInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) {
            previewDokumen.style.display = 'none';
            return;
        }

        if (file.type !== "application/pdf") {
            previewDokumen.style.display = "none";
            dokumenInput.value = "";
            Swal.fire({
                icon: "error",
                title: "Format Tidak Valid",
                text: "Dokumen kapal hanya boleh dalam format PDF.",
            });
            return;
        }

        dokumenFileName.textContent = file.name;
        previewDokumen.style.display = 'flex';
    });

    // Submit Form
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        form.querySelectorAll('.form-control').forEach(input => {
            input.classList.remove('is-invalid');
            const feedback = input.parentElement.querySelector('.invalid-feedback');
            if (feedback) feedback.textContent = '';
        });

        const formData = new FormData(form);

        Swal.fire({
            title: 'Menyimpan Data...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        btnSimpan.disabled = true;

        try {
            const res = await fetch('{{ route('user.kapal-store') }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData
            });

            const data = await res.json();
            Swal.close();
            btnSimpan.disabled = false;

            if (res.ok && data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message || 'Data kapal berhasil disimpan.',
                }).then(() => {
                    if (data.id_kapal) {
                        window.location.href = '{{ url("kapal") }}/' + data.id_kapal;
                    } else {
                        window.location.href = '{{ route("user.kapal-index") }}';
                    }
                });
            }
            else if (res.status === 422) {
                for (const [field, messages] of Object.entries(data.errors)) {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const fb = input.parentElement.querySelector('.invalid-feedback');
                        if (fb) fb.textContent = messages.join(', ');
                    }
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal!',
                    text: 'Periksa kembali data yang diisi.',
                });
            }
            else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan!',
                    text: data.message || 'Terjadi kesalahan.',
                });
            }
        }
        catch (err) {
            console.error('Error submit:', err);
            btnSimpan.disabled = false;
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan jaringan/server.',
            });
        }
    });
});
</script>
@endsection
