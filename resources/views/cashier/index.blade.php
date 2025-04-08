@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="overflow-hidden">
            <!-- Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6 dark:bg-gray-900">
                <!-- Daftar Produk (Kolom Kiri) -->
                <div class="lg:col-span-2 bg-gray-50 p-6 rounded-lg border shadow dark:bg-gray-800 dark:border-gray-800">
                    <div>

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
                <hr class="my-4">
                <div class="">
                
                {{ $products->appends(request()->query())->links() }}
                </div>
            </div>

                <!-- Keranjang Belanja (Kolom Kanan) -->
                <div class="col-span-1  dark:bg-gray-800 dark:border-gray-800">
                    <div class="shadow rounded-lg">
                        <div class="bg-white rounded-lg border overflow-hidden p-4">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold mb-4 text-gray-800">Keranjang Belanja</h2>
                                <button id="startScannerBtn"
                                    class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">
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

                            <!-- Informasi Kasir -->
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
                                    <select id="paymentMethod"
                                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                                        <option value="">Pilih Pembayaran</option>
                                        @foreach($paymentMethods as $method)
                                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Input Jumlah Pembayaran -->
                                <div class="mt-4">
                                    <label for="paymentAmount" class="block text-sm font-medium text-gray-700 mb-1">Jumlah
                                        Pembayaran</label>
                                    <input type="number" id="paymentAmount"
                                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500"
                                        placeholder="Masukkan jumlah pembayaran">
                                </div>

                                <!-- Kembalian -->
                                <div class="mt-4">
                                    <label for="changeAmount"
                                        class="block text-sm font-medium text-gray-700 mb-1">Kembalian</label>
                                    <input type="text" id="changeAmount" class="w-full border rounded-lg p-2 bg-gray-100"
                                        readonly>
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
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let cart = [];
            const cartItemsEl = document.getElementById('cartItems');
            const emptyCartMessage = document.getElementById('emptyCartMessage');
            const subtotalEl = document.getElementById('subtotal');
            const totalEl = document.getElementById('total');
            const discountEl = document.getElementById('discount');
            const paymentMethodEl = document.getElementById('paymentMethod');
            const checkoutBtn = document.getElementById('checkoutBtn');
            const productSearch = document.getElementById('productSearch');
            const productGrid = document.getElementById('productGrid');
            const paymentAmountEl = document.getElementById('paymentAmount');
            const changeAmountEl = document.getElementById('changeAmount');

            const startScannerBtn = document.getElementById('startScannerBtn');
            const stopScannerBtn = document.getElementById('stopScannerBtn');
            const scannerModal = document.getElementById('scannerModal');
            const scannerElement = document.getElementById('scanner');

            // Fungsi untuk update tampilan keranjang
            function updateCart() {
                // Kosongkan dulu
                cartItemsEl.innerHTML = '';

                if (cart.length === 0) {
                    cartItemsEl.appendChild(emptyCartMessage);
                    subtotalEl.textContent = 'Rp 0';
                    totalEl.textContent = 'Rp 0';
                    discountEl.textContent = 'Rp 0';
                    checkoutBtn.disabled = true;
                    return;
                }

                // Hitung subtotal
                let subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                let discount = 0;
                let total = subtotal - discount;

                // Update summary
                subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                discountEl.textContent = 'Rp ' + discount.toLocaleString('id-ID');
                totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');

                // Tampilkan item
                cart.forEach((item, index) => {
                    const itemEl = document.createElement('div');
                    itemEl.className = 'flex justify-between items-center py-2 border-b';
                    itemEl.innerHTML = `
                                                                                <div class="flex-1">
                                                                                    <div class="font-medium">${item.name}</div>
                                                                                    <div class="flex items-center mt-1">
                                                                                        <button class="quantity-btn px-2 py-1 bg-gray-200 rounded" data-index="${index}" data-action="decrease">
                                                                                            <i class="fas fa-minus text-xs"></i>
                                                                                        </button>
                                                                                        <span class="mx-2">${item.quantity}</span>
                                                                                        <button class="quantity-btn px-2 py-1 bg-gray-200 rounded" data-index="${index}" data-action="increase">
                                                                                            <i class="fas fa-plus text-xs"></i>
                                                                                        </button>
                                                                                        <span class="ml-4 text-blue-600">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <button class="remove-btn ml-2 text-red-500 hover:text-red-700" data-index="${index}">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            `;
                    cartItemsEl.appendChild(itemEl);
                });

                // Aktifkan tombol checkout jika ada item dan metode pembayaran dipilih
                checkoutBtn.disabled = cart.length === 0 || !paymentMethodEl.value;
            }

            // Event listener untuk produk
            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const price = parseFloat(this.dataset.price);
                    const stock = parseInt(this.dataset.stock);

                    // Cek apakah produk sudah ada di keranjang
                    const existingItem = cart.find(item => item.id === id);

                    if (existingItem) {
                        if (existingItem.quantity < stock) {
                            existingItem.quantity += 1;
                        } else {
                            alert('Stok tidak mencukupi');
                        }
                    } else {
                        cart.push({
                            id,
                            name,
                            price,
                            quantity: 1,
                            stock
                        });
                    }

                    updateCart();
                });
            });

            // Event delegation untuk tombol quantity dan hapus
            cartItemsEl.addEventListener('click', function (e) {
                if (e.target.closest('.quantity-btn')) {
                    const btn = e.target.closest('.quantity-btn');
                    const index = parseInt(btn.dataset.index);
                    const action = btn.dataset.action;

                    if (action === 'increase' && cart[index].quantity < cart[index].stock) {
                        cart[index].quantity += 1;
                    } else if (action === 'decrease' && cart[index].quantity > 1) {
                        cart[index].quantity -= 1;
                    }

                    updateCart();
                }

                if (e.target.closest('.remove-btn')) {
                    const btn = e.target.closest('.remove-btn');
                    const index = parseInt(btn.dataset.index);
                    cart.splice(index, 1);
                    updateCart();
                }
            });

            // Event listener untuk metode pembayaran
            paymentMethodEl.addEventListener('change', function () {
                const total = parseFloat(totalEl.textContent.replace(/[^\d]/g, '')) || 0;

                if (this.value === '1') {
                    // Jika metode pembayaran tunai, aktifkan input jumlah pembayaran
                    paymentAmountEl.disabled = false;
                    paymentAmountEl.value = '';
                    changeAmountEl.value = 'Rp 0';
                } else if (this.value === '2' || this.value === '3') {
                    // Jika metode pembayaran QRIS atau transfer, paymentAmount = total
                    paymentAmountEl.disabled = true;
                    paymentAmountEl.value = total;
                    changeAmountEl.value = 'Rp 0';
                }

                // Perbarui tombol checkout
                checkoutBtn.disabled = cart.length === 0 || !this.value;
            });

            // Event listener untuk tombol checkout
            checkoutBtn.addEventListener('click', async function () {
                try {
                    // Tampilkan loading
                    checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                    checkoutBtn.disabled = true;

                    const response = await fetch('{{ route("cashier.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            items: cart,
                            payment_method_id: paymentMethodEl.value,
                            payment_amount: paymentAmountEl.value,
                            change_amount: changeAmountEl.value.replace(/[^0-9]/g, ''),
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }

                    if (data.success) {
                        // Tampilkan notifikasi sukses pembayaran
                        showPaymentSuccessAlert(data.message, data.transaction);


                        

                        // Reset keranjang
                        cart = [];
                        updateCart();
                        paymentMethodEl.value = '';
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showErrorAlert(error.message);
                } finally {
                    checkoutBtn.innerHTML = '<i class="fas fa-check mr-1"></i> Bayar';
                    checkoutBtn.disabled = cart.length === 0 || !paymentMethodEl.value;
                }
            });

            // Fungsi untuk menampilkan alert error
            function showErrorAlert(message) {
                const alert = document.createElement('div');
                alert.className = 'fixed top-4 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 z-50 rounded shadow-lg';
                alert.innerHTML = `
                                                                <div class="flex items-center">
                                                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                                                    <span>${message}</span>
                                                                </div>
                                                            `;
                document.body.appendChild(alert);

                setTimeout(() => {
                    alert.remove();
                }, 5000);
            }

            // Fungsi untuk menampilkan alert sukses pembayaran
            function showPaymentSuccessAlert(message, transaction) {
    Swal.fire({
        icon: 'success',
        title: 'Pembayaran Berhasil',
        text: message,
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false
    }).then(() => {
        // Cetak struk setelah alert selesai
        printReceipt(transaction);
    });
}
            

            // Event listener untuk pencarian produk
            productSearch.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('.product-card').forEach(card => {
                    const name = card.dataset.name.toLowerCase();
                    const barcode = card.dataset.barcode.toLowerCase();

                    if (name.includes(searchTerm) || barcode.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }

                });
            });

            // Event listener untuk filter kategori
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const category = this.dataset.category;
                    document.querySelectorAll('.product-card').forEach(card => {
                        if (category === 'all' || card.dataset.category === category) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Update active button
                    document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('bg-blue-600', 'text-white'));
                    this.classList.add('bg-blue-600', 'text-white');
                });
            });

            // Fungsi untuk menampilkan invoice
            function showInvoice(transaction) {
                // Implementasi tampilan invoice
                console.log('Invoice for transaction:', transaction);
                // Dalam implementasi nyata, ini akan membuka modal atau window baru
                // untuk menampilkan invoice dan mencetaknya
                window.open(`/cashier/invoice/${transaction.id}`, '_blank');
            }

            function printReceipt(transaction) {
    // Buka halaman cetak struk
    const printWindow = window.open(`/cashier/print/${transaction.id}`, '_blank');
    printWindow.focus();
    printWindow.print();
}

            // Fungsi untuk memulai scanner
            function startScanner() {
                Quagga.init({
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        target: scannerElement, // Elemen untuk menampilkan kamera
                        constraints: {
                            facingMode: "environment" // Gunakan kamera belakang
                        }
                    },
                    decoder: {
                        readers: ["code_128_reader", "ean_reader", "upc_reader"] // Format barcode yang didukung
                    }
                }, function (err) {
                    if (err) {
                        console.error("QuaggaJS error:", err);
                        return;
                    }
                    Quagga.start();
                });

                // Event ketika barcode terdeteksi
                Quagga.onDetected(function (data) {
                    const barcode = data.codeResult.code;
                    console.log("Barcode detected:", barcode);


                    // Cari produk berdasarkan barcode
                    const productCard = Array.from(document.querySelectorAll('.product-card')).find(card => card.dataset.barcode === barcode);

                    
                    if (productCard) {
                        const id = productCard.dataset.id;
                        const name = productCard.dataset.name;
                        const price = parseFloat(productCard.dataset.price);
                        const stock = parseInt(productCard.dataset.stock);

                        // Tambahkan produk ke keranjang
                        const existingItem = cart.find(item => item.id === id);

                        if (existingItem) {
                            if (existingItem.quantity < stock) {
                                existingItem.quantity += 1;
                            } else {
                                alert('Stok tidak mencukupi');
                            }
                        } else {
                            cart.push({
                                id,
                                name,
                                price,
                                quantity: 1,
                                stock
                            });
                        }

                        updateCart();
                        alert(`Produk "${name}" berhasil ditambahkan ke keranjang.`);
                    } else {
                        alert('Produk dengan barcode ini tidak ditemukan');
                    }

                    // Hentikan scanner setelah barcode ditemukan
                    stopScanner();
                });
            }

            // Fungsi untuk menghentikan scanner
            function stopScanner() {
                Quagga.stop();
                scannerModal.classList.add('hidden');
            }

            // Event listener untuk tombol mulai scanner
            startScannerBtn.addEventListener('click', function () {
                scannerModal.classList.remove('hidden');
                startScanner();
            });

            // Event listener untuk tombol berhenti scanner
            stopScannerBtn.addEventListener('click', function () {
                stopScanner();
            });

            paymentAmountEl.addEventListener('input', function () {
                const total = parseFloat(totalEl.textContent.replace(/[^\d]/g, '')) || 0;
                const paymentMethod = paymentMethodEl.value;

                let change = 0;

                if (paymentMethod === '1') {
                    // Jika metode pembayaran tunai, hitung kembalian
                    const paymentAmount = parseFloat(this.value) || 0;
                    change = paymentAmount - total;
                } else if (paymentMethod === '2' || paymentMethod === '3') {
                    // Jika metode pembayaran QRIS atau transfer, paymentAmount = total
                    this.value = total;
                    change = 0;
                }

                // Tampilkan kembalian
                changeAmountEl.value = `Rp ${change.toLocaleString('id-ID')}`;
            });


        });
    </script>
@endsection