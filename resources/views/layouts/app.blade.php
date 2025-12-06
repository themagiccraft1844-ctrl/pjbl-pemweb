<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Wishnotes')</title>
    
    <!-- External Libs -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- App Styles -->
    @vite(['resources/css/style.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>

    <!-- NAVBAR GLOBAL -->
    <nav class="navbar navbar-expand-lg sticky-top mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}"><i class="fa-solid fa-gift me-2 text-warning"></i>Wishnotes</a>
            
            <div class="ms-auto d-flex align-items-center gap-3">
                <!-- Tombol Kembali: Hanya muncul jika BUKAN di halaman dashboard -->
                @unless(request()->routeIs('dashboard'))
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill btn-sm d-none d-md-inline-block">
                    <i class="fas fa-arrow-left me-1"></i> Dashboard
                </a>
                @endunless
                
                <!-- Profile Dropdown -->
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center rounded-pill px-3 py-1" href="javascript:void(0)" role="button" data-bs-toggle="dropdown">
                        <span class="me-2 fw-bold small d-none d-sm-block">{{ optional(auth()->user())->name ?? 'Guest' }}</span>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(optional(auth()->user())->name ?? 'Guest') }}&background=a18cd1&color=fff" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 mt-2" style="border-radius: 15px;">
                        @auth
                            <li><a class="dropdown-item rounded py-2" href="{{ url('profil') }}"><i class="fas fa-user-circle me-2 text-primary"></i> Profil Saya</a></li>
                            <!-- MENU TEMAN DIKEMBALIKAN DISINI -->
                            <li><a class="dropdown-item rounded py-2" href="{{ url('friendlist') }}"><i class="fas fa-user-friends me-2 text-success"></i> Teman</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <li><button type="submit" class="dropdown-item rounded text-danger py-2"><i class="fas fa-sign-out-alt me-2"></i> Logout</button></li>
                            </form>
                        @else
                            <li><a class="dropdown-item rounded" href="{{ route('login') }}">Masuk</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Dark Mode Toggle (Floating) -->
    <div class="position-fixed bottom-0 end-0 m-3" style="z-index: 9999;">
        <button class="btn btn-dark rounded-circle shadow" id="darkModeToggle" onclick="toggleDarkMode()">
            <i class="fas fa-moon"></i>
        </button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleDarkMode() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            const btn = document.getElementById('darkModeToggle');
            btn.innerHTML = newTheme === 'light' ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
            btn.className = newTheme === 'light' ? 'btn btn-dark rounded-circle shadow' : 'btn btn-light rounded-circle shadow';
        }

        // Apply saved theme on load
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            const btn = document.getElementById('darkModeToggle');
            if(btn) {
                btn.innerHTML = savedTheme === 'light' ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
                btn.className = savedTheme === 'light' ? 'btn btn-dark rounded-circle shadow' : 'btn btn-light rounded-circle shadow';
            }
        })();
    </script>
    
    @stack('scripts')
</body>
</html>