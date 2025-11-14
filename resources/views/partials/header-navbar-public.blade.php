<!-- Import Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Poppins:wght@600&display=swap"
    rel="stylesheet">

<style>
    .navbar .container {
        max-width: 100%;
        padding-left: 1rem;
        padding-right: 1rem;

    }

    .navbar {
        position: sticky;
        top: 0;
        z-index: 1030;
        background: linear-gradient(90deg, #003366, #0055a5);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        border-bottom: 3px solid #00264d;
        transition: all 0.3s ease;
    }

    .navbar-brand {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.25rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        color: #fff !important;
        display: flex;
        align-items: center;
        transition: font-size 0.3s ease;
    }

    .navbar-brand img {
        border-radius: 50%;
        border: 2px solid #fff;
        object-fit: cover;
        width: 40px;
        height: 40px;
        margin-right: 10px;
    }

    .navbar-nav .nav-link {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        color: #ddd !important;
        letter-spacing: 0.3px;
        margin-left: 1rem;
        padding: 6px 14px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
        color: #fff !important;
        background-color: rgba(255, 255, 255, 0.25);
    }

    @media (max-width: 992px) {
        .navbar-nav .nav-link {
            margin-left: 0;
            margin-top: 5px;
            padding: 8px;
            text-align: center;
        }
    }

    /* Perbaikan ukuran teks brand di mobile */
    @media (max-width: 768px) {
        .navbar-brand {
            font-size: 1rem;
            /* lebih kecil di tablet/ponsel besar */
        }

        .navbar-brand img {
            width: 34px;
            height: 34px;
            margin-right: 8px;
        }
    }

    @media (max-width: 576px) {
        .navbar-brand {
            font-size: 0.9rem;
            /* lebih kecil di ponsel kecil */
        }

        .navbar-brand img {
            width: 30px;
            height: 30px;
            margin-right: 6px;
        }
    }

    .navbar.scrolled {
        background: linear-gradient(90deg, #002850, #004080);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
    }
</style>

<nav id="publicNavbar" class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#hero">
            <img src="{{ asset('assets/icons/Icons_sistem_infomasi_perkapalan.png') }}" alt="Logo">
            Sistem Informasi Perizinan Kapal
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublic"
            aria-controls="navbarPublic" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarPublic">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link active" href="#hero">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/register') }}">Register</a></li>
            </ul>
        </div>
    </div>
</nav>


<script>
    // Efek scroll: ubah style navbar saat di-scroll
    document.addEventListener("scroll", function () {
        const navbar = document.getElementById("publicNavbar");
        if (window.scrollY > 10) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });

    // Smooth scroll untuk anchor link
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                window.scrollTo({
                    top: target.offsetTop - 70, // biar gak ketutup navbar
                    behavior: 'smooth'
                });
            }
        });
    });
</script>