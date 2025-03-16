<nav class="navbar">
        <div class="navbar-left">
            <button class="sidebar-toggle">
                <i class="fas fa-bars"></i>
            </button>
            <span class="navbar-brand">Admin Panel</span>
        </div>
        <div class="navbar-right">
            <div class="profile-dropdown">
                <button class="profile-button">
                    <i class="fas fa-user"></i> Admin
                    <i class="fas fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="#" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">