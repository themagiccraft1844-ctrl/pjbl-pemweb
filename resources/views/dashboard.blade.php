@extends('layouts.app')

@section('title', 'Dashboard - Wishnotes')

@push('styles')
    @vite(['resources/css/dashboard.css'])
    <style>
        /* Pastikan elemen interaktif punya z-index tinggi */
        .bottom-nav { z-index: 1050 !important; position: fixed; bottom: 0; width: 100%; left: 0; }
        /* Tambahkan padding di bawah agar konten tidak tertutup bottom nav di mobile */
        body { padding-bottom: 70px; }
        @media (min-width: 992px) { body { padding-bottom: 0; } }
    </style>
@endpush

@section('content')
    
    <!-- Navbar dihapus karena sudah ada di layout utama -->

    <div class="container mt-4 mb-5">
        <!-- Hero Section -->
        <div class="hero-section d-flex justify-content-between align-items-center p-4 p-md-5" id="hero-section" style="position: relative; z-index: 1;">
            <div id="hero-text-container" class="fade-in position-relative z-1">
                <h2 class="fw-bold mb-2" id="hero-title">Halo, {{ optional(auth()->user())->name ?? 'Teman' }}! 👋</h2>
                <p class="mb-4 opacity-90" id="hero-desc">
                    @auth
                        Apa harapanmu hari ini? Bagikan atau kirim pesan rahasia.
                    @else
                        Gabung sekarang untuk membuat wadah harapanmu sendiri!
                    @endauth
                </p>
                
                @auth
                    <button class="btn btn-create shadow-sm d-none d-lg-inline-block" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus me-2"></i>Buat Wishnote
                    </button>
                    <button class="btn btn-create shadow-sm d-lg-none px-4" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus"></i> Buat
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn btn-create shadow-sm">
                        Login Sekarang
                    </a>
                @endauth
            </div>
            <div class="d-none d-md-block fade-in" id="hero-icon" style="font-size: 5rem; opacity: 0.8;">🚀</div>
        </div>

        <!-- TABS -->
        <ul class="nav nav-pills mb-4 justify-content-center justify-content-md-start d-none d-lg-flex" id="pills-tab">
            @auth
                <li class="nav-item"><a class="nav-link active rounded-pill px-4 nav-desk-item" id="desk-nav-beranda" onclick="switchSection('beranda')"><i class="fas fa-home me-2"></i> Beranda</a></li>
                <li class="nav-item ms-2"><a class="nav-link rounded-pill px-4 nav-desk-item" id="desk-nav-teman" onclick="switchSection('teman')"><i class="fas fa-user-friends me-2"></i> Teman</a></li>
                <li class="nav-item ms-2"><a class="nav-link rounded-pill px-4 nav-desk-item" id="desk-nav-saya" onclick="switchSection('saya')"><i class="fas fa-folder-open me-2"></i> Milik Saya</a></li>
            @else
                <li class="nav-item"><a class="nav-link active rounded-pill px-4 nav-desk-item" id="desk-nav-populer" onclick="switchSection('populer')"><i class="fas fa-fire me-2"></i> Populer</a></li>
                <li class="nav-item ms-2"><a class="nav-link rounded-pill px-4 nav-desk-item" id="desk-nav-telusuri" onclick="switchSection('telusuri')"><i class="fas fa-compass me-2"></i> Telusuri</a></li>
            @endauth
        </ul>

        <!-- CONTENT -->
        <div id="main-content">
            @auth
                <div id="section-beranda" class="section-content fade-in">
                    <h5 class="fw-bold text-secondary mb-3 d-flex align-items-center"><i class="fas fa-fire text-warning me-2"></i> Sedang Populer</h5>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 mb-5">
                        @include('partials.wishnote_list', ['notes' => $popularWishnotes])
                    </div>
                    <h5 class="fw-bold text-secondary mb-3 d-flex align-items-center"><i class="fas fa-clock text-info me-2"></i> Terbaru</h5>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        @include('partials.wishnote_list', ['notes' => $recentWishnotes])
                    </div>
                </div>

                <div id="section-teman" class="section-content fade-in d-none">
                    <h5 class="fw-bold text-secondary mb-3 ms-1">Update Teman</h5>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        @if($friendsWishnotes->count() > 0)
                            @include('partials.wishnote_list', ['notes' => $friendsWishnotes])
                        @else
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="fas fa-users-slash fa-3x mb-3"></i>
                                <p>Belum ada update.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div id="section-saya" class="section-content fade-in d-none">
                    <h5 class="fw-bold text-secondary mb-3 ms-1">Wishnote Saya</h5>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        @if($myWishnotes->count() > 0)
                            @include('partials.wishnote_list', ['notes' => $myWishnotes, 'isMine' => true])
                        @else
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3"></i>
                                <p>Masih kosong.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div id="section-populer" class="section-content fade-in">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        @include('partials.wishnote_list', ['notes' => $popularWishnotes])
                    </div>
                </div>
                <div id="section-telusuri" class="section-content fade-in d-none">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        @include('partials.wishnote_list', ['notes' => $recentWishnotes])
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <!-- BOTTOM NAVBAR (Mobile) -->
    <div class="bottom-nav d-lg-none fixed-bottom bg-white border-top shadow-lg">
        <div class="d-flex justify-content-around py-2">
        @auth
            <a href="javascript:void(0)" class="nav-item-mobile text-decoration-none d-flex flex-column align-items-center active" id="mob-nav-beranda" onclick="switchSection('beranda')"><i class="fas fa-home mb-1"></i><span style="font-size: 0.7rem;">Beranda</span></a>
            <a href="javascript:void(0)" class="nav-item-mobile text-decoration-none d-flex flex-column align-items-center" id="mob-nav-teman" onclick="switchSection('teman')"><i class="fas fa-user-friends mb-1"></i><span style="font-size: 0.7rem;">Teman</span></a>
            <a href="javascript:void(0)" class="fab-add rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow" style="width: 45px; height: 45px; margin-top: -20px;" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus"></i></a>
            <a href="javascript:void(0)" class="nav-item-mobile text-decoration-none d-flex flex-column align-items-center" id="mob-nav-saya" onclick="switchSection('saya')"><i class="fas fa-folder mb-1"></i><span style="font-size: 0.7rem;">Saya</span></a>
            <a href="javascript:void(0)" class="nav-item-mobile text-decoration-none d-flex flex-column align-items-center" data-bs-toggle="offcanvas" data-bs-target="#accountOffcanvas"><i class="fas fa-user-circle mb-1"></i><span style="font-size: 0.7rem;">Akun</span></a>
        @else
            <a href="javascript:void(0)" class="nav-item-mobile text-decoration-none d-flex flex-column align-items-center active" id="mob-nav-populer" onclick="switchSection('populer')"><i class="fas fa-fire mb-1"></i><span style="font-size: 0.7rem;">Populer</span></a>
            <a href="javascript:void(0)" class="nav-item-mobile text-decoration-none d-flex flex-column align-items-center" id="mob-nav-telusuri" onclick="switchSection('telusuri')"><i class="fas fa-compass mb-1"></i><span style="font-size: 0.7rem;">Telusuri</span></a>
            <a href="{{ route('login') }}" class="nav-item-mobile text-decoration-none d-flex flex-column align-items-center"><i class="fas fa-user mb-1"></i><span style="font-size: 0.7rem;">Masuk</span></a>
        @endauth
        </div>
    </div>

    <!-- OFFCANVAS ACCOUNT -->
    @auth
    <div class="offcanvas offcanvas-bottom rounded-top-4" tabindex="-1" id="accountOffcanvas" style="height: auto; max-height: 80vh;">
        <div class="offcanvas-header border-bottom pb-2">
            <h5 class="offcanvas-title fw-bold">Menu Akun</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body pt-3 pb-4">
            <div class="d-flex align-items-center mb-4 p-3 rounded-4 bg-light">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(optional(auth()->user())->name ?? 'Guest') }}&background=a18cd1&color=fff" class="rounded-circle me-3" width="50">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">{{ optional(auth()->user())->name }}</h5>
                    <small class="text-muted">{{ optional(auth()->user())->email }}</small>
                </div>
            </div>
            <div class="list-group list-group-flush">
                <a href="profil" class="list-group-item list-group-item-action border-0 px-0 py-3"><i class="fas fa-user-edit me-3 text-primary"></i> Profil Saya</a>
                <a href="friendlist" class="list-group-item list-group-item-action border-0 px-0 py-3"><i class="fas fa-users me-3 text-success"></i> Daftar Teman</a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action border-0 px-0 py-3"><i class="fas fa-user-shield me-3 text-purple"></i> Admin Panel</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="w-100 mt-2">
                    @csrf
                    <button type="submit" class="list-group-item list-group-item-action border-0 px-0 py-3 text-danger bg-transparent"><i class="fas fa-sign-out-alt me-3"></i> Keluar Aplikasi</button>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL CREATE -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">✨ Wishnote Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('wishnote.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Judul</label>
                            <input type="text" name="judul" class="form-control rounded-pill" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Pesan Singkat</label>
                            <textarea name="deskripsi_singkat" class="form-control" rows="3" style="border-radius: 15px;" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Wadah</label>
                                <select name="tipe_wadah" class="form-select rounded-pill">
                                    <option value="tree">Pohon 🎄</option>
                                    <option value="mading">Mading 📝</option>
                                    <option value="mailbox">Surat 📮</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Privasi</label>
                                <select name="privasi" class="form-select rounded-pill">
                                    <option value="public">Public 🌍</option>
                                    <option value="private">Private 🔒</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 mt-2" style="background: #a18cd1; border:none;">Posting</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endauth

    <script>
        function openDetail(id, type) {
            window.location.href = `/${type}/${id}`;
        }
        function switchSection(section) {
            document.querySelectorAll('.section-content').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.nav-desk-item, .nav-item-mobile').forEach(el => el.classList.remove('active'));
            
            const target = document.getElementById('section-' + section);
            if(target) target.classList.remove('d-none');
            
            const deskBtn = document.getElementById('desk-nav-' + section);
            if(deskBtn) deskBtn.classList.add('active');
            
            const mobBtn = document.getElementById('mob-nav-' + section);
            if(mobBtn) mobBtn.classList.add('active');
        }
    </script>
@endsection