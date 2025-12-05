<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Wishnotes Admin')</title> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #f8f9fa; }
        
        /* Navbar Styling */
        .navbar { background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05); z-index: 1030; }
        .navbar-brand { font-weight: 700; color: #8A2BE2 !important; font-size: 1.5rem; }
        .nav-profile-img { width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 2px solid #fbc2eb; }

        /* Sidebar Styling */
        .sidebar {
            position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; padding: 48px 0 0; 
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1); width: 240px; background-color: #fff;
        }
        .sidebar-sticky { position: relative; top: 0; height: calc(100vh - 48px); padding-top: .5rem; overflow-x: hidden; overflow-y: auto; }
        .sidebar .nav-link { font-weight: 600; color: #333; padding: 10px 20px; transition: 0.2s; }
        
        /* Style Hover & Active Sidebar */
        .sidebar .nav-link:hover, .sidebar .nav-link.active { 
            color: #8A2BE2; background-color: #f0e6ff; border-right: 4px solid #8A2BE2; 
        }
        .sidebar .nav-link i { margin-right: 10px; width: 20px; text-align: center; }

        /* Main Content Adjustment */
        main { margin-left: 240px; padding: 30px; }

        /* Helper Classes */
        .admin-banner { background: linear-gradient(135deg, #a86ad0, #f8a5c3); color: white; border-radius: 15px; padding: 30px; position: relative; overflow: hidden; }
        .table-card { border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; }
        
        /* Tambahkan CSS khusus halaman anak di sini jika perlu */
        @yield('styles')
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}"><i class="fas fa-user-shield me-2"></i>Admin Panel</a>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-decoration-none" href="#" role="button" data-bs-toggle="dropdown">
                        <span class="me-2 fw-bold text-dark">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin' }}&background=8A2BE2&color=fff" class="nav-profile-img">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow" style="border-radius: 15px;">
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
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.notes.*') ? 'active' : '' }}" 
                               href="{{ route('admin.notes.index') }}">
                                <i class="fas fa-list-alt"></i> Kelola Catatan
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                               href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users"></i> Kelola Pengguna
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-cog"></i> Pengaturan
                            </a>
                        </li>

                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('content')
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>