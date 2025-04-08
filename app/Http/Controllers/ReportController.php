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
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $transactions = Transaction::with(['details.product', 'paymentMethod'])
            ->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            $transactionstotal = Transaction::with(['details.product', 'paymentMethod'])
            ->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();


        // Hitung statistik
        $totalTransactions = $transactionstotal->count();
        $totalRevenue = $transactionstotal->sum('total_amount');
        $mostSoldProduct = $this->getMostSoldProduct($transactionstotal);

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
        // Default tanggal (bulan ini)
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        
        // Query transaksi dengan filter
        $transactions = Transaction::with(['paymentMethod', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
           
        $transactionstotal = Transaction::with(['paymentMethod', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
        // Hitung total pendapatan
        $totalIncome = $transactionstotal->sum('total_amount');
        
        // Group by payment method
        $paymentMethodSummary = $transactionstotal->groupBy('payment_method_id')->map(function($items, $key) {
            return [
                'name' => $items->first()->paymentMethod->name,
                'count' => $items->count(),
                'total' => $items->sum('total_amount')
            ];
        });
        
        return view('reports.financial', compact(
            'transactions',
            'totalIncome',
            'paymentMethodSummary',
            'startDate',
            'endDate'
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

    public function orders(Request $request)
    {
        // Default filter: bulan ini
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status', 'all');
        $customerId = $request->input('customer_id');
        
        // Query dasar
        $query = Transaction::with(['customer', 'items.product'])
            ->whereBetween('order_date', [$startDate, $endDate]);
        
        // Filter status
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Filter pelanggan
        if ($customerId) {
            $query->where('customer_id', $customerId);
        }
        
        $orders = $query->orderBy('order_date', 'desc')->get();
        $customers = Customer::orderBy('name')->get();
        
        // Hitung statistik
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total_amount');
        $completedOrders = $orders->where('status', 'completed')->count();
        
        return view('reports.orders', compact(
            'orders',
            'customers',
            'startDate',
            'endDate',
            'status',
            'customerId',
            'totalOrders',
            'totalRevenue',
            'completedOrders'
        ));
    }
    
    public function exportOrders(Request $request)
    {
        // Validasi
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'export_format' => 'required|in:pdf,excel'
        ]);
        
        // Parameter filter sama dengan method orders()
        $params = $request->only(['start_date', 'end_date', 'status', 'customer_id']);
        
        if ($request->export_format === 'pdf') {
            return $this->exportPDF($params);
        }
        
        return $this->exportExcel($params);
    }

}