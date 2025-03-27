@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-3xl font-bold mb-6">Laporan Pesanan</h1>

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
        <div class="card mb-4 shadow">
            <div class="card-body">
                <h5 class="text-xl font-bold mb-4">Ringkasan Laporan</h5>
                <p>Total Pendapatan: <strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong></p>
                <p>Jumlah Transaksi: <strong>{{ $totalTransactions }}</strong></p>
            </div>
        </div>

        <!-- <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Total Pendapatan per Metode Pembayaran</h2>
                <table class="min-w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode
                                Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                                Pendapatan</th>
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
            </div> -->

        <!-- Daftar Transaksi -->

        <div class="bg-white rounded-xl shadow overflow-hidden mb-8 p-3">
            <h5 class="text-xl font-bold mb-3 pt-2 pl-2">Daftar Transaksi</h5>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 ">
                    <thead class="bg-gray-300">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-bold text-black-500 uppercase tracking-wider">
                                No. Invoice
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-bold text-black-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-bold text-black-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-bold text-black-500 uppercase tracking-wider">
                                Metode Pembayaran
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-bold text-black-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->invoice_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $transaction->created_at->format('d M Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm  text-gray-500">
                                    <span class="text-green-600 font-medium">Rp
                                        {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if ($transaction->payment_method_id == '1')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Tunai
                                        </span>
                                    @elseif ($transaction->payment_method_id == '2')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Transfer Bank
                                        </span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Qris
                                        </span>
                                    @endif

                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('cashier.invoice', $transaction->id) }}"
                                        class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="text-gray-600 hover:text-gray-900"><i class="fas fa-print"></i></a>
                                    <a href="{{ route('returns.create', $transaction->id) }}"
                                        class="text-yellow-600 hover:text-yellow-800">
                                        Buat Retur
                                    </a>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>
                    <tfoot class="bg-gray-50">

                    </tfoot>
                </table>
                <div class="mt-4">
                    {{ $transactions->appends([
        'start_date' => request('start_date'),
        'end_date' => request('end_date')
    ])->links() }}
                </div>
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