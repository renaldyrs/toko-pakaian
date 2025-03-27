<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // Menampilkan halaman laporan keuangan
    public function index(Request $request)
    {
        // Ambil parameter filter dari request
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Query transaksi berdasarkan rentang tanggal
        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Hitung total pendapatan
        $totalRevenue = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');

        // Hitung jumlah transaksi
        $totalTransactions = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();

        // Kelompokkan transaksi berdasarkan metode pembayaran
        $paymentMethods = PaymentMethod::with(['transactions' => function ($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }])->get();

        // Hitung total pendapatan per metode pembayaran
        $revenueByPaymentMethod = [];
        foreach ($paymentMethods as $method) {
            $revenueByPaymentMethod[$method->name] = $method->transactions->sum('total_amount');
        }

        return view('reports.index', compact('transactions', 'totalRevenue', 'totalTransactions', 'startDate', 'endDate', 'paymentMethods', 'revenueByPaymentMethod'));
    }

    public function financialReport(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    
    $transactions = Transaction::with(['details', 'paymentMethod'])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();
    
    $income = $transactions->sum('total_amount');
    
    
    return view('reports.financial', compact('transactions', 'income'));
}

}