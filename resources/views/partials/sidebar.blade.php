<!-- Sidebar -->
<div id="sidebar"
    class="w-64 bg-black text-white fixed md:relative h-full transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out z-50 shadow-lg">
    <div class="p-4 border-b border-blue-500">
        @php
            $storeProfile = \App\Models\StoreProfile::first();
        @endphp
        <div class="flex flex-col items-center">
            <img src="{{ $storeProfile && $storeProfile->logo ? asset('storage/' . $storeProfile->logo) : asset('images/default-logo.png') }}"
                alt="Logo Toko" class="w-20 h-20 rounded-full shadow-md mb-2">
            <h1 class="text-lg font-bold">{{ $storeProfile->name ?? 'Nama Toko' }}</h1>
            <p class="text-sm text-blue-200">{{ $storeProfile->address ?? 'Alamat Toko' }}</p>
        </div>
    </div>

    <div class="overflow-y-auto h-full">
        <nav class="p-4">
            <!-- Dashboard -->
            <div class="mb-6">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Master Data Dropdown -->
            <div class="mb-6">
                <button class="flex items-center justify-between w-full p-3 rounded-lg hover:bg-blue-700 transition duration-200 focus:outline-none"
                    onclick="toggleDropdown('master-data-dropdown')">
                    <span class="flex items-center">
                        <i class="fas fa-database mr-3"></i>
                        <span>Master Data</span>
                    </span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul id="master-data-dropdown" class="hidden mt-2">
                    <li class="mb-2">
                        <a href="{{ route('store-profile.index') }}" class="block p-3 rounded-lg hover:bg-blue-800">
                            <i class="fas fa-store mr-3"></i> Toko
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('users.index') }}" class="block p-3 rounded-lg hover:bg-blue-800">
                            <i class="fas fa-users mr-3"></i> Pengguna
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('categories.index') }}" class="block p-3 rounded-lg hover:bg-blue-800">
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
                </ul>
            </div>

            <!-- Transaksi Dropdown -->
            <div class="mb-6">
                <button class="flex items-center justify-between w-full p-3 rounded-lg hover:bg-blue-700 transition duration-200 focus:outline-none"
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
                <button class="flex items-center justify-between w-full p-3 rounded-lg hover:bg-blue-700 transition duration-200 focus:outline-none"
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
                        <a href="{{ route('reports.financial') }}" class="block p-3 rounded-lg hover:bg-blue-800">
                            <i class="fas fa-chart-line mr-3"></i> Laporan Keuangan
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>

<!-- JavaScript -->
<script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('hidden');
    }
</script>