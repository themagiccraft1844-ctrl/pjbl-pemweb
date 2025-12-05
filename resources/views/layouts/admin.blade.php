<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Wishnotes Admin')</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #f8f9fa; overflow-x: hidden; transition: all 0.3s; }
        
        /* Navbar Styling */
        /* [REVISI] Tambah transition agar gerakannya halus saat sidebar ditutup */
        .navbar { 
            background: white; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            z-index: 1030; 
            transition: all 0.3s ease-in-out; 
        }
        .navbar-brand { font-weight: 700; color: #8A2BE2 !important; font-size: 1.5rem; }
        .nav-profile-img { width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 2px solid #fbc2eb; }

        /* Sidebar Styling */
        .sidebar {
            position: fixed; top: 0; bottom: 0; left: 0; z-index: 1040; padding: 48px 0 0; 
            box-shadow: 2px 0 10px rgba(0, 0, 0, .1); width: 240px; background-color: #fff;
            transition: all 0.3s ease-in-out;
        }
        .sidebar-sticky { position: relative; top: 0; height: calc(100vh - 48px); padding-top: .5rem; overflow-x: hidden; overflow-y: auto; }
        .sidebar .nav-link { font-weight: 600; color: #333; padding: 10px 20px; transition: 0.2s; display: flex; align-items: center; }
        
        /* Style Hover & Active Sidebar */
        .sidebar .nav-link:hover, .sidebar .nav-link.active { 
            color: #8A2BE2; background-color: #f0e6ff; border-right: 4px solid #8A2BE2; 
        }
        .sidebar .nav-link i { margin-right: 10px; width: 20px; text-align: center; }

        /* Main Content Adjustment */
        main { 
            transition: all 0.3s ease-in-out; 
            padding: 30px; 
            min-height: 100vh;
        }

        /* Helper Classes */
        .admin-banner { background: linear-gradient(135deg, #a86ad0, #f8a5c3); color: white; border-radius: 15px; padding: 30px; position: relative; overflow: hidden; }
        .table-card { border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; }

        /* --- LOGIKA TOGGLE DESKTOP & MOBILE --- */

        /* Desktop Mode (Default) */
        @media (min-width: 768px) {
            /* Keadaan Normal: Sidebar muncul, Main & Navbar geser kanan */
            .sidebar { left: 0; }
            main { margin-left: 240px; }
            
            /* [REVISI PENTING] Navbar digeser agar tombol garis tiga tidak ketutupan sidebar */
            .navbar { margin-left: 240px; width: calc(100% - 240px); }

            /* Keadaan Tertutup (Saat tombol ditekan): Sidebar geser kiri, Main & Navbar full */
            body.sidebar-toggled .sidebar { left: -240px; }
            body.sidebar-toggled main { margin-left: 0; }
            body.sidebar-toggled .navbar { margin-left: 0; width: 100%; }
        }

        /* Mobile Mode */
        @media (max-width: 767.98px) {
            /* Keadaan Normal Mobile: Sidebar sembunyi */
            .sidebar { left: -240px; }
            main { margin-left: 0; }

            /* Keadaan Terbuka Mobile: Sidebar muncul */
            body.sidebar-toggled .sidebar { left: 0; }
            
            /* Overlay Gelap hanya di mobile */
            .sidebar-overlay {
                display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.5); z-index: 1035;
            }
            body.sidebar-toggled .sidebar-overlay { display: block; }
        }

        /* Tombol Toggle Styling */
        #sidebarToggle {
            background: transparent; border: none; font-size: 1.5rem; color: #333; cursor: pointer; margin-right: 15px;
        }
        
        @yield('styles')
    </style>
</head>
<body>

    <!-- Overlay khusus Mobile -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center">
                
                <!-- TOMBOL GARIS TIGA (Sekarang terlihat di Desktop karena Navbar sudah digeser) -->
                <button id="sidebarToggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>

                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-user-shield me-2"></i>Admin Panel
                </a>
            </div>

            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-decoration-none" href="#" role="button" data-bs-toggle="dropdown">
                        <span class="me-2 fw-bold text-dark d-none d-md-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
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

    <div class="container-fluid p-0">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebarMenu">
            <div class="sidebar-sticky">
                
                <!-- Header Mobile Close Button -->
                <div class="d-flex d-md-none justify-content-between align-items-center px-3 pt-3 pb-2">
                    <span class="fw-bold text-primary">Menu Admin</span>
                    <button type="button" class="btn-close" onclick="toggleSidebar()"></button>
                </div>

                <ul class="nav flex-column mt-2">
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
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            // Toggle class 'sidebar-toggled' pada body
            // Ini akan mengaktifkan CSS untuk menggeser sidebar, navbar, & konten
            document.body.classList.toggle('sidebar-toggled');
        }
    </script>
    
    @yield('scripts')
</body>
</html>