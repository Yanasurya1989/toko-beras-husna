<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sistem Penjualan Beras</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
        }

        .table th {
            background: #198754;
            color: white;
        }
    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-success">

        <div class="container">

            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">

                🌾 TOKO HUSNA

            </a>

            <div>

                <a href="{{ route('dashboard') }}" class="btn btn-success text-white">

                    Dashboard

                </a>

                <a href="{{ route('products.index') }}" class="btn btn-success text-white">

                    Barang

                </a>

                <a href="{{ route('sales.index') }}" class="btn btn-success text-white">

                    Penjualan

                </a>

            </div>

        </div>

    </nav>



    <div class="container mt-4">

        @if (session('success'))
            <div class="alert alert-success">

                {{ session('success') }}

            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">

                {{ session('error') }}

            </div>
        @endif

        @yield('content')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
