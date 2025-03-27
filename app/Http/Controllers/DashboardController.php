<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Expense;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()

    {
        // Default periode: bulan berjalan
        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
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


            $income = Transaction::sum('total_amount');
            $expenses = Expense::sum('amount');
            $profit = $income - $expenses;

            $monthlyComparison = $this->getMonthlyComparison($startDate, $endDate);

        return view('dashboard.index', compact(
            'todayTransactions',
            'todayRevenue',
            'bestSellingProduct',
            'transactionChartData',
            'revenueChartData',
            'income', 'expenses', 'profit',
            'monthlyComparison'
        ));
    }

    private function getMonthlyComparison($startDate, $endDate)
    {
        $currentPeriod = Transaction::selectRaw('
                SUM(total_amount) as total,
                COUNT(*) as transaction_count
            ')
            ->dateRange($startDate, $endDate)
            ->first();
        
        $currentExpenses = Expense::selectRaw(
            'SUM(amount) as total') 
            ->orWhereDate('date', '>=', $startDate)
            ->orWhereDate('date', '<=', $endDate)
            ->first();

        $previousStart = Carbon::parse($startDate)->subMonth()->format('Y-m-d');
        $previousEnd = Carbon::parse($endDate)->subMonth()->format('Y-m-d');
        
        $previousPeriod = Transaction::selectRaw('
                SUM(total_amount) as total,
                COUNT(*) as transaction_count
            ')
            ->dateRange($previousStart, $previousEnd)
            ->first();
        
        $previousExpenses = Expense::selectRaw(
            'SUM(amount) as total') 
            ->orWhereDate('date', '>=', $startDate)
            ->orWhereDate('date', '<=', $endDate)
            ->first();

        return [
            'current' => [
                'expense' => $currentExpenses->total ?? 0,
                'transactions' => $currentPeriod->transaction_count ?? 0
            ],
            'previous' => [
                'expense' => $previousExpenses->total ?? 0,
                'transactions' => $previousPeriod->transaction_count ?? 0
            ],
            'expense_change' => $currentExpenses->total && $previousExpenses->total ? 
                (($currentExpenses->total - $previousExpenses->total) / $previousExpenses->total) * 100 : 0,
            'transaction_change' => $currentPeriod->transaction_count && $previousPeriod->transaction_count ? 
                (($currentPeriod->transaction_count - $previousPeriod->transaction_count) / $previousPeriod->transaction_count) * 100 : 0
        ];
    }
}