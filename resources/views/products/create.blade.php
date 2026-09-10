@extends('layouts.app')

@section('content')
    <h3 class="mb-4">

        Tambah Barang

    </h3>

    <form action="{{ route('products.store') }}" method="POST">

        @include('products.form')

    </form>
@endsection
