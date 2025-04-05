
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Daftar Produk</h1>
        <button onclick="openModal()" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 transition">
            <i class="fas fa-plus"></i> Tambah Produk
        </button>
    </div>

    <!-- Tabel Daftar Produk -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-200 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Gambar</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kode Produk</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Barcode</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Produk</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stok</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($products as $product)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 grid justify-items-center">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-16 h-16 object-cover rounded-md shadow-md">
                            @else
                                <span class="text-gray-400">Tidak ada gambar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">{{ $product->code }}</td>
                        <td class="px-6 py-4 grid justify-items-center">
                            @if ($product->barcode)
                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->barcode, 'C128') }}"
                                    alt="barcode" class="w-32 h-12">
                            @else
                                <span class="text-gray-400">Tidak ada barcode</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">{{ $product->stock }}</td>
                        <td class="px-6 py-4 text-center flex justify-center space-x-2">
                            <a href="{{ route('products.edit', $product->id) }}"
                                class="text-blue-500 hover:text-blue-700 transition">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            <a href="{{ route('products.print-barcodes', $product->id) }}"
                                class="text-green-500 hover:text-green-700 transition" target="_blank">
                                <i class="fas fa-barcode"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4">
        {{ $products->links('vendor.tailwind') }}
    </div>
</div>

<!-- Modal Tambah Produk -->
<div id="addProductModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-full max-w-md dark:bg-gray-800">
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Tambah Produk</h2>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Produk</label>
                <input type="text" name="name" id="name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                    required>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Harga</label>
                <input type="number" name="price" id="price"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                    required>
            </div>
            <div class="mb-4">
                <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Stok</label>
                <input type="number" name="stock" id="stock"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                    required>
            </div>
            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Kategori</label>
                <select name="category_id" id="category_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                    required>
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Deskripsi</label>
                <textarea name="description" id="description"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"></textarea>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Gambar Produk</label>
                <input type="file" name="image" id="image"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeModal()"
                    class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2 hover:bg-gray-600 transition">Batal</button>
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi untuk membuka modal
    function openModal() {
        document.getElementById('addProductModal').classList.remove('hidden');
        document.getElementById('addProductModal').classList.add('flex');
    }

    // Fungsi untuk menutup modal
    function closeModal() {
        document.getElementById('addProductModal').classList.remove('flex');
        document.getElementById('addProductModal').classList.add('hidden');
    }

    // Tutup modal saat mengklik di luar modal
    document.getElementById('addProductModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endsection