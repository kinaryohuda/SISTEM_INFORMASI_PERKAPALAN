@extends('layouts.app')

@section('title', 'Sistem Informasi Perizinan Kapal')

@section('content')
<style>
    /* ===== Hero Section ===== */
    .hero {
        background: linear-gradient(90deg, #003366, #004080);
        min-height: 100vh;
        display: flex;
        align-items: center;
        text-align: center;
        color: white;
        padding: 0 20px;
    }

    .hero h1 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2.8rem;
        line-height: 1.3;
    }

    .hero p {
        font-family: 'Poppins', sans-serif;
        font-size: 1.1rem;
        max-width: 750px;
        margin: 0 auto 30px;
    }

    .hero-buttons {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .hero .btn {
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        min-width: 180px;
    }

    .hero .btn:hover {
        transform: translateY(-3px);
    }

    /* ===== Responsive Breakpoints ===== */
    @media (max-width: 992px) {
        .hero h1 {
            font-size: 2.3rem;
        }

        .hero p {
            font-size: 1rem;
        }
    }

    @media (max-width: 768px) {
        .hero {
            padding: 100px 25px 70px;
        }

        .hero h1 {
            font-size: 2rem;
        }

        .hero p {
            font-size: 0.95rem;
        }

        .hero-buttons {
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .hero .btn {
            width: 80%;
        }
    }

    /* ===== About Section ===== */
    #about img {
        max-width: 100%;
        border: 4px solid #003366;
    }

    @media (max-width: 768px) {
        #about .row {
            flex-direction: column-reverse;
        }
    }

    /* ===== Features Section ===== */
    #features .p-4 {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    #features .p-4:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    /* ===== Contact Section ===== */
    #contact a.btn:hover {
        background: #f1f1f1;
        color: #003366 !important;
    }
</style>

<!-- ===== Hero Section ===== -->
<section id="hero" class="hero">
    <div class="container">
        <h2 class="fw-bold mb-3">Selamat Datang di Sistem Informasi Perizinan Kapal</h2>
        <p>
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Praesentium error reprehenderit deleniti
            architecto tenetur commodi, nobis debitis ipsam sit officiis iure officia excepturi voluptates ducimus?
        </p>

        <div class="hero-buttons">
            <a href="{{ url('/login') }}" class="btn btn-light btn-lg px-4 py-2">
                Masuk Sekarang
            </a>
            <a href="{{ url('/register') }}" class="btn btn-outline-light btn-lg px-4 py-2">
                Daftar Akun
            </a>
        </div>
    </div>
</section>

<!-- ===== About Section ===== -->
<section id="about" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="font-family: 'Montserrat', sans-serif; color:#003366;">Tentang Sistem</h2>
            <div style="width: 80px; height: 3px; background-color: #0055a5; margin: 10px auto; border-radius: 2px;"></div>
        </div>

        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <p style="font-family: 'Poppins', sans-serif; text-align: justify;">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem inventore qui temporibus dicta vel
                    aperiam sequi aliquam, ea quidem molestiae debitis pariatur minus ab corporis repellendus cumque.
                </p>
            </div>

            <div class="col-md-6 text-center mb-4">
                <img src="{{ asset('assets/images/foto_pelabuhan.png') }}" alt="Tentang Sistem"
                    class="img-fluid rounded shadow" style="max-width: 70%; border: 4px solid #003366;">
            </div>
        </div>
    </div>
</section>

<!-- ===== Features Section (3 Kolom) ===== -->
<section id="features" class="py-5" style="background-color:#f8f9fa;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="font-family: 'Montserrat', sans-serif; color:#003366;">Fitur</h2>
            <div style="width: 80px; height: 3px; background-color: #0055a5; margin: 10px auto; border-radius: 2px;"></div>
        </div>

        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="p-4 shadow-sm rounded bg-white h-100">
                    <img src="{{ asset('assets/icons/feature1.png') }}" alt="Fitur Satu" width="60" class="mb-3">
                    <h5 class="fw-bold">Fitur Satu</h5>
                    <p style="font-family: 'Poppins', sans-serif;">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer euismod nisi vel nulla fermentum.
                    </p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="p-4 shadow-sm rounded bg-white h-100">
                    <img src="{{ asset('assets/icons/feature2.png') }}" alt="Fitur Dua" width="60" class="mb-3">
                    <h5 class="fw-bold">Fitur Dua</h5>
                    <p style="font-family: 'Poppins', sans-serif;">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque at risus ac nulla bibendum.
                    </p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="p-4 shadow-sm rounded bg-white h-100">
                    <img src="{{ asset('assets/icons/feature3.png') }}" alt="Fitur Tiga" width="60" class="mb-3">
                    <h5 class="fw-bold">Fitur Tiga</h5>
                    <p style="font-family: 'Poppins', sans-serif;">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris interdum urna a justo aliquam.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== Contact Section ===== -->
<section id="contact" class="py-5 text-white" style="background: linear-gradient(90deg, #003366, #004080);">
    <div class="container text-center">
        <h2 class="fw-bold mb-4" style="font-family: 'Montserrat', sans-serif;">Hubungi Kami</h2>
        <p style="font-family: 'Poppins', sans-serif;">Untuk informasi lebih lanjut, silakan hubungi kami melalui:</p>
        <div class="mt-3">
            <p><strong>Email:</strong> info@perkapalan.id</p>
            <p><strong>Telepon:</strong> +62 812 3456 7890</p>
        </div>
        <a href="mailto:info@perkapalan.id" class="btn btn-light btn-lg mt-3 fw-bold">Kirim Email</a>
    </div>
</section>

<!-- ===== Footer ===== -->
<footer class="py-3 bg-dark text-center text-white">
    <div class="container">
        <small>&copy; {{ date('Y') }} Sistem Informasi Perizinan Kapal. All rights reserved.</small>
    </div>
</footer>
@endsection
