<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Laporan Pesanan</h1>
            <div class="flex space-x-2">
                <button onclick="printReport()" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-print mr-2"></i> Cetak
                </button>
                <button id="exportBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-file-export mr-2"></i> Export
                </button>
            </div>
        </div>
        
        <!-- Filter Section -->
        @include('reports._filter')
        
        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="text-gray-500 text-sm">Total Pesanan</div>
                <div class="text-2xl font-bold">{{ $totalOrders }}</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="text-gray-500 text-sm">Total Pendapatan</div>
                <div class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="text-gray-500 text-sm">Pesanan Selesai</div>
                <div class="text-2xl font-bold">{{ $completedOrders }}</div>
            </div>
        </div>
        
        <!-- Tabel Pesanan -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-blue-600">#{{ $order->order_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium">{{ $order->customer->name }}</div>
                                <div class="text-sm text-gray-500">{{ $order->customer->phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">{{ $order->items->sum('quantity') }} item(s)</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusClasses[$order->status] }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('orders.edit', $order->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data pesanan untuk ditampilkan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if ($orders->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $orders->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
    
    <!-- Modal Export -->
    <div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Export Laporan</h3>
                <button onclick="closeExportModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('reports.export.orders') }}" method="POST" id="exportForm">
                @csrf
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <input type="hidden" name="status" value="{{ $status }}">
                @if($customerId)
                <input type="hidden" name="customer_id" value="{{ $customerId }}">
                @endif
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Format Export</label>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="radio" id="exportPdf" name="export_format" value="pdf" class="h-4 w-4 text-blue-600 focus:ring-blue-500" checked>
                            <label for="exportPdf" class="ml-2 block text-sm text-gray-700">PDF</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="exportExcel" name="export_format" value="excel" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                            <label for="exportExcel" class="ml-2 block text-sm text-gray-700">Excel</label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeExportModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-download mr-1"></i> Download
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Fungsi untuk print laporan
        function printReport() {
            window.print();
        }
        
        // Fungsi untuk export laporan
        document.getElementById('exportBtn').addEventListener('click', function() {
            document.getElementById('exportModal').classList.remove('hidden');
        });
        
        function closeExportModal() {
            document.getElementById('exportModal').classList.add('hidden');
        }
        
        // Inisialisasi datepicker
        document.addEventListener('DOMContentLoaded', function() {
            // Anda bisa menambahkan datepicker library seperti flatpickr di sini
            // flatpickr('[data-datepicker]', { dateFormat: 'Y-m-d' });
        });
    </script>
</body>
</html>