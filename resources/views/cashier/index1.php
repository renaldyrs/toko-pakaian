@extends('layouts.app')

@section('content')
    <script>
        // Audio Context Initialization
        let audioContext;

        function initAudio() {
            try {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
                console.log("Audio initialized");
            } catch (e) {
                console.log("Web Audio API not supported", e);
            }
        }

        // Play sound (file version)
        function playAddToCartSound() {
            // Coba gunakan file audio dulu
            const audio = new Audio('{{ asset("sounds/beep.mp3") }}');
            audio.volume = 100;

            audio.play().catch(e => {
                console.log("File audio blocked, falling back to beep");
                playBeepSound();
            });
        }

        // Fallback beep sound
        function playBeepSound() {
            if (!audioContext) initAudio();
            if (!audioContext) return;

            try {
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.type = 'triangle';
                oscillator.frequency.value = 800;
                gainNode.gain.value = 0.1;

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.start();
                setTimeout(() => {
                    oscillator.stop();
                }, 100);
            } catch (e) {
                console.log("Beep error:", e);
            }
        }

        // Inisialisasi saat halaman dimuat
        window.addEventListener('load', function () {
            initAudio();
            // Play silent sound untuk unlock audio
            const silentAudio = new Audio('data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU...');
            silentAudio.volume = 0;
            silentAudio.play().catch(e => console.log("Silent init failed"));
        });
    </script>
    <style>
        #interactive {
            width: 100%;
            height: 300px;
            background: #000;
            position: relative;
        }

        .viewport canvas,
        .viewport video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
        }

        .bg-green-50 {
            background-color: #f0fff4;
            transition: background-color 0.5s ease;
        }

        /* Animasi untuk input barcode */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20%,
            60% {
                transform: translateX(-5px);
            }

            40%,
            80% {
                transform: translateX(5px);
            }
        }

        .shake {
            animation: shake 0.5s;
        }
    </style>
    

    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Kasir</h1>

        <div class="grid grid-cols-1 md:grid-cols-2  ">
            <div class="col-md-8 mb-4">
                <button id="startScanner" class="bg-green-500 text-white px-4 py-2 rounded-md mb-2">
                    <i class="fas fa-barcode"></i> Scan
                </button>
                <button id="stopScanner" class="bg-red-500 text-white px-4 py-2 rounded-md mb-2 hidden">
                    <i class="fas fa-stop"></i> Close
                </button>
                <div id="scanner-container" class="hidden relative">
                    <div id="interactive" class="viewport"></div>
                    <div class="bg-black bg-opacity-50 text-white p-2 text-sm">
                        Arahkan kamera ke barcode produk
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="flex gap-2 mb-2">
                    <input type="text" id="manualBarcode" placeholder="Masukkan code"
                        class="flex-1 rounded-md border-gray-300 shadow-sm p-2" autocomplete="off">
                    <button id="searchBarcode" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-plus"></i> 
                    </button>
                </div>
                <div id="barcodeError" class="text-red-500 text-sm hidden"></div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4">
            <!-- Daftar Produk -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden  md:w-full">
                <table class="min-w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Produk</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga</th>
                            <th class=" text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stok</th>
                            <th class=" text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="pr-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($products as $product)
                            <tr>
                                <td class="text-center">{{ $product->name }}</td>
                                <td class="text-center">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $product->stock }}</td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" id="quantity-{{ $product->id }}" class="form-control text-center" min="1"
                                        max="{{ $product->stock }}" value="1">
                                </td>
                                <td class=" text-center">
                                    <button type="button" class="btn btn-primary btn-sm add-to-cart"
                                        data-product-id="{{ $product->id }}">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            <!-- Keranjang Belanja -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden ">
                <h5 class="text-xl font-bold mb-3 pt-2 pl-2 text-center">Keranjang Belanja</h5>
                <table class="min-w-full" id="cartTable">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                        </tr>
                        <!-- Item keranjang akan ditambahkan di sini oleh JavaScript -->
                    </tbody>
                    <tfoot>
                        <tr data-product-id="{{ $product->id }}">
                            <th colspan="2">Total</th>
                            <th id="cartTotal">Rp 0</th>
                        </tr>
                        <tr>
                            <th colspan="2">Uang Bayar</th>
                            <th>
                                <input type="number" id="paymentAmount" class="form-control" min="0"
                                    placeholder="Masukkan uang bayar">
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2">Kembalian</th>
                            <th id="changeAmount">Rp 0</th>
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
                    <input type="hidden" name="payment_amount" id="paymentAmountInput">
                    <input type="hidden" name="change_amount" id="changeAmountInput">

                    <button onclick="processPayment()" class="btn btn-success btn-block submit">
                        <i class="fas fa-check"></i> Proses Pembayaran
                    </button>
                </form>

            </div>

            <!-- Struk Pesanan -->
            <div id="receipt" class="hidden">
                <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
                    <div class="text-center mb-4">
                        <h2 class="text-xl font-bold">{{ $storeProfile->name }}</h2>
                        <p class="text-sm">{{ $storeProfile->address }}</p>
                        <p class="text-sm">Telp: {{ $storeProfile->phone }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm">Tanggal: {{ now()->format('d/m/Y H:i:s') }}</p>
                        <p class="text-sm">No. Transaksi: INV-{{ Str::upper(Str::random(8)) }}</p>
                    </div>
                    <table class="w-full mb-4">
                        <thead>
                            <tr>
                                <th class="text-left">Produk</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="receiptItems">
                            <!-- Item pesanan akan ditambahkan di sini oleh JavaScript -->
                        </tbody>
                    </table>
                    <div class="text-right">
                        <p class="font-semibold">Total: <span id="receiptTotal">Rp 0</span></p>
                    </div>
                    <div class="text-center mt-4">
                        <p class="text-sm">Terima kasih telah berbelanja!</p>
                    </div>
                </div>
            </div>


        </div>

        <script>
            let scannerActive = false;
            const productMap = @json($products->pluck('barcode', 'id'));

            document.getElementById('startScanner').addEventListener('click', function () {
                startScanner();
            });

            document.getElementById('stopScanner').addEventListener('click', function () {
                stopScanner();
            });

            function playBeepSound() {
                const audio = new Audio('{{ asset('sound/beep.mp3') }}');
                audio.play();
            }

            function startScanner() {
                scannerActive = true;
                document.getElementById('scanner-container').classList.remove('hidden');
                document.getElementById('startScanner').classList.add('hidden');
                document.getElementById('stopScanner').classList.remove('hidden');

                Quagga.init({
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        target: document.querySelector('#interactive'),
                        constraints: {
                            width: 480,
                            height: 320,
                            facingMode: "environment"
                        },
                    },
                    decoder: {
                        readers: ["ean_reader", "ean_8_reader", "code_128_reader", "upc_reader"],
                    },
                }, function (err) {
                    if (err) {
                        console.error(err);
                        alert("Gagal mengakses kamera: " + err);
                        return;
                    }
                    Quagga.start();
                });

                Quagga.onDetected(function (result) {
                    if (!scannerActive) return;

                    const barcode = result.codeResult.code;
                    const productId = Object.keys(productMap).find(key => productMap[key] === barcode);

                    if (productId) {
                        stopScanner();
                        addProductToCart(productId);
                    } else {
                        alert('Produk tidak ditemukan!');
                    }
                });
            }

            function stopScanner() {
                scannerActive = false;
                Quagga.stop();
                document.getElementById('scanner-container').classList.add('hidden');
                document.getElementById('startScanner').classList.remove('hidden');
                document.getElementById('stopScanner').classList.add('hidden');
            }

            function addProductToCart(productId) {
                const quantityInput = document.getElementById(`quantity-${productId}`);
                quantityInput.value = parseInt(quantityInput.value);
                playAddToCartSound();

                // Trigger click event untuk tombol tambah
                document.querySelector(`button[data-product-id="${productId}"]`).click();
            }

            // Auto stop scanner saat keluar dari halaman
            window.addEventListener('beforeunload', function () {
                if (scannerActive) {
                    Quagga.stop();
                }
            });

            // Handle input manual barcode
            document.getElementById('searchBarcode').addEventListener('click', searchByBarcode);
            document.getElementById('manualBarcode').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    searchByBarcode();
                }
            });

            function searchByBarcode() {
                const barcodeInput = document.getElementById('manualBarcode');
                const barcode = barcodeInput.value.trim();
                const errorElement = document.getElementById('barcodeError');

                errorElement.classList.add('hidden');

                if (!barcode) {
                    errorElement.textContent = 'Silakan masukkan barcode';
                    errorElement.classList.remove('hidden');
                    return;
                }

                const productId = Object.keys(productMap).find(key => productMap[key] === barcode);

                if (productId) {
                    addProductToCart(productId);
                    barcodeInput.value = ''; // Clear input setelah berhasil
                } else {
                    errorElement.textContent = 'Produk tidak ditemukan!';
                    errorElement.classList.remove('hidden');
                }
            }

            function addProductToCart(productId) {
                const quantityInput = document.getElementById(`quantity-${productId}`);
                if (quantityInput) {
                    quantityInput.value = parseInt(quantityInput.value);
                    document.querySelector(`button[data-product-id="${productId}"]`).click();
                    playAddToCartSound();
                    // Berikan feedback visual
                    const productRow = document.querySelector(`tr[data-product-id="${productId}"]`);
                    if (productRow) {
                        productRow.classList.add('bg-green-50');
                        setTimeout(() => {
                            productRow.classList.remove('bg-green-50');
                        }, 1000);
                    }
                }
            }
        </script>
