@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Kasir</h1>
        <div class="row">
            <!-- Daftar Produk -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Pilih Produk</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>
                                            <input type="number" id="quantity-{{ $product->id }}" class="form-control" min="1"
                                                max="{{ $product->stock }}" value="1">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm add-to-cart"
                                                data-product-id="{{ $product->id }}">
                                                <i class="fas fa-cart-plus"></i> Tambah
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Keranjang Belanja -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Keranjang Belanja</h5>
                        <table class="table" id="cartTable">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Item keranjang akan ditambahkan di sini oleh JavaScript -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th id="cartTotal">Rp 0</th>
                                </tr>
                            </tfoot>
                        </table>
                        <form id="cashierForm" action="{{ route('cashier.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="payment_method_id">Metode Pembayaran</label>
                                <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                                    <option value="">Pilih Metode Pembayaran</option>
                                    @foreach ($paymentMethods as $method)
                                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check"></i> Proses Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('cashier.orders') }}" class="btn btn-primary">
    <i class="fas fa-list"></i> Daftar Pesanan
</a>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cart = []; // Menyimpan data keranjang
            const cartTableBody = document.querySelector('#cartTable tbody');
            const cartTotal = document.getElementById('cartTotal');

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
                            existingItem.subtotal = existingItem.price * existingItem.quantity;
                        } else {
                            // Tambahkan produk baru ke keranjang
                            const product = {
                                productId: productId,
                                name: productRow.querySelector('td:nth-child(1)').textContent,
                                price: parseFloat(productRow.querySelector('td:nth-child(2)').textContent.replace('Rp ', '').replace(/\./g, '')),
                                quantity: quantity,
                                subtotal: 0
                            };
                            product.subtotal = product.price * product.quantity;
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
                            <td>${item.quantity}</td>
                            <td>Rp ${item.subtotal.toLocaleString()}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-from-cart" data-product-id="${item.productId}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        `;
                    cartTableBody.appendChild(row);
                    total += item.subtotal;
                });

                // Update total
                cartTotal.textContent = `Rp ${total.toLocaleString()}`;

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
            }

            // Submit form
            document.getElementById('cashierForm').addEventListener('submit', function (e) {
                e.preventDefault();

                // Pastikan keranjang tidak kosong
                if (cart.length === 0) {
                    alert('Keranjang belanja kosong. Silakan tambahkan produk terlebih dahulu.');
                    return;
                }

                // Konversi data keranjang ke format JSON string
                const items = JSON.stringify(cart.map(item => ({
                    product_id: item.productId,
                    quantity: item.quantity,
                    price: item.price,
                    subtotal: item.subtotal
                })));

                // Tambahkan input hidden untuk mengirim data keranjang
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'items';
                input.value = items;
                this.appendChild(input);

                // Submit form
                this.submit();
            });
        });
    </script>
@endsection