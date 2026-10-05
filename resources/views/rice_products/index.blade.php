@extends('layouts.app')

@section('content')
    <div class="container py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="fw-bold mb-1">
                    Data Beras
                </h3>

                <p class="text-muted mb-0">
                    Kelola nama beras dan harga jual
                </p>
            </div>

            <a href="{{ route('rice-products.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i>
                Tambah Beras
            </a>

        </div>


        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">

                <i class="bi bi-check-circle me-1"></i>

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>
        @endif


        {{-- ERROR --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">

                <i class="bi bi-exclamation-circle me-1"></i>

                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>
        @endif


        {{-- =====================================================
             BULK DELETE FORM
        ====================================================== --}}

        <form id="bulkDeleteForm" action="{{ route('rice-products.bulk-destroy') }}" method="POST">

            @csrf
            @method('DELETE')

        </form>


        <div class="card border-0 shadow-sm">

            {{-- TOOLBAR --}}
            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <button type="button" id="bulkDeleteButton" class="btn btn-danger btn-sm" disabled>

                            <i class="bi bi-trash me-1"></i>

                            Hapus Terpilih

                        </button>

                        <span id="selectedCount" class="text-muted small ms-2">

                            0 data dipilih

                        </span>

                    </div>

                </div>

            </div>


            {{-- TABLE --}}
            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-success">

                            <tr>

                                {{-- CHECK ALL --}}
                                <th width="50" class="text-center">

                                    <input type="checkbox" id="checkAll" class="form-check-input" title="Pilih semua">

                                </th>


                                <th width="60" class="text-center">
                                    No
                                </th>


                                <th>
                                    Kode
                                </th>


                                <th>
                                    Nama Beras
                                </th>


                                <th class="text-end">
                                    Harga / KG
                                </th>


                                <th class="text-end">
                                    Harga / Karung
                                </th>


                                <th class="text-center">
                                    Stok
                                </th>


                                <th width="150" class="text-center">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($riceProducts as $index => $rice)
                                <tr>

                                    {{-- CHECKBOX --}}
                                    <td class="text-center">

                                        <input type="checkbox" class="form-check-input row-checkbox" name="ids[]"
                                            value="{{ $rice->id }}" form="bulkDeleteForm">

                                    </td>


                                    {{-- NO --}}
                                    <td class="text-center">

                                        {{ $riceProducts->firstItem() + $index }}

                                    </td>


                                    {{-- KODE --}}
                                    <td>

                                        <span class="badge bg-secondary">

                                            {{ $rice->kode }}

                                        </span>

                                    </td>


                                    {{-- NAMA --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $rice->nama }}

                                        </div>


                                        @if ($rice->keterangan)
                                            <small class="text-muted">

                                                {{ $rice->keterangan }}

                                            </small>
                                        @endif

                                    </td>


                                    {{-- HARGA KG --}}
                                    <td class="text-end">

                                        @if ($rice->harga_kg !== null)
                                            Rp
                                            {{ number_format($rice->harga_kg, 0, ',', '.') }}
                                        @else
                                            <span class="text-muted">
                                                -
                                            </span>
                                        @endif

                                    </td>


                                    {{-- HARGA KARUNG --}}
                                    <td class="text-end">

                                        @if ($rice->harga_karung !== null)
                                            Rp
                                            {{ number_format($rice->harga_karung, 0, ',', '.') }}
                                        @else
                                            <span class="text-muted">
                                                -
                                            </span>
                                        @endif

                                    </td>


                                    {{-- STOK --}}
                                    <td class="text-center">

                                        {{ number_format($rice->stok, 0, ',', '.') }}

                                    </td>


                                    {{-- AKSI --}}
                                    <td>

                                        <div class="d-flex justify-content-center gap-1">


                                            {{-- EDIT --}}
                                            <a href="{{ route('rice-products.edit', $rice->id) }}"
                                                class="btn btn-sm btn-warning" title="Edit">

                                                <i class="bi bi-pencil"></i>

                                            </a>


                                            {{-- DELETE SATU --}}
                                            <form action="{{ route('rice-products.destroy', $rice->id) }}" method="POST"
                                                onsubmit="return confirmDelete('{{ addslashes($rice->nama) }}', this)">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </form>


                                        </div>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="8" class="text-center py-5">

                                        <div class="text-muted">

                                            <i class="bi bi-box-seam fs-1 d-block mb-2"></i>

                                            <div class="fw-semibold">

                                                Belum ada data beras

                                            </div>

                                            <small>

                                                Silakan tambahkan data beras terlebih dahulu.

                                            </small>

                                        </div>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- PAGINATION --}}
            @if ($riceProducts->hasPages())
                <div class="card-footer bg-white border-0">

                    {{ $riceProducts->links() }}

                </div>
            @endif

        </div>

    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | ELEMENT
            |--------------------------------------------------------------------------
            */

            const checkAll =
                document.getElementById('checkAll');

            const checkboxes =
                document.querySelectorAll('.row-checkbox');

            const deleteButton =
                document.getElementById('bulkDeleteButton');

            const selectedCount =
                document.getElementById('selectedCount');

            const form =
                document.getElementById('bulkDeleteForm');


            /*
            |--------------------------------------------------------------------------
            | UPDATE STATUS
            |--------------------------------------------------------------------------
            */

            function updateStatus() {

                const checked =
                    document.querySelectorAll(
                        '.row-checkbox:checked'
                    );


                const jumlah =
                    checked.length;


                /*
                | Tampilkan jumlah
                */

                selectedCount.textContent =
                    jumlah + ' data dipilih';


                /*
                | Aktif / nonaktif tombol
                */

                deleteButton.disabled =
                    jumlah === 0;


                /*
                | Status checkbox semua
                */

                if (jumlah === 0) {

                    checkAll.checked = false;

                    checkAll.indeterminate = false;

                } else if (jumlah === checkboxes.length) {

                    checkAll.checked = true;

                    checkAll.indeterminate = false;

                } else {

                    checkAll.checked = false;

                    checkAll.indeterminate = true;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | CHECK SEMUA
            |--------------------------------------------------------------------------
            */

            checkAll.addEventListener('change', function() {

                checkboxes.forEach(function(checkbox) {

                    checkbox.checked =
                        checkAll.checked;

                });

                updateStatus();

            });


            /*
            |--------------------------------------------------------------------------
            | CHECKBOX INDIVIDUAL
            |--------------------------------------------------------------------------
            */

            checkboxes.forEach(function(checkbox) {

                checkbox.addEventListener('change', function() {

                    updateStatus();

                });

            });


            /*
            |--------------------------------------------------------------------------
            | HAPUS TERPILIH
            |--------------------------------------------------------------------------
            */

            deleteButton.addEventListener('click', function() {


                const checked =
                    document.querySelectorAll(
                        '.row-checkbox:checked'
                    );


                if (checked.length === 0) {

                    alert(
                        'Pilih minimal satu data beras.'
                    );

                    return;

                }


                /*
                | Konfirmasi
                */

                const yakin =
                    confirm(
                        'Yakin ingin menghapus ' +
                        checked.length +
                        ' data beras yang dipilih?'
                    );


                if (!yakin) {

                    return;

                }


                /*
                | Password
                */

                const password =
                    prompt(
                        'Masukkan password untuk menghapus ' +
                        checked.length +
                        ' data beras:'
                    );


                /*
                | Cancel
                */

                if (password === null) {

                    return;

                }


                /*
                | Password kosong
                */

                if (password.trim() === '') {

                    alert(
                        'Password wajib diisi.'
                    );

                    return;

                }


                /*
                | Tambahkan password ke form
                */

                let passwordInput =
                    form.querySelector(
                        'input[name="delete_password"]'
                    );


                if (!passwordInput) {

                    passwordInput =
                        document.createElement('input');

                    passwordInput.type =
                        'hidden';

                    passwordInput.name =
                        'delete_password';

                    form.appendChild(
                        passwordInput
                    );

                }


                passwordInput.value =
                    password;


                /*
                | Submit
                */

                form.submit();

            });


            /*
            |--------------------------------------------------------------------------
            | INIT
            |--------------------------------------------------------------------------
            */

            updateStatus();

        });



        /*
        |--------------------------------------------------------------------------
        | HAPUS SATU
        |--------------------------------------------------------------------------
        */

        function confirmDelete(nama, form) {


            /*
            | Password
            */

            const password =
                prompt(
                    'Masukkan password untuk menghapus data "' +
                    nama +
                    '":'
                );


            /*
            | Cancel
            */

            if (password === null) {

                return false;

            }


            /*
            | Kosong
            */

            if (password.trim() === '') {

                alert(
                    'Password wajib diisi.'
                );

                return false;

            }


            /*
            | Masukkan password
            */

            let input =
                form.querySelector(
                    'input[name="delete_password"]'
                );


            if (!input) {

                input =
                    document.createElement('input');

                input.type =
                    'hidden';

                input.name =
                    'delete_password';

                form.appendChild(
                    input
                );

            }


            input.value =
                password;


            /*
            | Konfirmasi
            */

            return confirm(
                'Yakin ingin menghapus data "' +
                nama +
                '"?'
            );

        }
    </script>
@endsection
