<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total penjualan
        $totalSales = Transaction::sum('total_amount');

        // Jumlah transaksi
        $totalTransactions = Transaction::count();

        // Produk terlaris
        $bestSellingProduct = Product::withSum('transactionDetails', 'quantity')
            ->orderBy('transaction_details_sum_quantity', 'desc')
            ->first();

        // Data transaksi terbaru
        $recentTransactions = Transaction::with(['details.product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalSales',
            'totalTransactions',
            'bestSellingProduct',
            'recentTransactions'
        ));
    }
}