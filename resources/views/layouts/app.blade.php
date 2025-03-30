<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <!-- Font Awesome untuk ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

</head>

<body class="bg-gray-100 dark:bg-gray-900">
    <div class="flex h-screen overflow-hidden">
        <!-- Overlay (hanya muncul di mobile) -->
        <div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Konten utama -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('partials.navbar')
            <main class="flex-1 overflow-y-auto p-4">@yield('content')</main>
        </div>
    </div>



    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const body = document.body;

            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('translate-x-0');
            overlay.classList.toggle('hidden');
            body.classList.toggle('sidebar-open');

            // Untuk memastikan state konsisten
            if (sidebar.classList.contains('-translate-x-full')) {
                overlay.classList.add('hidden');
            } else {
                overlay.classList.remove('hidden');
            }
        }

        // Inisialisasi saat load
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 768) {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // Toggle dark mode
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        }

        // Initialize dark mode
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
    
</body>

</html>

<!-- Bootstrap JS dan dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>