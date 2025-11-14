<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Informasi Perizinan Kapal</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icons/icons_sistem_infomasi_perkapalan.png') }}">


    {{-- CDN Bootstrap--}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    {{-- CDN Bootstrap --}}
</head>

<body>
    <div class="">
        @include('partials.header-navbar-public')
        <div class="">
            @yield('content')
        </div>
    </div>

    {{-- CDN Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    {{-- CDN Bootstrap --}}
@stack('scripts')
</body>

</html>