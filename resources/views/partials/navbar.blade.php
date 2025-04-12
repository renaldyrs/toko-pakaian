<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar & Sidebar Responsif</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { "50": "#eff6ff", "100": "#dbeafe", "200": "#bfdbfe", "300": "#93c5fd", "400": "#60a5fa", "500": "#3b82f6", "600": "#2563eb", "700": "#1d4ed8", "800": "#1e40af", "900": "#1e3a8a" }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 dark:bg-gray-900">
    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow-lg fixed w-full z-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <!-- Logo & Mobile Menu Button -->
                <div class="flex items-center">
                    <button id="mobile-menu-button"
                        class="md:hidden text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex-shrink-0 flex items-center ml-4 md:ml-0">
                       
                        <span class="text-xl font-bold text-gray-800 dark:text-white"></span>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="#"
                        class="px-3 py-2 rounded-md text-sm font-medium text-white bg-primary-600 dark:bg-primary-700">Beranda</a>
                    <a href="#"
                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700">Produk</a>
                    <a href="#"
                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700">Layanan</a>
                    <a href="#"
                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700">Tentang</a>
                    <a href="#"
                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700">Kontak</a>
                </div>

                <!-- Right Side Items -->
                <div class="flex items-center">
                    <button id="theme-toggle"
                        class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white focus:outline-none">
                        <i class="fas fa-moon dark:hidden"></i>
                        <i class="fas fa-sun hidden dark:block"></i>
                    </button>
                    <div class="ml-4 relative">
                        <button class="flex items-center text-sm rounded-full focus:outline-none">
                            <img class="h-8 w-8 rounded-full"
                                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="Profil">
                            <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium hidden md:inline">John
                                Doe</span>
                            <i
                                class="fas fa-chevron-down ml-1 text-gray-500 dark:text-gray-400 text-xs hidden md:inline"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar & Main Content -->
    <div class="flex pt-16">
        <!-- Sidebar - Mobile (Hidden by default) -->
        <div id="sidebar-mobile" class="md:hidden fixed inset-0 z-20 bg-gray-800 bg-opacity-75 hidden">
            <div
                class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out -translate-x-full">
                <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <i class="fas fa-rocket text-xl text-primary-600 dark:text-primary-400 mr-2"></i>
                        <span class="text-lg font-bold text-gray-800 dark:text-white">MyApp</span>
                    </div>
                    <button id="close-sidebar"
                        class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="px-4 py-4">
                    <nav class="flex flex-col space-y-2">
                        <a href="#"
                            class="px-3 py-2 rounded-md text-sm font-medium text-white bg-primary-600 dark:bg-primary-700 flex items-center">
                            <i class="fas fa-home mr-2"></i> Beranda
                        </a>
                        <a href="#"
                            class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                            <i class="fas fa-box mr-2"></i> Produk
                        </a>
                        <a href="#"
                            class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                            <i class="fas fa-concierge-bell mr-2"></i> Layanan
                        </a>
                        <a href="#"
                            class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i> Tentang
                        </a>
                        <a href="#"
                            class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                            <i class="fas fa-envelope mr-2"></i> Kontak
                        </a>
                    </nav>
                </div>
            </div>
        </div>



    </div>

    <script>
        // Toggle mobile sidebar
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebarMobile = document.getElementById('sidebar-mobile');
        const closeSidebar = document.getElementById('close-sidebar');

        mobileMenuButton.addEventListener('click', () => {
            sidebarMobile.classList.remove('hidden');
            setTimeout(() => {
                sidebarMobile.querySelector('div').classList.remove('-translate-x-full');
            }, 50);
        });

        closeSidebar.addEventListener('click', () => {
            sidebarMobile.querySelector('div').classList.add('-translate-x-full');
            setTimeout(() => {
                sidebarMobile.classList.add('hidden');
            }, 300);
        });

        // Toggle dark mode
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.querySelector('html');

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('darkMode', html.classList.contains('dark'));
        });

        // Check for saved theme preference
        if (localStorage.getItem('darkMode') === 'true' ||
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        }
    </script>
</body>

</html>