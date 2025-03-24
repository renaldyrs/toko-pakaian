@extends('layouts.app')

@section('content')
<div class="container">
<h1 class="text-3xl font-bold mb-6">Laporan Keuangan</h1>

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

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Total Pendapatan per Metode Pembayaran</h2>
            <table class="min-w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Pembayaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($revenueByPaymentMethod as $method => $revenue)
                    <tr>
                        <td class="px-6 py-4">{{ $method }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($revenue, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
                        <td>
                        <a href="{{ route('cashier.invoice', $transaction->id) }}" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-eye"></i> Lihat Detail
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
<script>
    // Di dalam form submit handler
fetch(route('cashier.store'), {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        payment_method_id: selectedPaymentMethod,
        items: cartItems
    })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        // Buka jendela print
        const printWindow = window.open(data.invoice_url, '_blank');
        
        // Kosongkan keranjang
        clearCart();
        
        // Fokus ke jendela print (untuk beberapa browser memblokir ini)
        setTimeout(() => {
            if (printWindow) {
                printWindow.focus();
            }
        }, 1000);
    }
});
</script>