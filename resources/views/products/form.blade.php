@csrf

<div class="mb-3">

    <label>Kode Barang</label>

    <input type="text" name="kode" class="form-control" value="{{ old('kode', $product->kode ?? '') }}">

</div>

<div class="mb-3">

    <label>Nama Barang</label>

    <input type="text" name="nama" class="form-control" value="{{ old('nama', $product->nama ?? '') }}">

</div>

<div class="mb-3">

    <label>Stok</label>

    <input type="number" name="stok" class="form-control" value="{{ old('stok', $product->stok ?? 0) }}">

</div>

<div class="mb-3">

    <label>Harga Beli</label>

    <input type="number" name="harga_beli" class="form-control"
        value="{{ old('harga_beli', $product->harga_beli ?? 0) }}">

</div>

<div class="mb-3">

    <label>Harga Jual</label>

    <input type="number" name="harga_jual" class="form-control"
        value="{{ old('harga_jual', $product->harga_jual ?? 0) }}">

</div>

<div class="mb-3">

    <label>Keterangan</label>

    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $product->keterangan ?? '') }}</textarea>

</div>

<button class="btn btn-success">

    Simpan

</button>

<a href="{{ route('products.index') }}" class="btn btn-secondary">

    Kembali

</a>
