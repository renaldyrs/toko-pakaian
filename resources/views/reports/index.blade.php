@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Laporan Keuangan</h1>

    <!-- Filter Tanggal -->
    <form action="{{ route('reports.index') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="start_date">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label for="end_date">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Ringkasan Laporan -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Ringkasan Laporan</h5>
            <p>Total Pendapatan: <strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong></p>
            <p>Jumlah Transaksi: <strong>{{ $totalTransactions }}</strong></p>
        </div>
    </div>

    <!-- Daftar Transaksi -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Daftar Transaksi</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Metode Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->invoice_number }}</td>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                        <td>{{ $transaction->paymentMethod->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection