<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total transaksi hari ini
        $todayTransactions = Transaction::whereDate('created_at', Carbon::today())->count();

        // Total pendapatan hari ini
        $todayRevenue = Transaction::whereDate('created_at', Carbon::today())->sum('total_amount');

        // Produk terlaris (berdasarkan jumlah terjual)
        $bestSellingProduct = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->first();

        // Data grafik transaksi (7 hari terakhir)
        $transactionChartData = Transaction::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Data grafik pendapatan (7 hari terakhir)
        $revenueChartData = Transaction::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard.index', compact(
            'todayTransactions',
            'todayRevenue',
            'bestSellingProduct',
            'transactionChartData',
            'revenueChartData'
        ));
    }
}