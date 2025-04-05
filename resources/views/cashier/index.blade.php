@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex flex-col md:flex-row gap-6">

            <!-- Panel Produk -->
            <div class="w-full md:w-2/3 bg-white rounded-lg shadow-md p-4">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Daftar Produk</h2>

                <!-- Search Bar -->
                <div class="mb-4 relative">
                    <input type="text" id="productSearch" placeholder="Cari produk..."
                        class="w-full p-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>

                <!-- Kategori -->
                <div class="mb-4 flex flex-wrap gap-2">
                    <button
                        class="category-btn px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm hover:bg-blue-200 active:bg-blue-300"
                        data-category="all">Semua</button>
                    @foreach($categories as $category)
                        <button
                            class="category-btn px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm hover:bg-gray-200 active:bg-gray-300"
                            data-category="{{ $category->id }}">{{ $category->name }}</button>
                    @endforeach
                </div>

                <!-- Daftar Produk -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3" id="productGrid">
                    @foreach($products as $product)
                        <div class="product-card p-3 border rounded-lg hover:shadow-md transition-shadow cursor-pointer"
                            data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}"
                            data-stock="{{ $product->stock }}" data-category="{{ $product->category_id }}"
                            data-barcode="{{ $product->barcode }}">
                            <div class="h-32 bg-gray-100 rounded-md mb-2 overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image fa-2x"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex justify-between items-center mt-1">
                                <span class="font-medium text-gray-800 truncate">{{ $product->name }}</span>
                                <span>{{ $product->barcode }}</span>
                            </div>

                            <div class="flex justify-between items-center mt-1">
                                <span class="text-blue-600 font-bold">Rp {{ number_format($product->price) }}</span>
                                <span class="text-xs text-gray-500">Stok: {{ $product->stock }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Panel Keranjang -->
            <div class="w-full md:w-1/3 bg-white rounded-lg shadow-md p-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold mb-4 text-gray-800">Keranjang Belanja</h2>
                    <!-- Tombol untuk membuka scanner -->
                    <div class="mb-4">
                        <button id="startScannerBtn" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-camera mr-1"></i> 
                        </button>
                    </div>

                    <!-- Modal Scanner -->
                    <div id="scannerModal"
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                        <div class="bg-white rounded-lg shadow-lg p-4 w-full max-w-md">
                            <h2 class="text-lg font-bold mb-4">Scan Barcode</h2>
                            <div id="scanner" style="width: 100%; height: 300px; background: #f0f0f0;"></div>
                            <button id="stopScannerBtn"
                                class="mt-4 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700">
                                <i class="fas fa-times mr-1"></i> Tutup
                            </button>
                        </div>
                    </div>
                </div>


                <div class="border rounded-lg p-3 mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium">Kasir:</span>
                        <span class="font-bold">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Tanggal:</span>
                        <span class="text-sm">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <!-- Daftar Item -->
                <div class="mb-4 max-h-96 overflow-y-auto" id="cartItems">
                    <!-- Item akan ditambahkan via JavaScript -->
                    <div class="text-center text-gray-500 py-10" id="emptyCartMessage">
                        <i class="fas fa-shopping-cart fa-3x mb-2"></i>
                        <p>Keranjang kosong</p>
                    </div>
                </div>

                <!-- Ringkasan Pembayaran -->
                <div class="border-t pt-3">
                    <div class="flex justify-between mb-1">
                        <span>Subtotal:</span>
                        <span id="subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span>Diskon:</span>
                        <span id="discount">Rp 0</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total:</span>
                        <span id="total">Rp 0</span>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <select id="paymentMethod" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Pembayaran</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-6 grid grid-cols-2 gap-2">
                        <button id="cancelBtn"
                            class="bg-gray-200 text-gray-800 py-2 rounded-lg hover:bg-gray-300 transition">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button id="checkoutBtn"
                            class="bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                            disabled>
                            <i class="fas fa-check mr-1"></i> Bayar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('cashier.script')
    
@endsection