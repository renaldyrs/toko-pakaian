


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Kasir</h1>

        <!-- Tombol Buka Scanner -->
        <button onclick="startScanner()" class="bg-blue-500 text-white px-4 py-2 rounded-md mb-4">
            <i class="fas fa-barcode"></i> Buka Scanner
        </button>

        <!-- Area Scanner -->
        <div id="scanner" class="hidden mb-4">
            <div id="qr-reader" class="w-full max-w-md mx-auto"></div>
            <button onclick="stopScanner()" class="mt-2 bg-red-500 text-white px-4 py-2 rounded-md">
                <i class="fas fa-times"></i> Tutup Scanner
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Daftar Produk -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Pilih Produk</h2>
                <div class="space-y-4">
                    @foreach ($products as $product)
                    <div class="flex items-center justify-between p-4 border rounded-lg">
                        <div>
                            <h3 class="font-semibold">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600">Stok: {{ $product->stock }}</p>
                        </div>
                        <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                            <i class="fas fa-cart-plus"></i> Tambah
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Keranjang Belanja -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Keranjang Belanja</h2>
                <div id="cartItems" class="space-y-4">
                    <!-- Item keranjang akan ditambahkan di sini oleh JavaScript -->
                </div>
                <div class="mt-6">
                    <h3 class="text-lg font-semibold">Total: <span id="cartTotal">Rp 0</span></h3>
                    <div class="mt-4">
                        <label for="payment" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran</label>
                        <input type="number" id="payment" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Masukkan jumlah pembayaran">
                    </div>
                    <div class="mt-4">
                        <label for="change" class="block text-sm font-medium text-gray-700">Kembalian</label>
                        <input type="number" id="change" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" readonly>
                    </div>
                    <div class="mt-4">
        <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
        <select id="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Pilih Metode Pembayaran</option>
            @foreach ($paymentMethods as $method)
                <option value="{{ $method->id }}">{{ $method->name }}</option>
            @endforeach
        </select>
    </div>
                    
                    <button onclick="processPayment()" class="mt-4 w-full bg-green-500 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-check"></i> Proses Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cart = []; // Menyimpan data keranjang
        let html5QrCode; // Variabel untuk menyimpan instance scanner

        // Fungsi untuk memulai scanner
        function startScanner() {
            document.getElementById('scanner').classList.remove('hidden');

            // Inisialisasi scanner
            html5QrCode = new Html5Qrcode("qr-reader");
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                // Cari produk berdasarkan barcode (decodedText)
                const product = {!! $products->toJson() !!}.find(p => p.barcode === decodedText);
                if (product) {
                    // Tambahkan produk ke keranjang
                    console.log("Produk ditemukan:", product);

                    addToCart(product.id, product.name, product.price);
                    stopScanner(); // Tutup scanner setelah berhasil memindai
                } else {
                    alert('Produk tidak ditemukan!');
                }
            };

            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            // Mulai scanning
            html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
                .catch(err => {
                    console.error("Gagal memulai scanner:", err);
                });
        }

        // Fungsi untuk menghentikan scanner
        function stopScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    console.log("Scanner dihentikan.");
                    document.getElementById('scanner').classList.add('hidden');
                }).catch(err => {
                    console.error("Gagal menghentikan scanner:", err);
                });
            }
        }

        // Fungsi untuk menambahkan produk ke keranjang
        function addToCart(productId, productName, productPrice) {
            const existingItem = cart.find(item => item.productId === productId);

            if (existingItem) {
                existingItem.quantity += 1; // Tambah jumlah jika sudah ada
            } else {
                cart.push({
                    productId: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1,
                });
            }

            updateCartView();
        }

        // Fungsi untuk mengurangi jumlah produk di keranjang
        function decreaseQuantity(productId) {
            const item = cart.find(item => item.productId === productId);
            if (item) {
                item.quantity -= 1;
                if (item.quantity <= 0) {
                    cart = cart.filter(item => item.productId !== productId); // Hapus item jika jumlah <= 0
                }
            }
            updateCartView();
        }

        // Fungsi untuk mengupdate tampilan keranjang
        function updateCartView() {
            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');
            let total = 0;

            cartItems.innerHTML = ''; // Kosongkan keranjang

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;

                const itemElement = document.createElement('div');
                itemElement.className = 'flex items-center justify-between p-4 border rounded-lg';
                itemElement.innerHTML = `
                    <div>
                        <h3 class="font-semibold">${item.name}</h3>
                        <p class="text-sm text-gray-600">Rp ${item.price.toLocaleString()} x ${item.quantity}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="decreaseQuantity(${item.productId})" class="bg-red-500 text-white px-2 py-1 rounded-md">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span>${item.quantity}</span>
                        <button onclick="addToCart(${item.productId}, '${item.name}', ${item.price})" class="bg-blue-500 text-white px-2 py-1 rounded-md">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                `;
                cartItems.appendChild(itemElement);
            });

            cartTotal.textContent = `Rp ${total.toLocaleString()}`;
            calculateChange(); // Panggil fungsi hitung kembalian
        }

        // Fungsi untuk menghitung kembalian
        function calculateChange() {
            const payment = parseFloat(document.getElementById('payment').value) || 0;
            const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            const change = payment - total;

            document.getElementById('change').value = change >=0 ? change : 0;
        }

        // Event listener untuk input pembayaran
        document.getElementById('payment').addEventListener('input', calculateChange);

        // Fungsi untuk memproses pembayaran
        function processPayment() {
            const payment = parseFloat(document.getElementById('payment').value) || 0;
            const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

            if (payment < total) {
                alert('Jumlah pembayaran kurang!');
                return;
            }

            alert('Pembayaran berhasil!');
            cart = []; // Kosongkan keranjang
            updateCartView();
            document.getElementById('payment').value = '';
            document.getElementById('change').value = '';
        }
    </script>
</body>
</html>