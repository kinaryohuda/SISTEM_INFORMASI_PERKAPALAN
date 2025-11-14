
<aside class="sidebar-desktop d-none d-md-flex" id="sidebar" aria-expanded="true">

    {{-- === TOGGLE BUTTON === --}}
    <button class="toggle-sidebar" id="toggleSidebar" aria-label="Toggle sidebar" title="Toggle sidebar">
        <i class="bi bi-chevron-left" id="toggleIcon"></i>
    </button>

    {{-- === WRAPPER SCROLLABLE === --}}
    <div class="sidebar-scroll">

        <nav class="nav-list" id="sidebarContent" style="font-size: 0.8rem;">

            <span class="fw-bold text-secondary label-custom" data-title="Menu">MENU</span>

            {{-- DASHBOARD --}}
            <a href="{{ route('superAdmin.dashboard') }}" data-title="Dashboard"
                class="{{ request()->routeIs('superAdmin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i>
                <span class="label">Dashboard</span>
            </a>

            <span class="fw-bold text-secondary mt-3 label-custom" data-title="Permohonan">PERMOHONAN</span>

            {{-- VERIFIKASI --}}
            <a href="{{ route('superAdmin.verifikasi.index') }}" data-title="Verifikasi"
                class="{{ request()->routeIs('superAdmin.verifikasi*') ? 'active' : '' }}">
                <i class="bi bi-patch-check"></i>
                <span class="label">Verifikasi</span>
            </a>

            {{-- PERMOHONAN --}}
            <a href="{{ route('superAdmin.pengajuan-permohonan-index') }}" data-title="Pengajuan"
                class="{{ request()->routeIs('superAdmin.pengajuan-permohonan*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i>
                <span class="label">Pengajuan</span>
            </a>

            {{-- RIWAYAT --}}
            <a href="{{ route('superAdmin.riwayat-index') }}" data-title="Riwayat"
                class="{{ request()->routeIs('superAdmin.riwayat*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span class="label">Riwayat</span>
            </a>

            <span class="fw-bold text-secondary mt-3 label-custom" data-title="Data Master">DATA MASTER</span>

            {{-- DATA KAPAL --}}
            <a href="{{ route('superAdmin.kapal-index') }}" data-title="Data Kapal"
                class="{{ request()->routeIs('superAdmin.kapal*') ? 'active' : '' }}">
                <i class="fa-solid fa-ship"></i>
                <span class="label">Data Kapal</span>
            </a>

            {{-- DATA ADMIN --}}
            <a href="{{ route('superAdmin.pengguna-admin-index') }}" data-title="Pengguna Admin"
                class="{{ request()->routeIs('superAdmin.pengguna-admin*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i>
                <span class="label">Pengguna Admin</span>
            </a>

            {{-- DATA USER --}}
            <a href="{{ route('superAdmin.pengguna-user-index') }}" data-title="Pengguna User"
                class="{{ request()->routeIs('superAdmin.pengguna-user*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span class="label">Pengguna User</span>
            </a>

            <span class="fw-bold text-secondary mt-3 label-custom" data-title="Akun">AKUN</span>

            {{-- PROFILE --}}
            <a href="{{ route('profile-user') }}" data-title="Profile"
                class="{{ request()->routeIs('profile-user') ? 'active' : '' }}">
                <i class="bi bi-person"></i>
                <span class="label">Profile</span>
            </a>

            <span class="fw-bold text-secondary mt-3 label-custom" data-title="Setting">SETTING</span>

            {{-- KOMPONEN --}}
            <a href="{{ route('superAdmin.komponen-pengajuan-index') }}" data-title="Komponen Pengajuan"
                class="{{ request()->routeIs('superAdmin.komponen-pengajuan*') ? 'active' : '' }}">
                <i class="bi bi-list-task"></i>
                <span class="label">Komponen Pengajuan</span>
            </a>

            {{-- LOGOUT --}}
            <div class="mb-4 mt-4 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm w-100 btn-outline-danger-custom" data-title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="label-logout">Logout</span>
                    </button>
                </form>
            </div>

        </nav>
    </div>

    <div class="px-2 small-muted">v.1.0</div>
</aside>


<script>
    // === TOGGLE SIDEBAR ===
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleSidebar");
    const toggleIcon = document.getElementById("toggleIcon");

    toggleBtn.addEventListener("click", () => {
        sidebar.classList.toggle("shrink");

        toggleIcon.classList.toggle("bi-chevron-left");
        toggleIcon.classList.toggle("bi-chevron-right");
    });

    // === SUBMENU ===
    document.querySelectorAll(".submenu-toggle").forEach(toggle => {
        toggle.addEventListener("click", function (e) {
            e.preventDefault();
            const parent = this.parentElement;
            parent.classList.toggle("open");

            const submenu = parent.querySelector(".submenu-items");
            submenu.style.display = parent.classList.contains("open") ? "flex" : "none";
        });
    });
</script>
