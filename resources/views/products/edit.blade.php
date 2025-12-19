<h2>Edit Produk</h2>

<form action="{{ route('products.update', $product) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ $product->name }}">
    <input type="number" name="price" value="{{ $product->price }}">
    <input type="number" name="stock" value="{{ $product->stock }}">

    <button type="submit">Update</button>
</form>
