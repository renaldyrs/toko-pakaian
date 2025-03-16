<div class="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="has-submenu">
                <a href="#">
                    <i class="fas fa-cogs"></i> Master
                    <i class="fas fa-angle-down"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('categories.index') }}"><i class="fas fa-list"></i> Kategori</a></li>
                    <li><a href="{{ route('suppliers.index') }}"><i class="fas fa-truck"></i> Supplier</a></li>
                    <li><a href="{{ route('products.index') }}"><i class="fas fa-box"></i> Produk</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#">
                    <i class="fas fa-shopping-cart"></i> Transaksi
                    <i class="fas fa-angle-down"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('cashier.index') }}"><i class="fas fa-cash-register"></i> Kasir</a></li>
                    <li><a href="{{ route('cashier.orders') }}"><i class="fas fa-list-alt"></i> Daftar Pesanan</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route('reports.index') }}">
                    <i class="fas fa-chart-line"></i> Laporan Keuangan
                </a>
            </li>
        </ul>
    </div>


<style>
/* General Styles */
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
}

/* Navbar */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #2c3e50;
    color: #fff;
    padding: 10px 20px;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

.navbar-left {
    display: flex;
    align-items: center;
}

.navbar-brand {
    font-size: 20px;
    font-weight: bold;
    margin-left: 10px;
}

.sidebar-toggle {
    background: none;
    border: none;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
}

.navbar-right {
    position: relative;
}

.profile-button {
    background: none;
    border: none;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
}

.profile-button i {
    margin-left: 5px;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #fff;
    min-width: 160px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
}

.dropdown-content a {
    color: #333;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
}

.profile-dropdown:hover .dropdown-content {
    display: block;
}

/* Sidebar */
.sidebar {
    width: 250px;
    background-color: #34495e;
    color: #fff;
    height: 100vh;
    position: fixed;
    top: 60px;
    left: 0;
    transition: transform 0.3s ease;
}

.sidebar.collapsed {
    transform: translateX(-250px);
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-menu li {
    padding: 10px 20px;
    border-bottom: 1px solid #2c3e50;
}

.sidebar-menu li a {
    color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
}

.sidebar-menu li a i {
    margin-right: 10px;
}

.sidebar-menu li a .fa-angle-down {
    margin-left: auto;
    transition: transform 0.3s ease;
}

.sidebar-menu li a .fa-angle-down.rotate {
    transform: rotate(180deg);
}

.submenu {
    list-style: none;
    padding: 0;
    margin: 10px 0 0 20px;
    display: none;
}

.submenu.active {
    display: block;
}

.submenu li {
    padding: 5px 0;
}

.submenu li a {
    color: #bdc3c7;
    font-size: 14px;
}

.submenu li a:hover {
    color: #fff;
}

/* Main Content */
.main-content {
    margin-left: 250px;
    margin-top: 60px;
    padding: 20px;
    transition: margin-left 0.3s ease;
}

.sidebar.collapsed + .main-content {
    margin-left: 0;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-250px);
    }

    .sidebar.collapsed {
        transform: translateX(0);
    }

    .main-content {
        margin-left: 0;
    }

    .sidebar.collapsed + .main-content {
        margin-left: 0;
    }
}
</style>

<script>
        // JavaScript untuk toggle sidebar dan submenu
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');
            const submenuToggles = document.querySelectorAll('.has-submenu > a');

            // Toggle sidebar
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
            });

            // Toggle submenu
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    const submenu = this.nextElementSibling;
                    submenu.classList.toggle('active');
                    this.querySelector('.fa-angle-down').classList.toggle('rotate');
                });
            });
        });
    </script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">