@extends('layouts.app')
@section('content')

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Laporan Keuangan</h1>
            <div class="flex items-center space-x-2">
                <a href=" "
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-file-export mr-2"></i>Export
                </a>
            </div>
        </div>
        
        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" 
                           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Ringkasan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Total Pendapatan -->
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-wallet text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Jumlah Transaksi -->
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500">Jumlah Transaksi</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $transactions->count() }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-receipt text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Rata-rata Transaksi -->
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500">Rata-rata Transaksi</p>
                        <p class="text-2xl font-bold text-gray-800">
                            Rp {{ $transactions->count() > 0 ? number_format($totalIncome / $transactions->count(), 0, ',', '.') : 0 }}
                        </p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-calculator text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Ringkasan Metode Pembayaran -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pembayaran</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($paymentMethodSummary as $method)
                <div class="border rounded-lg p-3">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">{{ $method['name'] }}</span>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                            {{ $method['count'] }} transaksi
                        </span>
                    </div>
                    <div class="mt-2 text-xl font-bold">
                        Rp {{ number_format($method['total'], 0, ',', '.') }}
                    </div>
                    <div class="mt-1 text-sm text-gray-500">
                        {{ $totalIncome > 0 ? round(($method['total'] / $totalIncome) * 100, 2) : 0 }}% dari total
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Daftar Transaksi -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $transaction->invoice_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ auth()->user()->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $transaction->paymentMethod->name === 'Tunai' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $transaction->paymentMethod->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="" target="_blank"
                                   class="text-green-600 hover:text-green-900">
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
            
            <!-- Pagination -->
            @if($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>

@endsection

