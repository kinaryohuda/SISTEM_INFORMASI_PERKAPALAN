<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
        }
        .maintenance-container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .maintenance-container h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .maintenance-container p {
            font-size: 1.2rem;
            color: #6c757d;
            margin-bottom: 30px;
        }
        .maintenance-icon {
            font-size: 5rem;
            color: #0d6efd;
            margin-bottom: 20px;
        }
        .btn-home {
            background-color: #0d6efd;
            color: #fff;
        }
        .btn-home:hover {
            background-color: #0b5ed7;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <i class="bi bi-tools maintenance-icon"></i>
        <h1>Under Maintenance</h1>
        <p>Fitur ini sedang dalam tahap pengembangan. Mohon bersabar, kami akan kembali secepatnya!</p>
        <a href="/" class="btn btn-home">Kembali ke Beranda <i class="bi bi-house-door-fill"></i></a>
    </div>

    <!-- Bootstrap JS (optional, jika butuh interaksi) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
