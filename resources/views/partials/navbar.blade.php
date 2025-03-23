<nav class="bg-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-gray-800">TokoKu</a>
                </div>

                <!-- User Avatar dan Dropdown -->
                <div class="flex items-center">
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center focus:outline-none">
                        <img src="{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : asset('images/default-avatar.png') }}" 
                             alt="Avatar" 
                             class="rounded-circle mb-3 w-8 h-8" 
                             >
                            <span class="ml-2 text-gray-800">Admin</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg">
                            <a href="profile" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profil</a>
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>