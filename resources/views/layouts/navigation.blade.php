{{-- 
    Navbar ini menggunakan struktur Bootstrap standar seperti request awal Anda.
    Ditambahkan style="var(--...)" agar warnanya berubah dinamis sesuai tema.
--}}
<nav class="navbar navbar-expand-lg sticky-top mb-4 shadow-sm" style="background-color: var(--bg-panel); border-bottom: 1px solid var(--border-color); transition: background-color 0.3s;">
    <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}" style="color: var(--text-main);">
            <i class="fa-solid fa-gift me-2" style="color: var(--accent-start);"></i>
            <span class="text-accent">Wishnotes</span>
        </a>
        
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation" style="border-color: var(--border-color);">
            <span class="navbar-toggler-icon" style="filter: var(--navbar-toggler-filter);"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <!-- Left Side Navbar -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}" style="color: var(--text-main);">Dashboard</a>
                </li>
            </ul>

            <!-- Right Side Navbar -->
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                
                <!-- Tombol Kembali (Jika bukan di dashboard) -->
                @unless(request()->routeIs('dashboard'))
                <li class="nav-item d-none d-md-block">
                    <a href="{{ route('dashboard') }}" class="btn btn-sm rounded-pill px-3" style="border: 1px solid var(--border-color); color: var(--text-muted);">
                        <i class="fas fa-arrow-left me-1"></i> Dashboard
                    </a>
                </li>
                @endunless

                <!-- Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center rounded-pill px-3 py-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--text-main);">
                        <span class="me-2 fw-bold small d-none d-sm-block">{{ optional(auth()->user())->name ?? 'Guest' }}</span>
                        <img src="{{ optional(auth()->user())->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(optional(auth()->user())->name ?? 'Guest').'&background=random&color=fff' }}" 
                             class="rounded-circle border" 
                             style="width: 35px; height: 35px; object-fit: cover; border-color: var(--accent-start) !important;">
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 mt-2" style="border-radius: 15px; background-color: var(--bg-panel);">
                        @auth
                            <li>
                                <a class="dropdown-item rounded py-2" href="{{ route('profile.edit') }}" style="color: var(--text-main);">
                                    <i class="fas fa-user-circle me-2 text-accent"></i> Profil & Tema
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded py-2" href="{{ url('friendlist') }}" style="color: var(--text-main);">
                                    <i class="fas fa-user-friends me-2 text-accent"></i> Teman
                                </a>
                            </li>
                            <li><hr class="dropdown-divider" style="border-color: var(--border-color);"></li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <li>
                                    <button type="submit" class="dropdown-item rounded text-danger py-2">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </li>
                            </form>
                        @else
                            <li><a class="dropdown-item rounded" href="{{ route('login') }}">Masuk</a></li>
                        @endauth
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Tambahan CSS inline kecil untuk filter icon navbar saat dark mode --}}
<style>
    [data-theme="dark"] .navbar-toggler-icon {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
</style>