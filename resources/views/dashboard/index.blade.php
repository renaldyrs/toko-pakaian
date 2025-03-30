@extends('layouts.app')

@section('content')
    <div class="container mx-auto p">
        <h1 class="text-3xl font-bold mb-6 dark:text-white">Dashboard</h1>


        <!-- Statistik Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Pendapatan -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden dark:bg-gray-800 ">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <i class="fas fa-wallet text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">Rp {{ number_format($income, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-green-600 text-sm font-medium">{{ abs(round($monthlyComparison['transaction_change'], 2)) }}% dari bulan sebelumnya</span>
                    </div>
                </div>
            </div>

            <!-- Total Pengeluaran -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden dark:bg-gray-800 ">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                            <i class="fas fa-shopping-cart text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Pengeluaran</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">Rp {{ number_format($expenses, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-red-600 text-sm font-medium">{{ abs(round($monthlyComparison['expense_change'], 2)) }}% dari bulan sebelumnya</span>
                    </div>
                </div>
            </div>

            <!-- Laba Bersih -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden dark:bg-gray-800">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Laba/Rugi</p>
                            <p class="text-2xl font-bold @if($profit >= 0) text-green-600 @else text-red-600 @endif">
                                Rp {{ number_format(abs($profit), 0, ',', '.') }}
                                ({{ $profit >= 0 ? 'Laba' : 'Rugi' }})
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-blue-600 text-sm font-medium">+15% dari bulan lalu</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8  ">
            <!-- Total Transaksi Hari Ini -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden dark:bg-gray-800">
                <div class="p-6">
                    <div class="flex items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Transaksi Hari Ini</p>
                            <p class="text-2xl font-bold text-black-600 dark:text-white">
                            {{ $todayTransactions }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pendapatan Hari Ini -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden dark:bg-gray-800">
                <div class="p-6">
                    <div class="flex items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Pendapatan Hari Ini</p>
                            <p class="text-2xl font-bold text-black-600 dark:text-white">
                            Rp {{ number_format($todayRevenue, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produk Terlaris -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden dark:bg-gray-800">
                <div class="p-6">
                    <div class="flex items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Produk Terlaris</p>
                            <p class="text-2xl font-bold text-black-600 dark:text-white">
                            @if ($bestSellingProduct)
                        {{ $bestSellingProduct->product->name }} ({{ $bestSellingProduct->total_sold }} terjual)
                    @else
                        Tidak ada data
                    @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 ">
            <!-- Grafik Transaksi -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-8 dark:bg-gray-800">
                <h2 class="text-xl font-semibold text-gray-300 mb-4">Grafik Transaksi (7 Hari Terakhir)</h2>
                <canvas id="transactionChart" class="w-full" height="200"></canvas>
            </div>

            <!-- Grafik Pendapatan -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-8 dark:bg-gray-800">
                <h2 class="text-xl font-semibold text-gray-300 mb-4">Grafik Pendapatan (7 Hari Terakhir)</h2>
                <canvas id="revenueChart" class="w-full" height="200"></canvas>
            </div>
        </div>


    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data untuk grafik transaksi
            const transactionChartData = {
                labels: {!! json_encode($transactionChartData->pluck('date')) !!},
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: {!! json_encode($transactionChartData->pluck('total')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            };

            // Data untuk grafik pendapatan
            const revenueChartData = {
                labels: {!! json_encode($revenueChartData->pluck('date')) !!},
                datasets: [{
                    label: 'Total Pendapatan',
                    data: {!! json_encode($revenueChartData->pluck('total')) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            };

            // Grafik Transaksi
            const transactionChartCtx = document.getElementById('transactionChart').getContext('2d');
            new Chart(transactionChartCtx, {
                type: 'line',
                data: transactionChartData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Grafik Pendapatan
            const revenueChartCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueChartCtx, {
                type: 'bar',
                data: revenueChartData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection