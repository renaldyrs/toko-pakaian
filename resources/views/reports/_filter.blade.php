<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form action="{{ route('reports.orders') }}" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Tanggal Mulai -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <!-- Tanggal Selesai -->
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <!-- Status Pesanan -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            
            <!-- Pelanggan -->
            <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">Pelanggan</label>
                <select id="customer_id" name="customer_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Pelanggan</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $customerId == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="mt-4 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
        </div>
    </form>
</div>