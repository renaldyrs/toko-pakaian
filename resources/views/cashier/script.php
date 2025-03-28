
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cart = []; // Menyimpan data keranjang
        const cartTableBody = document.querySelector('#cartTable tbody');
        const cartTotal = document.getElementById('cartTotal');
        const paymentAmountInput = document.getElementById('paymentAmount');
        const changeAmount = document.getElementById('changeAmount');
        const paymentAmountHidden = document.getElementById('paymentAmountInput');
        const changeAmountHidden = document.getElementById('changeAmountInput');

        // Fungsi untuk menambahkan produk ke keranjang
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-product-id');
                const quantityInput = document.getElementById(`quantity-${productId}`);
                const quantity = parseInt(quantityInput.value);

                // Ambil stok produk dari tabel
                const productRow = this.closest('tr');
                const productStock = parseInt(productRow.querySelector('td:nth-child(3)').textContent);

                if (quantity > 0 && quantity <= productStock) {
                    // Cek apakah produk sudah ada di keranjang
                    const existingItem = cart.find(item => item.productId === productId);
                    if (existingItem) {
                        // Jika jumlah yang diminta melebihi stok, tampilkan pesan error
                        if (existingItem.quantity + quantity > productStock) {
                            alert(`Stok produk tidak mencukupi. Stok tersedia: ${productStock}`);
                            return;
                        }
                        existingItem.quantity += quantity; // Tambah jumlah jika sudah ada
                        existingItem.subtotal = Math.round(existingItem.price * existingItem.quantity); // Bulatkan ke integer
                    } else {
                        // Tambahkan produk baru ke keranjang
                        const product = {
                            productId: productId,
                            name: productRow.querySelector('td:nth-child(1)').textContent,
                            price: Math.round(parseFloat(productRow.querySelector('td:nth-child(2)').textContent.replace('Rp ', '').replace(/\./g, ''))), // Bulatkan ke integer
                            quantity: quantity,
                            subtotal: 0
                        };
                        product.subtotal = Math.round(product.price * product.quantity); // Bulatkan ke integer
                        cart.push(product);
                    }

                    // Update tampilan keranjang
                    updateCartView();
                } else {
                    alert(`Jumlah produk tidak valid atau melebihi stok. Stok tersedia: ${productStock}`);
                }
            });
        });

        // Fungsi untuk mengupdate tampilan keranjang
        function updateCartView() {
            cartTableBody.innerHTML = ''; // Kosongkan tabel
            let total = 0;

            cart.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                                            <td>${item.name}</td>
                                            <td>
                                                <button type="button" class="text-blue-500 hover:text-blue-700 decrease-quantity" data-product-id="${item.productId}">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <span class="mx-1">${item.quantity}</span>
                                                <button type="button" class="text-blue-500 hover:text-blue-700 increase-quantity" data-product-id="${item.productId}">
                                                    <i class="fas fa-plus"></i>
                                                </button>

                                            </td>
                                            <td>Rp ${item.subtotal.toLocaleString()}</td>
                                            <td>
                                                <button type="button" class="text-red-500 hover:text-red-700 remove-from-cart" data-product-id="${item.productId}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        `;
                cartTableBody.appendChild(row);
                total += item.subtotal;
            });

            // Update total (tanpa koma)
            cartTotal.textContent = `Rp ${total.toLocaleString('id-ID', { maximumFractionDigits: 0 })}`;

            // Hitung kembalian
            calculateChange(total);

            // Tambahkan event listener untuk tombol hapus
            document.querySelectorAll('.remove-from-cart').forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.getAttribute('data-product-id');
                    const index = cart.findIndex(item => item.productId === productId);
                    if (index !== -1) {
                        cart.splice(index, 1); // Hapus item dari keranjang
                        updateCartView(); // Update tampilan
                    }
                });
            });
            function processPayment() {
                const payment = parseFloat(document.getElementById('payment').value) || 0;
                const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

                if (payment < total) {
                    alert('Jumlah pembayaran kurang!');
                    return;
                }

                alert('Pembayaran berhasil!');
                printReceipt(); // Cetak struk setelah pembayaran berhasil
                cart = []; // Kosongkan keranjang
                updateCartView();
                document.getElementById('payment').value = '';
                document.getElementById('change').value = '';
            }
            // Tambahkan event listener untuk tombol tambah jumlah
            document.querySelectorAll('.increase-quantity').forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.getAttribute('data-product-id');
                    const item = cart.find(item => item.productId === productId);
                    if (item) {
                        item.quantity += 1;
                        item.subtotal = item.price * item.quantity;
                        updateCartView();
                    }
                });
            });

            // Tambahkan event listener untuk tombol kurangi jumlah
            document.querySelectorAll('.decrease-quantity').forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.getAttribute('data-product-id');
                    const item = cart.find(item => item.productId === productId);
                    if (item && item.quantity > 1) {
                        item.quantity -= 1;
                        item.subtotal = item.price * item.quantity;
                        updateCartView();
                    }
                });
            });
        }

        // Fungsi untuk menghitung kembalian
        function calculateChange(total) {
            const paymentAmount = Math.round(parseFloat(paymentAmountInput.value) || 0); // Bulatkan ke integer
            const change = paymentAmount - total;

            if (change >= 0) {
                changeAmount.textContent = `Rp ${change.toLocaleString('id-ID', { maximumFractionDigits: 0 })}`;
            } else {
                changeAmount.textContent = 'Uang bayar kurang';
            }

            // Simpan nilai uang bayar dan kembalian di input hidden
            paymentAmountHidden.value = paymentAmount;
            changeAmountHidden.value = change >= 0 ? change : 0;
        }

        // Event listener untuk input uang bayar
        paymentAmountInput.addEventListener('input', function () {
            const total = Math.round(parseFloat(cartTotal.textContent.replace('Rp ', '').replace(/\./g, '')) || 0); // Bulatkan ke integer
            calculateChange(total);
        });

        // Submit form
        document.getElementById('cashierForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Pastikan keranjang tidak kosong
            if (cart.length === 0) {
                alert('Keranjang belanja kosong. Silakan tambahkan produk terlebih dahulu.');
                return;
            }

            // Pastikan uang bayar cukup
            const total = Math.round(parseFloat(cartTotal.textContent.replace('Rp ', '').replace(/\./g, '')) || 0); // Bulatkan ke integer
            const paymentAmount = Math.round(parseFloat(paymentAmountInput.value) || 0); // Bulatkan ke integer
            if (paymentAmount < total) {
                alert('Uang bayar tidak mencukupi.');
                return;
            }

            // Konversi data keranjang ke format yang bisa dikirim ke backend
            const items = cart.map(item => ({
                product_id: item.productId,
                quantity: item.quantity,
                price: Math.round(item.price), // Bulatkan ke integer
                subtotal: Math.round(item.subtotal), // Bulatkan ke integer
            }));



            // Tambahkan input hidden untuk mengirim data keranjang
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'items';
            input.value = JSON.stringify(items);
            this.appendChild(input);

            // Submit form
            this.submit();
            alert(
                `Pembayaran berhasil!\n\n` +
                `Total Belanja: Rp ${total.toLocaleString()}\n` +
                `Jumlah Pembayaran: Rp ${payment.toLocaleString()}\n` +
                `Kembalian: Rp ${change.toLocaleString()}\n\n` +
                `Terima kasih telah berbelanja!`
            );
        });


    });
</script>

