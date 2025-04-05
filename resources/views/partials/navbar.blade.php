<link rel="stylesheet" href="css/styles.css">

<nav class="bg-white dark:bg-gray-800 shadow-sm">
    <div class="px-4 py-3 flex justify-between items-center">
        <!-- Mobile menu button -->
        <button onclick="toggleSidebar()" class="md:hidden text-gray-500 dark:text-gray-400 focus:outline-none">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <!-- Logo (hidden on mobile) -->
        <div class="hidden md:block">

        </div>

        <!-- Right side items -->
        <div class="flex items-center space-x-4">
            <!-- Dark mode toggle -->
            <button onclick="toggleDarkMode()" class="text-gray-500 dark:text-gray-400 focus:outline-none">
                <i class="fas fa-moon dark:hidden"></i>
                <i class="fas fa-sun hidden dark:block"></i>
            </button>

            <!-- User dropdown -->
            <div class="relative">
                <button onclick="toggleUserDropdown()" class="flex items-center space-x-2 focus:outline-none">
                    <span class="text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                </button>

                <!-- Dropdown menu -->
                <div id="userDropdown"
                    class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1 z-50">
                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Toggle user dropdown
    function toggleUserDropdown() {
        document.getElementById('userDropdown').classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('userDropdown');
        const button = document.querySelector('[onclick="toggleUserDropdown()"]');

        if (!button.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });


</script>
<script>
    // Fungsi untuk toggle dark mode
    function toggleDarkMode() {
    console.log('Dark mode toggled'); // Debugging
    const html = document.documentElement;
    const isDarkMode = html.classList.toggle('dark'); // Tambah/hapus kelas 'dark'
    console.log('Dark mode status:', isDarkMode); // Debugging
    localStorage.setItem('darkMode', isDarkMode); // Simpan preferensi pengguna
}

    // Inisialisasi dark mode saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function () {
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark'); // Terapkan dark mode jika diaktifkan
        }
    });
</script>