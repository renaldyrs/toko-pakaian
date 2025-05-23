@extends('layouts.app')

@section('content')
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
    </div>
    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="description" id="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
    </div>
    <div class="mb-4">
        <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
        <input type="number" name="price" id="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
    </div>
    <div class="mb-4">
        <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
        <input type="number" name="stock" id="stock" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
    </div>
    <div class="mb-4">
        <label for="barcode" class="block text-sm font-medium text-gray-700">Barcode</label>
        <input type="text" name="barcode" id="barcode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div class="mb-4">
        <label for="image" class="block text-sm font-medium text-gray-700">Gambar Produk</label>
        <input type="file" name="image" id="image" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <!-- Input untuk Ukuran dan Stok -->
    <div class="mb-4">
        <label for="sizes" class="block text-sm font-medium text-gray-700">Ukuran dan Stok</label>
        <div id="size-container">
            <div class="flex items-center gap-4 mb-2">
                <input type="text" name="sizes[0][name]" placeholder="Ukuran (contoh: M)" class="block w-1/2 rounded-md border-gray-300 shadow-sm" required>
                <input type="number" name="sizes[0][stock]" placeholder="Stok" class="block w-1/2 rounded-md border-gray-300 shadow-sm" required>
                <button type="button" class="bg-red-500 text-white px-2 py-1 rounded-md remove-size">Hapus</button>
            </div>
        </div>
        <button type="button" id="add-size" class="bg-green-500 text-white px-4 py-2 rounded-md">Tambah Ukuran</button>
    </div>
    
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
        <i class="fas fa-save"></i> Simpan
    </button>
</form>
@endsection