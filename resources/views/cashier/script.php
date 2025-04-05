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
                        payment_method_id: paymentMethodEl.value
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }

                if (data.success) {
                    // Tampilkan notifikasi sukses
                    showSuccessAlert('Transaksi berhasil diproses');

                    // Tampilkan invoice
                    showInvoice(data.transaction);

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

        // Fungsi untuk menampilkan alert sukses
        function showSuccessAlert(message) {
            const alert = document.createElement('div');
            alert.className = 'fixed top-4 right-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 z-50 rounded shadow-lg';
            alert.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>${message}</span>
            </div>
        `;
            document.body.appendChild(alert);

            setTimeout(() => {
                alert.remove();
            }, 3000);
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

    });
</script>