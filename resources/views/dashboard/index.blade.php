@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard</h1>

    <!-- Card Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Penjualan</h5>
                    <p class="card-text">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Transaksi</h5>
                    <p class="card-text">{{ $totalTransactions }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Produk Terlaris</h5>
                    <p class="card-text">
                        @if ($bestSellingProduct)
                            {{ $bestSellingProduct->name }} ({{ $bestSellingProduct->transaction_details_sum_quantity }} terjual)
                        @else
                            Tidak ada data
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Transaksi Terbaru</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->invoice_number }}</td>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('cashier.invoice', $transaction->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection