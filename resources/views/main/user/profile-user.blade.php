@extends('layouts.app-user')

@section('content')
    <div class="container p-2">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h4 class="fw-bold">PROFILE</h4>
        </div>

        {{-- Form Profil --}}
        <div class="card p-4 mb-4 shadow-sm" id="profileCard">
            <h6 id="editNotice" class="text-primary d-none">Edit Profil :</h6>

            <div class="row g-2 p-2">
                <div class="col-md-6 ">
                    {{-- Nama --}}
                    <div class="mb-3 row align-items-center">
                        <div class="col-sm-2 fw-semibold">Nama :</div>
                        <div class="col-sm-10">
                            <span class="view">{{ $user->name ?? '-' }}</span>
                            <input type="text" class="form-control edit d-none" value="{{ $user->name }}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    {{-- NIK --}}
                    <div class="mb-3 row align-items-center">
                        <div class="col-sm-2 fw-semibold">NIK :</div>
                        <div class="col-sm-10">
                            <span class="view">{{ $user->nik ?? '-' }}</span>
                            <input type="text" class="form-control edit d-none" value="{{ $user->nik }}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    {{-- No HP --}}
                    <div class="mb-3 row align-items-center">
                        <div class="col-sm-2 fw-semibold">No.HP :</div>
                        <div class="col-sm-10">
                            <span class="view">{{ $user->no_hp ?? '-' }}</span>
                            <input type="text" class="form-control edit d-none" value="{{ $user->no_hp }}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- Email --}}
                    <div class="mb-3 row align-items-center">
                        <div class="col-sm-2 fw-semibold">Email :</div>
                        <div class="col-sm-10">
                            <span class="view">{{ $user->email ?? '-' }}</span>
                            <input type="email" class="form-control edit d-none" value="{{ $user->email }}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-3 row align-items-center">
                        <div class="col-sm-2 fw-semibold">Alamat :</div>
                        <div class="col-sm-10">
                            <span class="view">{{ $user->alamat ?? '-' }}</span>
                            <input type="text" class="form-control edit d-none" value="{{ $user->alamat }}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>


                </div>
            </div>
            {{-- Tombol Aksi --}}
            <div class="mt-4 d-flex justify-content-end gap-2">
                <button id="editBtn" class="btn btn-primary w-100">Edit Profil</button>
                <button id="saveBtn" class="btn btn-success w-100 d-none">Simpan</button>
                <button id="cancelBtn" class="btn btn-secondary w-100 d-none">Batal</button>
                <button id="changePasswordBtn" class="btn w-100 btn-warning">Ganti Password</button>
            </div>
        </div>

        {{-- Form Password --}}
        <div class="card p-4 mb-4 shadow-sm d-none" id="passwordCard">
            <h6 class="text-primary">Ganti Password :</h6>
            <form id="passwordForm">
                <div class="mb-3">
                    <label class="form-label">Password Saat Ini</label>
                    <input type="password" class="form-control" name="current_password">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" class="form-control" name="new_password">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" name="new_password_confirmation">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-success">Simpan Password</button>
                    <button type="button" class="btn btn-secondary" id="cancelPasswordBtn">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-auto px-2 mb-2 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class=" btn btn-sm w-100 btn-outline-danger-custom">
                <i class="bi bi-box-arrow-right me-2"></i>
                <span class="label" style="font-size: 0.8rem;">Logout</span>
            </button>
        </form>
    </div>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editBtn = document.getElementById('editBtn');
            const saveBtn = document.getElementById('saveBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const changePasswordBtn = document.getElementById('changePasswordBtn');
            const cancelPasswordBtn = document.getElementById('cancelPasswordBtn');
            const profileCard = document.getElementById('profileCard');
            const passwordCard = document.getElementById('passwordCard');
            const passwordForm = document.getElementById('passwordForm');

            // mapping keys tanpa tanda baca
            const labelToKey = {
                'nama': 'nama',
                'nik': 'nik',
                'nohp': 'no_hp',   // <-- perbaikan
                'alamat': 'alamat',
                'email': 'email'
            };

            // Edit Profil
            editBtn.addEventListener('click', () => {
                document.getElementById('editNotice').classList.remove('d-none');
                profileCard.querySelectorAll('.view').forEach(el => el.classList.add('d-none'));
                profileCard.querySelectorAll('.edit').forEach(el => el.classList.remove('d-none'));
                editBtn.classList.add('d-none');
                saveBtn.classList.remove('d-none');
                cancelBtn.classList.remove('d-none');
                changePasswordBtn.classList.add('d-none');
            });

            // Batal Edit Profil
            cancelBtn.addEventListener('click', () => {
                document.getElementById('editNotice').classList.add('d-none');

                profileCard.querySelectorAll('.edit').forEach((input, i) => {
                    // sembunyikan input dan tampilkan kembali view
                    input.classList.add('d-none');
                    profileCard.querySelectorAll('.view')[i].classList.remove('d-none');

                    // hapus status error jika ada
                    input.classList.remove('is-invalid');
                    const feedback = input.parentElement.querySelector('.invalid-feedback');
                    if (feedback) feedback.textContent = '';
                });

                editBtn.classList.remove('d-none');
                saveBtn.classList.add('d-none');
                cancelBtn.classList.add('d-none');
                changePasswordBtn.classList.remove('d-none');
            });

            // Simpan Profil
            saveBtn.addEventListener('click', () => {
                const data = {};
                profileCard.querySelectorAll('.mb-3.row').forEach(row => {
                    // ambil teks label, lower case, hapus semua non-alphanumeric
                    const rawLabel = row.querySelector('.fw-semibold').textContent || '';
                    const label = rawLabel.trim().toLowerCase().replace(/[^a-z0-9]/g, '');
                    const key = labelToKey[label];
                    const input = row.querySelector('.edit');
                    if (key && input) data[key] = input.value;

                    // bersihkan error state lama
                    if (input) {
                        input.classList.remove('is-invalid');
                        const feedback = row.querySelector('.invalid-feedback');
                        if (feedback) feedback.textContent = '';
                    }
                });

                // DEBUG: lihat payload sebelum dikirim
                console.log('Payload updateProfile:', data);

                Swal.fire({
                    title: 'Menyimpan data...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch('{{ route("user.updateProfile") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                    .then(async res => {
                        // jika status 422 (validasi), ambil JSON juga
                        const json = await res.json().catch(() => ({}));
                        return { status: res.status, json };
                    })
                    .then(({ status, json }) => {
                        Swal.close();

                        // tampilkan error validasi (422) atau success
                        if (status === 200 && json.success) {
                            // update view
                            profileCard.querySelectorAll('.mb-3.row').forEach(row => {
                                const input = row.querySelector('.edit');
                                const view = row.querySelector('.view');
                                if (input && view) {
                                    view.textContent = input.value || '-';
                                    input.classList.add('d-none');
                                    view.classList.remove('d-none');
                                }
                            });
                            editBtn.classList.remove('d-none');
                            saveBtn.classList.add('d-none');
                            cancelBtn.classList.add('d-none');
                            changePasswordBtn.classList.remove('d-none');
                            document.getElementById('editNotice').classList.add('d-none');

                            Swal.fire('Berhasil', json.message || 'Data profil berhasil diperbarui!', 'success');
                        } else if (json.errors) {
                            // json.errors adalah object: { field: [msg, ...], ... }
                            for (const [field, messages] of Object.entries(json.errors)) {
                                // cari row yang sesuai
                                const row = [...profileCard.querySelectorAll('.mb-3.row')].find(r => {
                                    const rawLabel = r.querySelector('.fw-semibold').textContent || '';
                                    const normLabel = rawLabel.trim().toLowerCase().replace(/[^a-z0-9]/g, '');
                                    return labelToKey[normLabel] === field;
                                });
                                if (row) {
                                    const input = row.querySelector('.edit');
                                    if (input) {
                                        input.classList.add('is-invalid');
                                        row.querySelector('.invalid-feedback').textContent = messages.join(', ');
                                    }
                                }
                            }

                            // optional: tampilkan toast ringkasan
                            Swal.fire('Validasi gagal', json.message || 'Periksa input Anda.', 'error');
                        } else {
                            Swal.fire('Gagal', json.message || 'Terjadi kesalahan saat menyimpan data!', 'error');
                        }
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                        Swal.close();
                        Swal.fire('Gagal', 'Terjadi kesalahan jaringan!', 'error');
                    });
            });

            // Ganti Password (sama perbaikan header)
            changePasswordBtn.addEventListener('click', () => {
                profileCard.classList.add('d-none');
                passwordCard.classList.remove('d-none');
                editBtn.classList.add('d-none');
                saveBtn.classList.add('d-none');
                cancelBtn.classList.add('d-none');
                changePasswordBtn.classList.add('d-none');
            });

            cancelPasswordBtn.addEventListener('click', () => {
                passwordCard.classList.add('d-none');
                passwordForm.reset();
                profileCard.classList.remove('d-none');
                editBtn.classList.remove('d-none');
                changePasswordBtn.classList.remove('d-none');
            });

            passwordForm.addEventListener('submit', (e) => {
                e.preventDefault();

                const data = {
                    current_password: passwordForm.current_password.value,
                    new_password: passwordForm.new_password.value,
                    new_password_confirmation: passwordForm.new_password_confirmation.value
                };

                console.log('Payload changePassword:', data);

                Swal.fire({
                    title: 'Mengubah password...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch('{{ route("user.changePassword") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                    .then(async res => {
                        const json = await res.json().catch(() => ({}));
                        return { status: res.status, json };
                    })
                    .then(({ status, json }) => {
                        Swal.close();

                        if (status === 200 && json.success) {
                            Swal.fire('Berhasil', json.message || 'Password berhasil diperbarui!', 'success');
                            passwordForm.reset();
                            passwordCard.classList.add('d-none');
                            profileCard.classList.remove('d-none');
                            editBtn.classList.remove('d-none');
                            changePasswordBtn.classList.remove('d-none');
                        } else if (json.errors) {
                            for (const [field, messages] of Object.entries(json.errors)) {
                                const input = passwordForm[field];
                                if (input) {
                                    input.classList.add('is-invalid');
                                    input.nextElementSibling.textContent = messages.join(', ');
                                }
                            }
                            Swal.fire('Validasi gagal', json.message || 'Periksa input Anda.', 'error');
                        } else {
                            Swal.fire('Gagal', json.message || 'Terjadi kesalahan saat mengganti password!', 'error');
                        }
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                        Swal.close();
                        Swal.fire('Gagal', 'Terjadi kesalahan jaringan!', 'error');
                    });
            });
        });
    </script>

@endsection