@extends('layouts.app')

@section('content')
    <h3 class="mb-4">
        Dashboard
    </h3>

    <div class="dashboard-scroll">

        <div class="dashboard-card">
            <div class="card border-0 shadow h-100">
                <div class="card-body text-center d-flex flex-column">

                    <h1 class="text-success">📦</h1>

                    <h5>Barang</h5>

                    <h2>{{ $barang }}</h2>

                    <div class="mt-auto">
                        <a href="{{ route('products.index') }}" class="btn btn-success btn-sm mt-2">
                            Buka
                        </a>
                    </div>

                </div>
            </div>
        </div>


        <div class="dashboard-card">
            <div class="card border-0 shadow h-100">
                <div class="card-body text-center d-flex flex-column">

                    <h1>🛒</h1>

                    <h5>Penjualan</h5>

                    <h2>{{ $penjualan }}</h2>

                    <div class="mt-auto">
                        <a href="{{ route('transaksi.index') }}" class="btn btn-primary btn-sm mt-2">
                            Buka
                        </a>
                    </div>

                </div>
            </div>
        </div>


        <div class="dashboard-card">
            <div class="card border-0 shadow h-100">
                <div class="card-body text-center d-flex flex-column">

                    <h1>💳</h1>

                    <h5>Kasbon</h5>

                    <h2>{{ $kasbon }}</h2>

                    <div class="mt-auto">
                        <button class="btn btn-warning btn-sm mt-2" disabled>
                            Segera
                        </button>
                    </div>

                </div>
            </div>
        </div>


        <div class="dashboard-card">
            <div class="card border-0 shadow h-100">
                <div class="card-body text-center d-flex flex-column">

                    <h1>💰</h1>

                    <h5>Lunas</h5>

                    <h2>{{ $lunas }}</h2>

                    <div class="mt-auto">
                        <button class="btn btn-secondary btn-sm mt-2" disabled>
                            Segera
                        </button>
                    </div>

                </div>
            </div>
        </div>


        <div class="dashboard-card">
            <div class="card border-0 shadow h-100">
                <div class="card-body text-center d-flex flex-column">

                    <h1 class="text-danger">📤</h1>

                    <h5>Pengeluaran</h5>

                    <h2>{{ $pengeluaran }}</h2>

                    <div class="mt-auto">
                        <a href="{{ route('pengeluaran.index') }}" class="btn btn-danger btn-sm mt-2">
                            Buka
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>


    <style>
        /* Desktop */
        .dashboard-scroll {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }

        .dashboard-card {
            min-width: 0;
        }


        /* HP / layar kecil */
        @media (max-width: 991.98px) {

            .dashboard-scroll {
                display: flex;
                overflow-x: auto;
                gap: 1rem;
                padding-bottom: 10px;

                scroll-snap-type: x mandatory;

                -webkit-overflow-scrolling: touch;
            }

            .dashboard-card {
                flex: 0 0 85%;
                scroll-snap-align: start;
            }

            /* Hilangkan scrollbar */
            .dashboard-scroll::-webkit-scrollbar {
                display: none;
            }

            .dashboard-scroll {
                scrollbar-width: none;
            }

        }
    </style>
@endsection
