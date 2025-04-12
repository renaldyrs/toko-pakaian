        <!-- Sidebar - Desktop -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="w-64 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 fixed md:relative h-full transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out z-50 shadow-lg">
                <div class="flex items-center h-16 px-4 border-b border-gray-200 dark:border-gray-700">
                    
                    <div class="flex flex-col items-center">
                        
                        <h1 class="text-lg font-bold">{{ $storeProfile->name ?? 'Nama Toko' }}</h1>
                        
                    </div>
                </div>
                <div class="px-4 py-4">
                    <nav class="flex flex-col space-y-2">
                        <a href="{{ route('dashboard') }}"
                            class="px-3 py-2 rounded-md text-sm font-medium text-white bg-primary-600 dark:bg-primary-700 flex items-center">
                            <i class="fas fa-home mr-2"></i> Dashboard
                        </a>

                    </nav>

                    <!-- Master Data Dropdown -->
                    <div class="mb-6">
                        <button
                            class="flex items-center justify-between w-full p-3 rounded-lg hover:bg-indigo-700 transition duration-200 focus:outline-none"
                            onclick="toggleDropdown('master-data-dropdown')">
                            <span class="flex items-center">
                                <i class="fas fa-database mr-3"></i>
                                <span>Master Data</span>
                            </span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <ul id="master-data-dropdown" class="hidden mt-2">
                            <li class="mb-2">
                                <a href="{{ route('store-profile.index') }}"
                                    class="block p-3 rounded-lg hover:bg-blue-800">
                                    <i class="fas fa-store mr-3"></i> Toko
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('users.index') }}" class="block p-3 rounded-lg hover:bg-blue-800">
                                    <i class="fas fa-users mr-3"></i> Pengguna
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('categories.index') }}"
                                    class="block p-3 rounded-lg hover:bg-blue-800">
                                    <i class="fas fa-tags mr-3"></i> Kategori
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('suppliers.index') }}" class="block p-3 rounded-lg hover:bg-blue-800">
                                    <i class="fas fa-truck mr-3"></i> Supplier
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('products.index') }}" class="block p-3 rounded-lg hover:bg-blue-800">
                                    <i class="fas fa-boxes mr-3"></i> Produk
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('payment.index') }}" class="block p-3 rounded-lg hover:bg-blue-800">
                                    <i class="fas fa-boxes mr-3"></i> Payment
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- Transaksi Dropdown -->
                    <div class="mb-6">
                        <button
                            class="flex items-center justify-between w-full p-3 rounded-lg hover:bg-indigo-700 transition duration-200 focus:outline-none"
                            onclick="toggleDropdown('transaksi-dropdown')">
                            <span class="flex items-center">
                                <i class="fas fa-exchange-alt mr-3"></i>
                                <span>Transaksi</span>
                            </span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <ul id="transaksi-dropdown" class="hidden mt-2">
                            <li class="mb-2">
                                <a href="{{ route('cashier.index') }}" class="block p-3 rounded-lg hover:bg-blue-800">
                                    <i class="fas fa-cash-register mr-3"></i> Kasir
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('expenses.index') }}" class="block p-3 rounded-lg hover:bg-blue-800">
                                    <i class="fas fa-money-bill-wave mr-3"></i> Pengeluaran
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('returns.index') }}" class="block p-3 rounded-lg hover:bg-blue-800">
                                    <i class="fas fa-undo mr-3"></i> Return
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Laporan Dropdown -->
                    <div class="mb-6">
                        <button
                            class="flex items-center justify-between w-full p-3 rounded-lg hover:bg-indigo-700 transition duration-200 focus:outline-none"
                            onclick="toggleDropdown('laporan-dropdown')">
                            <span class="flex items-center">
                                <i class="fas fa-chart-bar mr-3"></i>
                                <span>Laporan</span>
                            </span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <ul id="laporan-dropdown" class="hidden mt-2">
                            <li class="mb-2">
                                <a href="{{ route('reports.index') }}" class="block p-3 rounded-lg hover:bg-blue-800">
                                    <i class="fas fa-clipboard-list mr-3"></i> Laporan Pesanan
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('reports.financial') }}"
                                    class="block p-3 rounded-lg hover:bg-blue-800">
                                    <i class="fas fa-chart-line mr-3"></i> Laporan Keuangan
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Pengaturan</h3>
                        <nav class="mt-2 flex flex-col space-y-2">
                            <a href="#"
                                class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                                <i class="fas fa-user-cog mr-2"></i> Profil
                            </a>
                            <a href="#"
                                class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                                <i class="fas fa-cog mr-2"></i> Pengaturan
                            </a>
                            <a href="#"
                                class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                                <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

<!-- JavaScript -->
<script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('hidden');
    }
</script>