@endsection

    @include('cashier.script')

<!-- Controller -->
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\PaymentMethod;

use App\Models\User;
use App\Models\StoreProfile;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;



class CashierController extends Controller
{
    // Menampilkan halaman kasir
    public function index()
    {
        $products = Product::where('stock', '>', 0)->get();
        $storeProfile = StoreProfile::first();
        $paymentMethods = PaymentMethod::all();
        return view('cashier.index', compact('products', 'paymentMethods', 'storeProfile'));
    }

    // Menyimpan transaksi
    public function store(Request $request)
    {

        // Konversi JSON string items ke array
        $items = json_decode($request->items, true);

        // Validasi input
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'items' => 'required|json', // Pastikan items adalah JSON string
        ]);

        // Debug: Tampilkan data request
        \Log::info('Request data:', $request->all());

        // Konversi JSON string items ke array
        $items = json_decode($request->items, true);

        // Periksa apakah json_decode berhasil
        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', 'Format items tidak valid.');
        }

        // Validasi manual untuk items
        if (!is_array($items)) {
            return redirect()->back()->with('error', 'Data items harus berupa array.');
        }

        foreach ($items as $item) {
            $validator = Validator::make($item, [
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'subtotal' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Data items tidak valid.');
            }
        }

        // Cek stok produk
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            if ($item['quantity'] > $product->stock) {
                return redirect()->back()->with('error', `Stok produk ${$product->name} tidak mencukupi. Stok tersedia: ${$product->stock}`);
            }
        }

        // Generate nomor invoice
        $invoiceNumber = 'INV-' . Str::upper(Str::random(8));

        // Hitung total amount
        $totalAmount = collect($items)->sum('subtotal');

        // Simpan transaksi
        $transaction = Transaction::create([
            'invoice_number' => $invoiceNumber,
            'total_amount' => $totalAmount,
            'payment_amount' => $request->payment_amount,
            'change_amount' => $request->change_amount,
            'payment_method_id' => $request->payment_method_id,
        ]);

        \Log::info('Transaksi disimpan:', $transaction->toArray()); // Log transaksi

        // Simpan detail transaksi
        foreach ($items as $item) {
            $detail = TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);

            \Log::info('Detail transaksi disimpan:', $detail->toArray()); // Log detail transaksi

            // Kurangi stok produk
            $product = Product::find($item['product_id']);
            $product->stock -= $item['quantity'];
            $product->save();
        }
        alert('Pembayaran berhasil!');
        return redirect()->route('cashier.print', $transaction->id)
        ->with('print', true);
    }

    public function print($id)
    {
        $storeProfile = StoreProfile::first();
        $products = Product::where('stock', '>', 0)->get();
        $paymentMethods = PaymentMethod::all();
        $transaction = Transaction::with(['details.product', 'paymentMethod'])->findOrFail($id);
        return view('cashier.print', compact('transaction', 'storeProfile', 'paymentMethods', 'products'));
    }

    // Menampilkan invoice
    public function invoice($id)
    {
        $storeProfile = StoreProfile::first();
        $products = Product::where('stock', '>', 0)->get();
        $paymentMethods = PaymentMethod::all();
        $transaction = Transaction::with(['details.product', 'paymentMethod'])->findOrFail($id);
        return view('cashier.invoice', compact('transaction', 'storeProfile', 'paymentMethods', 'products'));
    }

    // Generate PDF invoice
    public function printInvoice($id)
    {
        $transaction = Transaction::with(['details.product', 'paymentMethod'])->findOrFail($id);
        $pdf = Pdf::loadView('cashier.invoice', compact('transaction'));
        return $pdf->stream('invoice-' . $transaction->invoice_number . '.pdf');
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);

        // Simpan produk ke session
        $cart = session()->get('cart', []);
        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $request->quantity,
            'subtotal' => $product->price * $request->quantity,
        ];
        session()->put('cart', $cart);

        return response()->json(['success' => 'Produk berhasil ditambahkan ke keranjang.']);
    }

    public function orders()
    {
        // Ambil semua transaksi beserta detail dan produk
        $transactions = Transaction::with(['details.product', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cashier.orders', compact('transactions'));
    }


}