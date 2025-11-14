@extends('layouts.app-user')

@section('title', 'Edit Data Kapal')

@section('content')
<div class="container p-2">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
        <h4 class="fw-bold">EDIT DATA KAPAL</h4>
    </div>

    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <h6 class="text-primary">Perbarui Data Kapal :</h6>

            <form id="formEditKapal" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- DATA PEMILIK --}}
                <h6 class="fw-bold text-secondary mt-2">Data Pemilik</h6>
                <hr class="mt-1 mb-3">

                <div class="mb-3">
                    <label for="nama_pemilik" class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                    <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control"
                        value="{{ old('nama_pemilik', $kapal->nama_pemilik) }}" placeholder="Masukkan nama pemilik" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                    <input type="text" name="nik" id="nik" class="form-control" 
                        value="{{ old('nik', $kapal->nik) }}" placeholder="Masukkan NIK" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap" required>{{ old('alamat', $kapal->alamat) }}</textarea>
                    <div class="invalid-feedback"></div>
                </div>

                {{-- DATA KAPAL --}}
                <h6 class="fw-bold text-secondary mt-4">Data Kapal</h6>
                <hr class="mt-1 mb-3">

                <div class="mb-3">
                    <label for="nama_kapal" class="form-label">Nama Kapal <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kapal" id="nama_kapal" class="form-control"
                        value="{{ old('nama_kapal', $kapal->nama_kapal) }}" placeholder="Masukkan nama kapal" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="tipe_kapal" class="form-label">Tipe Kapal <span class="text-danger">*</span></label>
                    <input type="text" name="tipe_kapal" id="tipe_kapal" class="form-control"
                        value="{{ old('tipe_kapal', $kapal->tipe_kapal) }}" placeholder="Masukkan tipe kapal" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="kapasitas" class="form-label">Kapasitas (Orang / Ton) <span class="text-danger">*</span></label>
                    <input type="number" name="kapasitas" id="kapasitas" class="form-control"
                        value="{{ old('kapasitas', $kapal->kapasitas) }}" placeholder="Masukkan kapasitas" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="nomor_registrasi" class="form-label">Nomor Registrasi <span class="text-danger">*</span></label>
                    <input type="text" name="nomor_registrasi" id="nomor_registrasi" class="form-control"
                        value="{{ old('nomor_registrasi', $kapal->nomor_registrasi) }}" placeholder="Masukkan nomor registrasi" required>
                    <div class="invalid-feedback"></div>
                </div>

                {{-- FILE FOTO & DOKUMEN --}}
                <h6 class="fw-bold text-secondary mt-4">File Pendukung Kapal</h6>
                <hr class="mt-1 mb-3">

                {{-- Foto Kapal --}}
                <div class="mb-3">
                    <label for="foto_kapal" class="form-label fw-semibold">Foto Kapal</label>
                    <input type="file" name="foto_kapal" id="foto_kapal" accept="image/*" class="form-control">
                    <div class="invalid-feedback"></div>
                    <img id="previewFoto"
                        src="{{ $kapal->foto_kapal ? asset('storage/'.$kapal->foto_kapal) : '#' }}"
                        alt="Preview Foto"
                        style="display: {{ $kapal->foto_kapal ? 'block' : 'none' }}; max-width:10%; border-radius:8px; margin-top:8px;">
                </div>

                {{-- Dokumen Kapal --}}
                <div class="mb-3">
                    <label for="dokumen_kapal" class="form-label fw-semibold">Dokumen Kapal</label>
                    <input type="file" name="dokumen_kapal" id="dokumen_kapal" accept=".pdf" class="form-control">
                    <div class="invalid-feedback"></div>
                    <div id="previewDokumen" class="mt-2" style="display: {{ $kapal->dokumen_kapal ? 'flex' : 'none' }};">
                        <i class="bi bi-file-earmark-text text-primary"></i>
                        <span id="dokumenFileName" class="fw-semibold">{{ $kapal->dokumen_kapal ? basename($kapal->dokumen_kapal) : '' }}</span>
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('user.kapal-viewDetail', $kapal->id_kapal) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" id="btnUpdate" class="btn btn-primary mx-2">
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formEditKapal');
    const btnUpdate = document.getElementById('btnUpdate');
    const fotoInput = document.getElementById('foto_kapal');
    const previewFoto = document.getElementById('previewFoto');
    const dokumenInput = document.getElementById('dokumen_kapal');
    const previewDokumen = document.getElementById('previewDokumen');
    const dokumenFileName = document.getElementById('dokumenFileName');

    // Preview Foto
    fotoInput?.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                previewFoto.src = event.target.result;
                previewFoto.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Preview Dokumen
    dokumenInput?.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            dokumenFileName.textContent = file.name;
            previewDokumen.style.display = 'flex';
        }
    });

    // Submit Update
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        form.querySelectorAll('.form-control').forEach(el => {
            el.classList.remove('is-invalid');
            const feedback = el.parentElement.querySelector('.invalid-feedback');
            if (feedback) feedback.textContent = '';
        });

        const formData = new FormData(form);

        Swal.fire({
            title: 'Memperbarui Data...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        btnUpdate.disabled = true;

        try {
            const res = await fetch('{{ route('user.kapal-update', $kapal->id_kapal) }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData
            });

            const data = await res.json();
            Swal.close();
            btnUpdate.disabled = false;

            if (res.ok && data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message || 'Data kapal berhasil diperbarui.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = '{{ route("user.kapal-viewDetail", $kapal->id_kapal) }}';
                });
            } else if (res.status === 422 && data.errors) {
                for (const [field, messages] of Object.entries(data.errors)) {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = input.parentElement.querySelector('.invalid-feedback');
                        if (feedback) feedback.textContent = messages.join(', ');
                    }
                }
                Swal.fire({ icon: 'error', title: 'Validasi Gagal!', text: 'Periksa kembali data.' });
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal Memperbarui!', text: data.message || 'Terjadi kesalahan.' });
            }
        } catch (err) {
            console.error(err);
            btnUpdate.disabled = false;
            Swal.close();
            Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan jaringan atau server.' });
        }
    });
});
</script>

<style>
.input-group .btn {
    display: flex;
    align-items: center;
    justify-content: center;
}
.input-group .btn i {
    margin: 0;
}
</style>
@endsection
