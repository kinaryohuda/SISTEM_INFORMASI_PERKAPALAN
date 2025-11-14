<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/icons/icons_sistem_infomasi_perkapalan.png') }}">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/buttonStyle.css') }}">

    <style>
        /* ============================
        GLOBAL VARIABLES
        ============================ */
        :root {
            --header-height: 60px;
            --sidebar-expanded: 230px;
            --sidebar-collapsed: 68px;
            --transition-time: 0.28s;
        }

        /* ============================
        BODY PADDING UNTUK HEADER FIXED
        ============================ */
        body {
            padding-top: calc(var(--header-height));
            /* jarak aman ke bawah header */
        }

        /* ============================
        HEADER
        ============================ */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
        }

        header .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #0d6efd;
            font-weight: 600;
        }

        header .logo i {
            font-size: 1.35rem;
        }

        header .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ============================
        SIDEBAR DESKTOP
        ============================ */
        @media (min-width: 768px) {
            .sidebar-desktop {
                position: fixed;
                top: var(--header-height);
                left: 0;
                width: var(--sidebar-expanded);
                height: calc(100vh - var(--header-height));
                background: #fff;
                border-right: 1px solid #dee2e6;
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
                padding: 1.25rem 0.75rem;
                transition: width var(--transition-time) ease, padding var(--transition-time) ease;
                overflow: hidden;
                z-index: 1040;
                display: flex;
                flex-direction: column;
            }

            .sidebar-desktop.shrink {
                width: var(--sidebar-collapsed);
                padding: 0.8rem 0.35rem;
            }

            /* Scroll Wrapper */
            .sidebar-scroll {
                height: calc(100vh - var(--header-height) - 20px);
                overflow-y: auto;
                overflow-x: hidden;
                padding-right: 4px;
                margin-right: 2px;
            }

            .sidebar-scroll::-webkit-scrollbar {
                width: 6px;
            }

            .sidebar-scroll::-webkit-scrollbar-thumb {
                background: rgba(0, 0, 0, 0.2);
                border-radius: 10px;
            }

            .sidebar-scroll::-webkit-scrollbar-thumb:hover {
                background: rgba(0, 0, 0, 0.35);
            }

            /* Toggle Button */
            .toggle-sidebar {
                position: absolute;
                top: 12px;
                right: -15px;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                border: 1px solid #dee2e6;
                background: #fff;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 1050;
                transition: 0.3s;
            }

            /* Navigation */
            .nav-list {
                display: flex;
                flex-direction: column;
                gap: .25rem;
                padding: .25rem .25rem;
                margin-top: 40px;
            }

            .nav-list a {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 10px;
                color: #333;
                text-decoration: none;
                border-radius: .375rem;
                position: relative;
            }

            .nav-list a:hover,
            .nav-list a.active {
                background: #0d6efd;
                color: #fff;
            }

            /* Hide label saat shrink */
            .sidebar-desktop.shrink .nav-list a .label,
            .sidebar-desktop.shrink .label-custom,
            .sidebar-desktop.shrink .label-logout {
                display: none !important;
            }

            .label-custom {
                font-weight: bold;
                padding-left: 10px;
                margin-top: 10px;
                color: #6c757d;
            }

            .sidebar-desktop.shrink .label-custom {
                opacity: 0;
            }

            /* Tooltip pada mode shrink */
            .sidebar-desktop.shrink .nav-list a::after,
            .sidebar-desktop.shrink .label-custom::after {
                content: attr(data-title);
                position: absolute;
                left: calc(var(--sidebar-collapsed) + 12px);
                top: 50%;
                transform: translateY(-50%);
                background: #0d6efd;
                color: #fff;
                padding: 6px 10px;
                border-radius: 6px;
                font-size: 0.78rem;
                white-space: nowrap;
                opacity: 0;
                pointer-events: none;
                transition: opacity .25s ease;
                z-index: 2000;
            }

            .sidebar-desktop.shrink .nav-list a:hover::after,
            .sidebar-desktop.shrink .label-custom:hover::after {
                opacity: 1;
            }

            /* Body Padding */
            body.with-sidebar-expanded {
                padding-left: var(--sidebar-expanded);
            }

            body.with-sidebar-collapsed {
                padding-left: var(--sidebar-collapsed);
            }

            main  {
                padding-top: 30px;
                padding-bottom: 30px;
            }


        }

        /* ============================
        MOBILE NAVBAR
        ============================ */
        @media (max-width: 767.98px) {
            .navbar-mobile {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background: #fff;
                border-top: 1px solid #dee2e6;
                box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.05);
                display: flex;
                justify-content: space-around;
                padding: .4rem 0;
                z-index: 1000;
            }

            .navbar-mobile a {
                text-align: center;
                text-decoration: none;
                color: #333;
            }

            .navbar-mobile a.active {
                color: #0d6efd;
            }

            header .user-info span {
                display: none;
            }

            main {
                padding-top: calc(var(--header-height) - 35px);
                padding-bottom: 30px;
            }

            body {
                padding-left: 0 !important;
            }
        }
    </style>
</head>

<body>
    @include('partials.header-navbar')

    <main class="container-fluid bg-body-tertiary" id="mainContent">
        <div class="container">
            <div class="card bg-white p-2">
                @yield('content')
            </div>
        </div>
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('toggleIcon');
            const body = document.body;

            let collapsed = false;

            function applyState() {
                if (collapsed) {
                    sidebar.classList.add('shrink');
                    sidebar.setAttribute('aria-expanded', 'false');
                    toggleIcon.className = 'bi bi-chevron-right';
                    body.classList.remove('with-sidebar-expanded');
                    body.classList.add('with-sidebar-collapsed');
                } else {
                    sidebar.classList.remove('shrink');
                    sidebar.setAttribute('aria-expanded', 'true');
                    toggleIcon.className = 'bi bi-chevron-left';
                    body.classList.remove('with-sidebar-collapsed');
                    body.classList.add('with-sidebar-expanded');
                }
            }

            function init() {
                const md = window.matchMedia('(min-width: 768px)').matches;
                if (!md) {
                    body.classList.remove('with-sidebar-expanded', 'with-sidebar-collapsed');
                    sidebar.classList.remove('shrink');
                    sidebar.setAttribute('aria-expanded', 'true');
                    return;
                }
                collapsed = false;
                applyState();
            }

            toggleBtn?.addEventListener('click', () => {
                collapsed = !collapsed;
                applyState();
            });

            window.addEventListener('resize', init);
            init();
        })();
    </script>
    @stack('scripts')
</body>

</html>