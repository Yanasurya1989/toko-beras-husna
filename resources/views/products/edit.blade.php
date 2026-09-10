@extends('layouts.app')

@section('content')
    <h3 class="mb-4">

        Edit Barang

    </h3>

    <form action="{{ route('products.update', $product) }}" method="POST">

        @csrf

        @method('PUT')

        @include('products.form')

    </form>
@endsection
