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
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
        <i class="fas fa-save"></i> Simpan
    </button>
</form>
@endsection