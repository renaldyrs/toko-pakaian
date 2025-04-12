<link rel="stylesheet" href="css/styles.css">

<nav class=" dark:bg-gray-800 shadow-sm fixed w-full z-50 mb-2">
    <div class="px-4 py-3 flex justify-between items-center">
        <!-- Mobile menu button -->
        <button id="mobile-menu-button" class="md:hidden text-gray-500 dark:text-gray-400 focus:outline-none">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <!-- Logo (hidden on mobile) -->
        <div class="hidden md:block">
            <h1 class="text-xl font-bold font-mono text-center text-gray-800 dark:text-white">
                {{ $storeProfile->name ?? 'Toko Ku' }}
            </h1>
        </div>

        <!-- Right side items -->
        <div class="flex items-center space-x-4">
            <!-- Dark mode toggle -->
            <button id="dark-mode-toggle" class="text-gray-500 dark:text-gray-400 focus:outline-none">
                <i class="fas fa-moon dark:hidden"></i>
                <i class="fas fa-sun hidden dark:block"></i>
            </button>

            <!-- User dropdown -->
            <div class="relative">
                <button id="user-menu-button" class="flex items-center space-x-2 focus:outline-none">
                    <span class="text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                </button>

                <!-- Dropdown menu -->
                <div id="user-dropdown"
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



<!-- Overlay for sidebar -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-30"></div>

<!-- JavaScript -->
<script>
    // Sidebar toggle for mobile
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    mobileMenuButton.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        sidebarOverlay.classList.toggle('hidden');
    });

    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
    });

    // User dropdown toggle
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');

    userMenuButton.addEventListener('click', () => {
        userDropdown.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.classList.add('hidden');
        }
    });

    // Dark mode toggle
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    darkModeToggle.addEventListener('click', () => {
        const html = document.documentElement;
        const isDarkMode = html.classList.toggle('dark');
        localStorage.setItem('darkMode', isDarkMode);
    });

    // Initialize dark mode
    document.addEventListener('DOMContentLoaded', () => {
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    });
</script>