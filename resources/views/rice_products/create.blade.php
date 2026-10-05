@extends('layouts.app')

@section('content')

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-lg-7">

                {{-- HEADER --}}
                <div class="mb-4">
                    <h3 class="fw-bold mb-1">
                        Tambah Data Beras
                    </h3>

                    <p class="text-muted mb-0">
                        Tambahkan nama beras dan harga jual.
                    </p>
                </div>

                {{-- VALIDATION ERROR --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Periksa kembali data:</strong>

                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        <form action="{{ route('rice-products.store') }}" method="POST">

                            @csrf

                            {{-- KODE --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Kode
                                </label>

                                <input type="text" name="kode" class="form-control" value="{{ old('kode') }}"
                                    placeholder="Contoh: BR001" required>

                            </div>

                            {{-- NAMA --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Nama Beras
                                </label>

                                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}"
                                    placeholder="Contoh: Angsa" required>

                            </div>

                            <div class="row">

                                {{-- HARGA KG --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-semibold">
                                        Harga / KG
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            Rp
                                        </span>

                                        <input type="number" name="harga_kg" class="form-control"
                                            value="{{ old('harga_kg') }}" min="0" placeholder="14500">

                                    </div>

                                    <small class="text-muted">
                                        Kosongkan jika tidak dijual per KG.
                                    </small>

                                </div>

                                {{-- HARGA KARUNG --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-semibold">
                                        Harga / Karung
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            Rp
                                        </span>

                                        <input type="number" name="harga_karung" class="form-control"
                                            value="{{ old('harga_karung') }}" min="0" placeholder="352500">

                                    </div>

                                    <small class="text-muted">
                                        Kosongkan jika tidak dijual per karung.
                                    </small>

                                </div>

                            </div>

                            {{-- STOK --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Stok
                                </label>

                                <input type="number" name="stok" class="form-control" value="{{ old('stok', 0) }}"
                                    min="0">

                            </div>

                            {{-- KETERANGAN --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Keterangan
                                </label>

                                <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>

                            </div>

                            {{-- BUTTON --}}
                            <div class="d-flex justify-content-between">

                                <a href="{{ route('rice-products.index') }}" class="btn btn-light border">

                                    <i class="bi bi-arrow-left me-1"></i>
                                    Kembali

                                </a>

                                <button type="submit" class="btn btn-success">

                                    <i class="bi bi-save me-1"></i>
                                    Simpan

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
