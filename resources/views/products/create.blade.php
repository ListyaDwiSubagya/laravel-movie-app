<h2>Tambah Produk</h2>

<form action="{{ route('products.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Nama Produk">
    <input type="number" name="price" placeholder="Harga">
    <input type="number" name="stock" placeholder="Stok">
    <button type="submit">Simpan</button>
</form>
