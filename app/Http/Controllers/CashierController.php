<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\PaymentMethod;
use App\Models\Category;
use DB;
use Log;

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
        $products = Product::where('stock', '>', 0)
        ->paginate(10);
        $storeProfile = StoreProfile::first();
        $paymentMethods = PaymentMethod::all();
        $categories = Category::all();
        return view('cashier.index', compact('products', 'paymentMethods', 'storeProfile', 'categories'));
    }

    // Menyimpan transaksi
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'payment_method_id' => 'required|exists:payment_methods,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cek stok semua produk sebelum memproses
            foreach ($request->items as $item) {
                $product = Product::find($item['id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi");
                }
            }

            // Generate invoice number
            $invoiceNumber = 'INV-' . date('YmdHis') . '-' . Str::random(4);

            // Create transaction
            $transaction = new Transaction();
            $transaction->invoice_number = $invoiceNumber;
            $transaction->total_amount = 0; // Akan diupdate
            $transaction->payment_method_id = $request->payment_method_id;
            $transaction->payment_amount = $request->payment_amount;
            $transaction->change_amount = $request->change_amount;
            $transaction->user_id = auth()->id();
            $transaction->save();

            $total = 0;
            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->find($item['id']);
                $subtotal = $product->price * $item['quantity'];

                $transaction->details()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $product->stock -= $item['quantity'];
                $product->save();

                $total += $subtotal;
            }

            $transaction->total_amount = $total;
            $transaction->save();

            

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction' => $transaction->load('details.product', 'paymentMethod', 'user'),
                'message' => 'Transaksi berhasil diproses'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Transaction error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
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
        $pdf = Pdf::loadView('cashier.print', compact('transaction'));
        return $pdf->stream('invoice-' . $transaction->invoice_number . '.pdf');
    }

    

    public function showReceipt($id)
    {
        $transaction = Transaction::with(['details.product', 'paymentMethod', 'user'])
            ->findOrFail($id);

        return view('cashier.print', compact('transaction'));
    }

    public function printReceipt($id)
    {
        $transaction = Transaction::with(['details.product', 'paymentMethod', 'user'])
            ->findOrFail($id);

        return view('cashier.print', compact('transaction'));
    }


}