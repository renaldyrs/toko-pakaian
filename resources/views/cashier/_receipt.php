<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Toko</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-cash-register mr-2"></i> Kasir Toko
            </h1>
            <div class="flex items-center space-x-4">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                    <i class="fas fa-user mr-1"></i> Kasir 1
                </span>
                <span class="text-gray-500">Shift: Pagi</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Daftar Produk -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-700">
                        <i class="fas fa-boxes mr-2"></i> Daftar Produk
                    </h2>
                    <div class="relative w-64">
                        <input type="text" placeholder="Cari produk..." 
                               class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <!-- Contoh Produk 1 -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-blue-400 transition-all">
                        <div class="h-32 bg-gray-200 rounded-md mb-3 flex items-center justify-center">
                            <i class="fas fa-image text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="font-medium text-gray-800">Baju Kaos Polos</h3>
                        <p class="text-blue-600 font-bold my-1">Rp 75.000</p>
                        <p class="text-sm text-gray-500 mb-2">Stok: 15</p>
                        <div class="flex items-center">
                            <input type="number" min="1" max="15" value="1" 
                                   class="w-16 px-2 py-1 border rounded-l-md border-gray-300">
                            <button class="bg-blue-500 text-white px-3 py-1 rounded-r-md hover:bg-blue-600 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Contoh Produk 2 -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-blue-400 transition-all">
                        <div class="h-32 bg-gray-200 rounded-md mb-3 flex items-center justify-center">
                            <i class="fas fa-image text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="font-medium text-gray-800">Celana Jeans</h3>
                        <p class="text-blue-600 font-bold my-1">Rp 150.000</p>
                        <p class="text-sm text-gray-500 mb-2">Stok: 8</p>
                        <div class="flex items-center">
                            <input type="number" min="1" max="8" value="1" 
                                   class="w-16 px-2 py-1 border rounded-l-md border-gray-300">
                            <button class="bg-blue-500 text-white px-3 py-1 rounded-r-md hover:bg-blue-600 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Produk lainnya... -->
                </div>
            </div>

            <!-- Keranjang Belanja -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-6">
                    <i class="fas fa-shopping-cart mr-2"></i> Keranjang Belanja
                </h2>

                <div class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                    <!-- Item 1 -->
                    <div class="flex justify-between items-center border-b pb-3">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-gray-200 rounded-md mr-3 flex-shrink-0"></div>
                            <div>
                                <h4 class="font-medium">Baju Kaos Polos</h4>
                                <p class="text-sm text-gray-500">Rp 75.000 x 2</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="font-bold mr-4">Rp 150.000</span>
                            <button class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex justify-between items-center border-b pb-3">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-gray-200 rounded-md mr-3 flex-shrink-0"></div>
                            <div>
                                <h4 class="font-medium">Celana Jeans</h4>
                                <p class="text-sm text-gray-500">Rp 150.000 x 1</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="font-bold mr-4">Rp 150.000</span>
                            <button class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Pembayaran -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium">Rp 300.000</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Diskon:</span>
                        <span class="font-medium text-green-600">- Rp 0</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t pt-2 mt-2">
                        <span>Total:</span>
                        <span>Rp 300.000</span>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="mb-6">
                    <h3 class="font-medium mb-3">Metode Pembayaran</h3>
                    <div class="grid grid-cols-3 gap-2">
                        <button class="py-2 border rounded-lg hover:bg-blue-50 hover:border-blue-300 transition">
                            Tunai
                        </button>
                        <button class="py-2 border rounded-lg hover:bg-blue-50 hover:border-blue-300 transition">
                            Transfer
                        </button>
                        <button class="py-2 border rounded-lg hover:bg-blue-50 hover:border-blue-300 transition">
                            Kartu
                        </button>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="space-y-3">
                    <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition flex items-center justify-center">
                        <i class="fas fa-check-circle mr-2"></i> Proses Pembayaran
                    </button>
                    <button class="w-full bg-gray-200 text-gray-800 py-3 rounded-lg font-bold hover:bg-gray-300 transition flex items-center justify-center">
                        <i class="fas fa-times-circle mr-2"></i> Batalkan Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pembayaran -->
    <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Konfirmasi Pembayaran</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 mb-1">Total Pembayaran</label>
                    <div class="text-2xl font-bold">Rp 300.000</div>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-1">Dibayar</label>
                    <input type="number" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-1">Kembalian</label>
                    <div class="text-xl font-bold text-green-600">Rp 50.000</div>
                </div>
                
                <div class="pt-4">
                    <button onclick="printInvoice()" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition flex items-center justify-center">
                        <i class="fas fa-print mr-2"></i> Cetak Invoice
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk modal
        function openModal() {
            document.getElementById('paymentModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }
        
        function printInvoice() {
            // Fungsi untuk mencetak invoice
            alert('Invoice berhasil dicetak!');
            closeModal();
        }
    </script>
</body>
</html>