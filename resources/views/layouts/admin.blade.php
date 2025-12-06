<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Wishnotes Admin')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/style.css', 'resources/css/admin.css'])
    @yield('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-4">
            <button class="btn btn-link d-md-none me-3 text-dark" type="button" onclick="toggleSidebar()">
                <i class="fas fa-bars fa-lg"></i>
            </button>

            <a class="navbar-brand" href="{{ route('admin.dashboard') }}"><i class="fas fa-user-shield me-2"></i>Admin Panel</a>
            
            <div class="d-flex align-items-center ms-auto gap-3">
                <!-- Toggle Dark Mode -->
                <button class="btn btn-outline-secondary rounded-circle btn-sm" onclick="toggleDarkMode()">
                    <i class="fas fa-moon" id="icon-theme"></i>
                </button>

                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-decoration-none" href="#" role="button" data-bs-toggle="dropdown">
                        <span class="me-2 fw-bold text-dark d-none d-md-block">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin' }}&background=8A2BE2&color=fff" class="nav-profile-img rounded-circle" width="40" height="40">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                        <li><h6 class="dropdown-header">Mode Tampilan</h6></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="fas fa-home me-2 text-primary"></i> Dashboard User
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <li><button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</button></li>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link {{ request()->routeIs('admin.notes.*') ? 'active' : '' }}" href="{{ route('admin.notes.index') }}">
                                <i class="fas fa-list-alt"></i> Kelola Catatan
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users"></i> Kelola Pengguna
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="{{ route('maintenance')}}">
                                <i class="fas fa-cog"></i> Pengaturan
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-md-none position-fixed top-0 start-0 w-100 h-100 bg-dark opacity-50 d-none" id="sidebarOverlay" onclick="toggleSidebar()" style="z-index: 99;"></div>
                @yield('content')
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebarMenu');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('d-none');
        }

        function toggleDarkMode() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            document.getElementById('icon-theme').className = newTheme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
        }

        // Init theme
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            const icon = document.getElementById('icon-theme');
            if(icon) icon.className = savedTheme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
        })();
    </script>
    @yield('scripts')
</body>
</html>