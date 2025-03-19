<nav class="navbar">
    <div class="navbar-left">
        <button class="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <span class="navbar-brand">Admin Panel</span>
    </div>

    <div class="navbar-right">
        <img src="" alt="">
        <div class="profile-dropdown">
            <button class="profile-button">
                
                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                <i class="fas fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
                <a class="dropdown-item" href="{{ route('profile.show') }}">
                    <i class="fas fa-user"></i> Profil Saya
                </a>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="fas fa-edit"></i> Edit Profil
                </a>
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
              </div>
            </div>
        </div>
    </div>
</nav>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">