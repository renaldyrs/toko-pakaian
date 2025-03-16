<div class="form-group">
    <label for="supplier_id">Supplier</label>
    <select name="supplier_id" id="supplier_id" class="form-control">
        <option value="">Pilih Supplier</option>
        @foreach ($suppliers as $supplier)
            <option value="{{ $supplier->id }}" {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>
                {{ $supplier->name }}
            </option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="code">Kode Produk</label>
    <input type="text" name="code" id="code" class="form-control" value="{{ $product->code }}" readonly>
</div>