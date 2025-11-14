@extends('layouts.app-user')

@section('title', 'Verifikasi Pengajuan')

@section('content')

    <div class="container p-3">
         <div>
            <h5 class="fw-bold text-primary mb-0">
                 <i class="bi bi-patch-check me-1"></i>
                <span>VERIFIKASI PERMOHONAN</span> 
            </h5>
            <hr class="mt-1 mb-3">
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Pemilik</th>
                        <th class="text-center">Kapal</th>
                        <th class="text-center">Tanggal Pengajuan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Detail</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($pengajuans as $i => $item)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ optional($item->kapal->user)->name ?? optional($item->kapal)->nama_pemilik ?? '-' }}</td>
                                    <td>{{ optional($item->kapal)->nama_kapal ?? '-' }}</td>
                                    <td>{{ optional($item->created_at)->format('d M Y H:i') ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge p-2 bg-{{ 
                                                                                                $item->status === 'disetujui' ? 'success' :
                        ($item->status === 'ditolak' ? 'danger' :
                            ($item->status === 'menunggu' ? 'warning' : 'secondary'))
                                                                                            }}">
                                            {{ ucfirst(strtolower($item->status ?? '-')) }}

                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary btnDetail" data-id="{{ $item->id_pengajuan }}">
                                            Lihat
                                        </button>
                                    </td>
                                </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL --}}
    <div class="modal fade" id="modalVerifikasi" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        Detail Pengajuan – <span id="modalKapal"></span>
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <p><strong>Status Saat Ini:</strong> <span id="modalStatus"></span></p>
                        <p><strong>Tanggal Pengajuan:</strong> <span id="modalTanggal"></span></p>
                    </div>

                    <hr>

                    <h5 class="fw-bold mb-3">Data Kapal</h5>
                    <table class="table table-bordered mb-4">
                        <tbody id="tabelKapal"></tbody>
                    </table>

                    <h5 class="fw-bold">Data Komponen</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Komponen</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody id="tabelKomponen"></tbody>
                    </table>

                    <hr>

                    <h5 class="fw-bold">Riwayat Verifikasi</h5>
                    <div id="logVerifikasi"></div>

                    <hr>

                    <h5 class="fw-bold">Update Status</h5>
                    <form id="formVerifikasi">
                        @csrf
                        <input type="hidden" id="idPengajuan" name="id">

                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select" required>
                                <option value="" disabled>Pilih Status</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="menunggu">Menunggu</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Catatan</label>
                            <textarea name="catatan_verifikator" class="form-control" rows="3"></textarea>
                        </div>

                        <button class="btn btn-success">Simpan</button>
                    </form>

                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const csrfToken = "{{ csrf_token() }}";

        document.addEventListener("click", async function (e) {
            if (!e.target.classList.contains("btnDetail")) return;

            const id = e.target.dataset.id;
            const url = "{{ route('superAdmin.verifikasi.show', ':id') }}".replace(":id", id);

            // Reset modal
            document.getElementById("modalKapal").textContent = "Loading...";
            document.getElementById("modalStatus").textContent = "";
            document.getElementById("modalTanggal").textContent = "";
            document.getElementById("tabelKomponen").innerHTML = "";
            document.getElementById("tabelKapal").innerHTML = "";
            document.getElementById("logVerifikasi").innerHTML = "";

            try {
                const res = await fetch(url);
                const json = await res.json();

                if (!json.success) return alert("Gagal memuat data!");

                const data = json.data;

                document.getElementById("idPengajuan").value = data.id_pengajuan;
                document.getElementById("modalKapal").textContent = data.kapal?.nama_kapal ?? "-";
                document.getElementById("modalStatus").textContent =
                    data.status ? data.status.charAt(0).toUpperCase() + data.status.slice(1).toLowerCase() : "-";
                document.getElementById("modalTanggal").textContent = data.created_at ?? "-";

                // DATA KAPAL
                let kapal = data.kapal || {};
                let kapalHTML = "";
                const fotoKapal = kapal.foto_url ?? null;
                const dokumenKapal = kapal.dokumen_url ?? null;

                kapalHTML += `
                                <tr><td>Nama Kapal</td><td>${kapal.nama_kapal ?? '-'}</td></tr>
                                <tr><td>Pemilik</td><td>${kapal.nama_pemilik ?? '-'}</td></tr>
                                <tr><td>Kapasitas</td><td>${kapal.kapasitas ?? '-'}</td></tr>
                                <tr><td>Tipe Kapal</td><td>${kapal.tipe_kapal ?? '-'}</td></tr>
                                <tr><td>Nomor Registrasi</td><td>${kapal.nomor_registrasi ?? '-'}</td></tr>
                                <tr>
                                    <td>Foto Kapal</td>
                                    <td>
                    ${fotoKapal
                        ? `<button type="button" class="btn btn-outline-primary btn-sm" onclick="window.open('${fotoKapal}', '_blank')">Lihat Foto</button>`
                        : `<span class="text-muted">Tidak ada foto</span>`
                    }
                </td>
                                </tr>
                                <tr>
                                    <td>Dokumen Kapal</td>
                                    <td>${dokumenKapal ? `<a href="${dokumenKapal}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Dokumen</a>` : `<span class="text-muted">Tidak ada dokumen</span>`}</td>
                                </tr>
                            `;

                document.getElementById("tabelKapal").innerHTML = kapalHTML;

                // DATA KOMPONEN
                let html = "";
                if (Array.isArray(data.details) && data.details.length > 0) {
                    data.details.forEach(d => {
                        const nama = d.komponen?.nama_komponen ?? "-";
                        const tipe = d.komponen?.tipe?.toLowerCase();
                        let nilai = d.nilai;

                        try {
                            const decode = JSON.parse(d.nilai);
                            if (decode?.url) {
                                if (tipe === "foto") {
                                    nilai = `<button type="button" class="btn btn-outline-primary btn-sm" onclick="window.open('${decode.url}', '_blank')">Lihat Foto</button>`;
                                }

                                if (tipe === "file") {
                                    nilai = `<a href="${decode.url}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Dokumen</a>`;
                                }
                            }
                        } catch { }

                        html += `<tr><td>${nama}</td><td>${nilai}</td></tr>`;
                    });
                } else {
                    html = `<tr><td colspan="2" class="text-muted">Tidak ada data komponen.</td></tr>`;
                }

                document.getElementById("tabelKomponen").innerHTML = html;

                // LOG VERIFIKASI
                let logHTML = "";
                (data.log_verifikator ?? []).forEach(l => {
                    logHTML += `
                             <div class="border p-2 mb-2 rounded bg-light">
                                <strong>${l.nama_verifikator ?? "-"}</strong><br>
                                <span class="badge 
                                    ${l.status_baru === 'disetujui' ? 'bg-success' :
                                                    (l.status_baru === 'ditolak' ? 'bg-danger' :
                                                        (l.status_baru === 'menunggu' ? 'bg-warning text-dark' : 'bg-secondary'))}">
                                    ${l.status_baru ? l.status_baru.charAt(0).toUpperCase() + l.status_baru.slice(1).toLowerCase() : '-'}
                                </span><br>
                                <small class="text-muted">${l.created_at ?? "-"}</small>
                                <p class="mt-2">${l.catatan_verifikator ?? ''}</p>
                            </div>
                        
                                `;
                });
                document.getElementById("logVerifikasi").innerHTML = logHTML || `<p class="text-muted">Belum ada riwayat.</p>`;

                // SHOW MODAL
                let modal = new bootstrap.Modal(document.getElementById("modalVerifikasi"));
                modal.show();

            } catch (err) {
                console.error(err);
                alert("Terjadi kesalahan.");
            }
        });

        // UPDATE STATUS
        document.getElementById("formVerifikasi").addEventListener("submit", async function (e) {
            e.preventDefault();

            const id = document.getElementById("idPengajuan").value;

            const res = await fetch(`/superAdmin/verifikasi/${id}/status`, {
                method: "POST",
                headers: { "X-CSRF-TOKEN": csrfToken },
                body: new FormData(this)
            });

            const json = await res.json();

            if (json.success) {
                alert(json.message);
                location.reload();
            } else {
                alert("Gagal menyimpan!");
            }
        });
    </script>
@endpush