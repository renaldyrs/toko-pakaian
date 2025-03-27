<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Product;
use App\Models\Category;
use App\Models\Returns;
use App\Models\ReturnItem;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use DB;
use Str;

// app/Http/Controllers/ReturnController.php
class ReturnController extends Controller
{
   // ReturnController.php
   public function index() {
    $returns = Returns::with(['transaction', 'product', 'user'])->get();
    return view('return.index', compact('returns'));
}

public function create($transaction_id) {
    $transaction = Transaction::with('details.product')->findOrFail($transaction_id);
    return view('return.create', compact('transaction'));
}

public function store(Request $request) {

    

    $request->validate([
        'transaction_id' => 'required|exists:transactions,id',
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'reason' => 'required|string|max:500',
    ]);

    // Cek stok retur tidak melebihi pembelian
    $transactionDetail = TransactionDetail::where('transaction_id', $request->transaction_id)
        ->where('product_id', $request->product_id)
        ->firstOrFail();

    if ($request->quantity > $transactionDetail->quantity) {
        return back()->with('error', 'Jumlah retur melebihi pembelian');
    }
    // Cek stok retur tidak melebihi pembelian

    $number = 'RET-' . date('Ymd') . '-' . strtoupper(Str::random(4));

    $return = Returns::create([
        'return_number' => $number,
        'transaction_id' => $request->transaction_id,
        'product_id' => $request->product_id,
        'quantity' => $request->quantity,
        'total_refund' => $transactionDetail->price * $request->quantity,
        'reason' => $request->reason,
        'user_id' => auth()->id(),
    ]);

    return redirect()->route('returns.index')->with('success', 'Permintaan retur berhasil diajukan');
}

public function approve($id) {
    $return = Returns::findOrFail($id);
    
    // Update stok produk
    $product = $return->product;
    $product->stock += $return->quantity;
    $product->save();

    $return->update(['status' => 'approved']);

    return back()->with('success', 'Retur disetujui dan stok diperbarui');
}

public function reject($id) {
    $return = Returns::findOrFail($id);
    $return->update(['status' => 'rejected']);
    return back()->with('success', 'Retur ditolak');
}
}
