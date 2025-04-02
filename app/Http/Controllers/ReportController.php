<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // Menampilkan halaman laporan keuangan
    public function index(Request $request)
    {
        // Default: 7 hari terakhir
        $startDate = $request->start_date ?? Carbon::now()->subDays(7)->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');

        $transactions = Transaction::with(['details.product', 'paymentMethod'])
            ->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Hitung statistik
        $totalTransactions = $transactions->count();
        $totalRevenue = $transactions->sum('total_amount');
        $mostSoldProduct = $this->getMostSoldProduct($transactions);

        return view('reports.index', compact(
            'transactions',
            'startDate',
            'endDate',
            'totalTransactions',
            'totalRevenue',
            'mostSoldProduct'
        ));
    }

    private function getMostSoldProduct($transactions)
    {
        $products = [];
        
        foreach ($transactions as $transaction) {
            foreach ($transaction->details as $detail) {
                $productId = $detail->product_id;
                $products[$productId] = [
                    'name' => $detail->product->name,
                    'sold' => ($products[$productId]['sold'] ?? 0) + $detail->quantity
                ];
            }
        }

        if (!empty($products)) {
            usort($products, function ($a, $b) {
                return $b['sold'] <=> $a['sold'];
            });
            return $products[0];
        }

        return null;
    }

    public function financialReport(Request $request)
    {
        // Default periode: bulan ini
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Validasi tanggal
        if ($startDate > $endDate) {
            return redirect()->back()->withErrors('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        }

        // Query data transaksi
        $transactions = Transaction::with(['paymentMethod', 'details.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            

        // Hitung summary
        $totalPendapatan = $transactions->sum('total_amount');
        $totalTransaksi = $transactions->count();
        $averageTransaction = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;

        // Data untuk chart
        $chartData = $this->generateChartData($startDate, $endDate);

        return view('reports.financial', compact(
            'transactions',
            'startDate',
            'endDate',
            'totalPendapatan',
            'totalTransaksi',
            'averageTransaction',
            'chartData'
        ));
    }
    private function generateChartData($startDate, $endDate)
    {
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        $chartLabels = [];
        $chartIncome = [];
        $chartTransactions = [];

        foreach ($period as $date) {
            $chartLabels[] = $date->format('d M');
            
            $income = Transaction::whereDate('created_at', $date)
                ->sum('total_amount');
            $chartIncome[] = $income;

            $transactionCount = Transaction::whereDate('created_at', $date)
                ->count();
            $chartTransactions[] = $transactionCount;
        }

        return [
            'labels' => $chartLabels,
            'income' => $chartIncome,
            'transactions' => $chartTransactions
        ];
    }

}