@extends('layouts.app')

@section('content')

<h3 class="mb-4">
    Dashboard
</h3>

<div class="row">

    <div class="col-md-3">

        <div class="card border-0 shadow">

            <div class="card-body text-center">

                <h1 class="text-success">

                    📦

                </h1>

                <h5>Barang</h5>

                <h2>{{ $barang }}</h2>

                <a href="{{ route('products.index') }}"
                    class="btn btn-success btn-sm mt-2">

                    Buka

                </a>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card border-0 shadow">

            <div class="card-body text-center">

                <h1>

                    🛒

                </h1>

                <h5>Penjualan</h5>

                <h2>{{ $penjualan }}</h2>

                <a href="{{ route('sales.index') }}"
                    class="btn btn-primary btn-sm mt-2">

                    Buka

                </a>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card border-0 shadow">

            <div class="card-body text-center">

                <h1>

                    💳

                </h1>

                <h5>Kasbon</h5>

                <h2>{{ $kasbon }}</h2>

                <button
                    class="btn btn-warning btn-sm mt-2"
                    disabled>

                    Segera

                </button>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card border-0 shadow">

            <div class="card-body text-center">

                <h1>

                    💰

                </h1>

                <h5>Lunas</h5>

                <h2>{{ $lunas }}</h2>

                <button
                    class="btn btn-secondary btn-sm mt-2"
                    disabled>

                    Segera

                </button>

            </div>

        </div>

    </div>

</div>

@endsection