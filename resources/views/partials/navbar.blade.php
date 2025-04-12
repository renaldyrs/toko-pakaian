<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Navbar + Sidebar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-4 py-3">
                    <!-- Hamburger menu untuk mobile -->
                    <div class="flex items-center">
                        <button id="sidebar-toggle" class="md:hidden text-gray-500 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <span class="ml-4 text-lg font-semibold text-gray-800 md:hidden">My App</span>
                    </div>
                    
                    <!-- Search bar -->
                    <div class="hidden md:block flex-1 mx-8">
                        <div class="relative">
                            <input type="text" placeholder="Search..." 
                                   class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- User profile dan notifications -->
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-red-500"></span>
                        </button>
                        <div class="relative">
                            <button id="user-menu-button" class="flex items-center focus:outline-none">
                                <img src="https://via.placeholder.com/40" alt="User" class="h-8 w-8 rounded-full">
                                <span class="ml-2 text-gray-700 hidden md:inline">John Doe</span>
                                <i class="fas fa-chevron-down ml-1 text-gray-500 text-xs"></i>
                            </button>
                            <!-- Dropdown menu -->
                            <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>



    <!-- Mobile sidebar overlay (hidden by default) -->
    <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"></div>

                <script>
            // Toggle sidebar on mobile
            document.getElementById('sidebar-toggle').addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                
                sidebar.classList.toggle('hidden');
                overlay.classList.toggle('hidden');
                
                // Add animation classes
                sidebar.classList.add('animate-slide-in');
                setTimeout(() => {
                    sidebar.classList.remove('animate-slide-in');
                }, 300);
            });
        
            // Close sidebar when clicking on overlay
            document.getElementById('sidebar-overlay').addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.add('hidden');
                this.classList.add('hidden');
            });
        
            // Close sidebar when clicking a menu item (mobile only)
            const sidebarLinks = document.querySelectorAll('#sidebar a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    const sidebar = document.getElementById('sidebar');
                    const overlay = document.getElementById('sidebar-overlay');
                    sidebar.classList.add('hidden');
                    overlay.classList.add('hidden');
                });
            });
        
            // Automatically hide sidebar on window resize (for mobile to desktop transition)
            window.addEventListener('resize', function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                if (window.innerWidth >= 768) { // md breakpoint
                    sidebar.classList.add('hidden');
                    overlay.classList.add('hidden');
                }
            });
        
            // Toggle user dropdown menu
            document.getElementById('user-menu-button').addEventListener('click', function() {
                document.getElementById('user-menu').classList.toggle('hidden');
            });
        
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const userMenu = document.getElementById('user-menu');
                const userButton = document.getElementById('user-menu-button');
                
                if (!userMenu.contains(event.target) && !userButton.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        </script>

    <style>
        /* Animation for sidebar */
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 50;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }
    </style>
</body>
</html>