{{-- === HEADER === --}}
<style>
.person-icon {
    font-size: 1.5rem;
    transition: transform 0.3s ease;
    color: black
}

.person-icon:hover {
    transform: scale(1.3);
}
</style>

<header>
    <div class="logo">
        <i class="fa-solid fa-ship"></i>
        <span>Sistem Informasi Perizinan Kapal</span>
    </div>

    <div class="user-info d-flex align-items-center gap-2">
        <span>{{ auth()->user()->name ?? 'Nama User' }} - {{ auth()->user()->role ?? 'Role User' }}</span>
        <a href="{{ route('profile-user') }}" class="{{ request()->routeIs('profile-user') ? 'active' : '' }}">
            <i class="bi bi-person-circle person-icon"></i>
        </a>
    </div>
</header>

@if (auth()->user()->role === 'user')
    @include('partials.components.sidebar-navbar-user')
@elseif (auth()->user()->role === 'admin')
    @include('partials.components.sidebar-navbar-admin')
@elseif (auth()->user()->role === 'superAdmin')
    @include('partials.components.sidebar-navbar-superAdmin')
@endif