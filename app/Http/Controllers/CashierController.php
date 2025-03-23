<?php

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
        return view('cashier.index', compact('products', 'paymentMethods','storeProfile'));
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
        return redirect()->route('cashier.index');
    }

    // Menampilkan invoice
    public function invoice($id)
    {
        $storeProfile = StoreProfile::first();
        $products = Product::where('stock', '>', 0)->get();
        $paymentMethods = PaymentMethod::all();
        $transaction = Transaction::with(['details.product', 'paymentMethod'])->findOrFail($id);
        return view('cashier.index', compact('transaction', 'storeProfile', 'paymentMethods','products'));
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