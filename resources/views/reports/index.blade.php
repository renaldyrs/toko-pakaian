@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Laporan Pesanan</h1>
            
                <button onclick="window.print()" class="h-10 px-4 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    <i class="fas fa-print mr-2"></i>Cetak
                </button>
            
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                <h3 class="text-gray-500 font-medium">Total Transaksi</h3>
                <p class="text-3xl font-bold">{{ $totalTransactions }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                <h3 class="text-gray-500 font-medium">Total Pendapatan</h3>
                <p class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-purple-500">
                <h3 class="text-gray-500 font-medium">Produk Terlaris</h3>
                <p class="text-2xl font-bold">
                    {{ $mostSoldProduct['name'] ?? '-' }}
                    <span class="text-lg text-purple-600">
                        ({{ $mostSoldProduct['sold'] ?? 0 }} terjual)
                    </span>
                </p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border-l-4 border-purple-500 pt-4">
                <form method="GET" class="flex items-center space-x-4 p-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow">
                    </div>
                    <button type="submit" class="mt-6  p-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-filter mr-2"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Pesanan -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-blue-600">
                                    {{ $transaction->invoice_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($transaction->details as $detail)
                                            <span class="px-2 py-1 bg-gray-100 text-xs rounded-full">
                                                {{ $detail->product->name }} ({{ $detail->quantity }})
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">
                                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                              @if($transaction->paymentMethod->name == 'Tunai') bg-green-100 text-green-800
                                              @elseif($transaction->paymentMethod->name == 'Transfer Bank') bg-blue-100 text-blue-800
                                              @else bg-purple-100 text-purple-800 @endif">
                                        {{ $transaction->paymentMethod->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('cashier.invoice', $transaction->id) }}"
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data transaksi pada periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="mt-6">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
<!-- Print Styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .container,
        .container * {
            visibility: visible;
        }

        .container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0;
        }

        .no-print {
            display: none !important;
        }

        table {
            width: 100% !important;
            font-size: 12px !important;
        }
    }
</style>