@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

        <!-- Card Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <!-- Total Transaksi Hari Ini -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Total Transaksi Hari Ini</h2>
                <p class="text-3xl font-bold text-blue-600">{{ $todayTransactions }}</p>
            </div>

            <!-- Total Pendapatan Hari Ini -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Total Pendapatan Hari Ini</h2>
                <p class="text-3xl font-bold text-green-600">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            </div>

            <!-- Produk Terlaris -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Produk Terlaris</h2>
                <p class="text-xl font-bold text-purple-600">
                    @if ($bestSellingProduct)
                        {{ $bestSellingProduct->product->name }} ({{ $bestSellingProduct->total_sold }} terjual)
                    @else
                        Tidak ada data
                    @endif
                </p>
            </div>
        </div>

        <!-- Grafik Transaksi -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Grafik Transaksi (7 Hari Terakhir)</h2>
            <canvas id="transactionChart" class="w-full" height="200"></canvas>
        </div>

        <!-- Grafik Pendapatan -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Grafik Pendapatan (7 Hari Terakhir)</h2>
            <canvas id="revenueChart" class="w-full" height="200"></canvas>
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