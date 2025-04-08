<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\PDF;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        // Default periode: bulan berjalan
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Validasi tanggal
        if ($startDate > $endDate) {
            return back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        }

        // Hitung total pendapatan
        $totalRevenue = Transaction::dateRange($startDate, $endDate)->sum('total_amount');

        // Pendapatan per metode pembayaran
        $revenueByPaymentMethod = PaymentMethod::withSum([
            'transactions' => function($query) use ($startDate, $endDate) {
                $query->dateRange($startDate, $endDate);
            }
        ], 'total_amount')->get();

        // Transaksi harian untuk grafik
        $dailyTransactions = Transaction::selectRaw('
                DATE(created_at) as date,
                COUNT(*) as transaction_count,
                SUM(total_amount) as total_amount
            ')
            ->dateRange($startDate, $endDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Produk terlaris
        $bestSellingProducts = \DB::table('transaction_details')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->selectRaw('
                products.name,
                SUM(transaction_details.quantity) as total_sold,
                SUM(transaction_details.subtotal) as total_revenue
            ')
            ->whereBetween('transaction_details.created_at', [$startDate, $endDate])
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Perbandingan bulanan
        $monthlyComparison = $this->getMonthlyComparison($startDate, $endDate);

        return view('reports.financial', compact(
            'totalRevenue',
            'revenueByPaymentMethod',
            'dailyTransactions',
            'bestSellingProducts',
            'monthlyComparison',
            'startDate',
            'endDate'
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

        $previousStart = Carbon::parse($startDate)->subMonth()->format('Y-m-d');
        $previousEnd = Carbon::parse($endDate)->subMonth()->format('Y-m-d');
        
        $previousPeriod = Transaction::selectRaw('
                SUM(total_amount) as total,
                COUNT(*) as transaction_count
            ')
            ->dateRange($previousStart, $previousEnd)
            ->first();

        return [
            'current' => [
                'revenue' => $currentPeriod->total ?? 0,
                'transactions' => $currentPeriod->transaction_count ?? 0
            ],
            'previous' => [
                'revenue' => $previousPeriod->total ?? 0,
                'transactions' => $previousPeriod->transaction_count ?? 0
            ],
            'revenue_change' => $currentPeriod->total && $previousPeriod->total ? 
                (($currentPeriod->total - $previousPeriod->total) / $previousPeriod->total) * 100 : 0,
            'transaction_change' => $currentPeriod->transaction_count && $previousPeriod->transaction_count ? 
                (($currentPeriod->transaction_count - $previousPeriod->transaction_count) / $previousPeriod->transaction_count) * 100 : 0
        ];
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $transactions = Transaction::with(['details', 'paymentMethod'])
            ->dateRange($startDate, $endDate)
            ->get();

        $pdf = PDF::loadView('reports.financial_pdf', compact('transactions'));

        return $pdf->download('laporan_keuangan.pdf');
    }
    
}