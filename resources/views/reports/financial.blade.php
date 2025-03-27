
@extends('layouts.app')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<body class="bg-gray-100">
    <div class="container mx-auto ">
        <h1 class="text-2xl font-bold mb-6">Laporan Keuangan</h1>
        
        <!-- Filter Form -->
        <div class="bg-white p-4 rounded shadow mb-6">
            <form action="{{ route('financial-reports.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="self-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                        Filter
                    </button>
                    <a href="{{ route('financial-reports.export') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" 
                       class="bg-green-500 text-white px-4 py-2 rounded-md ml-2">
                        Export PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-lg font-medium text-gray-500">Total Pendapatan</h3>
                <p class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0) }}</p>
                <p class="text-sm {{ $monthlyComparison['revenue_change'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    {{ $monthlyComparison['revenue_change'] >= 0 ? '↑' : '↓' }} 
                    {{ abs(round($monthlyComparison['revenue_change'], 2)) }}% vs bulan sebelumnya
                </p>
            </div>
            
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-lg font-medium text-gray-500">Total Transaksi</h3>
                <p class="text-2xl font-bold">{{ $monthlyComparison['current']['transactions'] }}</p>
                <p class="text-sm {{ $monthlyComparison['transaction_change'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    {{ $monthlyComparison['transaction_change'] >= 0 ? '↑' : '↓' }} 
                    {{ abs(round($monthlyComparison['transaction_change'], 2)) }}% vs bulan sebelumnya
                </p>
            </div>
            
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-lg font-medium text-gray-500">Rata-rata per Transaksi</h3>
                <p class="text-2xl font-bold">
                    @if($monthlyComparison['current']['transactions'] > 0)
                        Rp {{ number_format($monthlyComparison['current']['revenue'] / $monthlyComparison['current']['transactions'], 0) }}
                    @else
                        Rp 0
                    @endif
                </p>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Pendapatan Harian -->
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-lg font-medium mb-4">Pendapatan Harian</h3>
                <canvas id="dailyRevenueChart" height="250"></canvas>
            </div>
            
            <!-- Metode Pembayaran -->
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-lg font-medium mb-4">Pendapatan per Metode Pembayaran</h3>
                <canvas id="paymentMethodChart" height="250"></canvas>
            </div>
        </div>

        <!-- Produk Terlaris -->
        <div class="bg-white p-4 rounded shadow mb-6">
            <h3 class="text-lg font-medium mb-4">5 Produk Terlaris</h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terjual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bestSellingProducts as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->total_sold }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($product->total_revenue, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Chart Pendapatan Harian
        const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($dailyTransactions->pluck('date')) !!},
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode($dailyTransactions->pluck('total_amount')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Chart Metode Pembayaran
        const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
        new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($revenueByPaymentMethod->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($revenueByPaymentMethod->pluck('transactions_sum_total_amount')) !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: Rp ${value.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
@endsection
