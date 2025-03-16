@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Produk</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>

    <div class="row">
        @foreach ($products as $product)
        <div class="col-md-3 mb-3">
            <div class="card">
                <!-- Gambar Produk -->
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ $product->description }}</p>
                    <p class="card-text"><strong>Supplier:</strong> {{ $product->supplier->name ?? 'Tidak ada supplier' }}</p>
                    <p class="card-text"><strong>Kategori:</strong> {{ $product->category->name ?? 'Tidak ada kategori' }}</p>
                    <p class="card-text"><strong>Harga:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="card-text"><strong>Stok:</strong> {{ $product->stock }}</p>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection