{{-- === SIDEBAR DESKTOP === --}}
<aside class="sidebar-desktop d-none d-md-flex" id="sidebar" aria-expanded="true">
    <button class="toggle-sidebar" id="toggleSidebar" aria-label="Toggle sidebar" title="Toggle sidebar">
        <i class="bi bi-chevron-left" id="toggleIcon"></i>
    </button>

    <nav class="nav-list" id="sidebarContent" style="font-size: 0.8rem;">
        <span class="fw-bold text-secondary" style="font-size: 0.8rem">MENU</span>

        {{-- DASHBOARD --}}
        <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i>
            <span class="label" style="font-size: 0.8rem;">Dashboard</span>
        </a>

        {{-- PERMOHONAN --}}
        <a href="{{ route('user.pengajuan-permohonan-index') }}"
            class="{{ request()->routeIs('user.pengajuan-permohonan*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i>
            <span class="label" style="font-size: 0.8rem;">Ajukan Permohonan</span>
        </a>

        {{-- DATA KAPAL --}}
        <a href="{{ route('user.kapal-index') }}" class="{{ request()->routeIs('user.kapal*') ? 'active' : '' }}">
            <i class="fa-solid fa-ship"></i>
            <span class="label" style="font-size: 0.8rem;">Data Kapal</span>
        </a>

        {{-- RIWAYAT --}}
        <a href="{{ route('user.riwayat-index') }}" class="{{ request()->routeIs('user.riwayat*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span class="label" style="font-size: 0.8rem;">Riwayat Permohonan</span>
        </a>

        <span class="fw-bold text-secondary mt-3" style="font-size: 0.8rem">AKUN</span>

        {{-- PROFILE --}}
        <a href="{{ route('profile-user') }}" class="{{ request()->routeIs('profile-user') ? 'active' : '' }}">
            <i class="bi bi-person"></i>
            <span class="label" style="font-size: 0.8rem;">Profile</span>
        </a>
    </nav>

    {{-- LOGOUT --}}
    <div class="mt-auto px-2 mb-2 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm w-100 btn-outline-danger-custom">
                <i class="bi bi-box-arrow-right me-2"></i>
                <span class="label" style="font-size: 0.8rem;">Logout</span>
            </button>
        </form>
    </div>

    <div class="px-2 small-muted">v.1.0</div>
</aside>

{{-- === NAVBAR MOBILE === --}}
<div class="navbar-mobile d-md-none">
    <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door"></i>
        <div>Dashboard</div>
    </a>

    <a href="{{ route('user.pengajuan-permohonan-index') }}"
        class="{{ request()->routeIs('user.pengajuan-permohonan*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i>
        <div>Permohonan</div>
    </a>

    {{-- DATA KAPAL MOBILE --}}
    <a href="{{ route('user.kapal-index') }}"
        class="{{ request()->routeIs('user.kapal-index*') || request()->routeIs('user.kapal-create*') || request()->routeIs('user.kapal-edit*') || request()->routeIs('kapal-show-user*') ? 'active' : '' }}">
        <i class="fa-solid fa-ship"></i>
        <div>Kapal</div>
    </a>

    <a href="{{ route('user.riwayat-index') }}" class="{{ request()->routeIs('user.riwayat*') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i>
        <div>Riwayat</div>
    </a>
</div>