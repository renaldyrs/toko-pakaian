<aside id="sidebar" class="w-64 bg-white dark:bg-gray-800 shadow-md fixed md:relative h-full transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out z-50">
    <div class="p-1 border-b border-gray-200 dark:border-gray-700">
        @php
            $storeProfile = \App\Models\StoreProfile::first();
        @endphp
        <img src="{{ $storeProfile && $storeProfile->logo ? asset('storage/' . $storeProfile->logo) : asset('images/default-logo.png') }}"
            alt="Logo Toko" class="w-20 h-20 rounded-full mx-auto mb-2">
        
    </div>

    <div class="overflow-y-auto h-full">
        <nav class="p-4">
            <!-- Dashboard -->
            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Master Data -->
            <div class="mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400 mb-2">Master Data</p>
                <ul>
                    <li class="mb-1">
                        <a href="{{ route('store-profile.edit') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-store mr-3"></i>
                            <span>Toko</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('users.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-users mr-3"></i>
                            <span>Pengguna</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('categories.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-tags mr-3"></i>
                            <span>Kategori</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('suppliers.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-truck mr-3"></i>
                            <span>Supplier</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('products.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-boxes mr-3"></i>
                            <span>Produk</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Transaksi -->
            <div class="mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400 mb-2">Transaksi</p>
                <ul>
                    <li class="mb-1">
                        <a href="{{ route('cashier.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-cash-register mr-3"></i>
                            <span>Kasir</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('expenses.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-money-bill-wave mr-3"></i>
                            <span>Pengeluaran</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('returns.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-undo mr-3"></i>
                            <span>Return</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Laporan -->
            <div class="mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400 mb-2">Laporan</p>
                <ul>
                    <li class="mb-1">
                        <a href="{{ route('reports.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-clipboard-list mr-3"></i>
                            <span>Laporan Pesanan</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('reports.financial') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-chart-line mr-3"></i>
                            <span>Laporan Keuangan</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</aside>