<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="text" name="product_title" placeholder="Nom de l'EPI (ex: Gants)" required>
    <input type="number" name="product_price" placeholder="Prix" step="0.01" required>
    <input type="number" name="stock_qty" placeholder="Quantité en stock" required>
    <input type="file" name="product_image">
    <button type="submit">Ajouter au catalogue</button>
</form>