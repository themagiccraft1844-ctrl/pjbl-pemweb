<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishnotes - Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #f8f9fa; color: #444; }
        
        /* Navbar Styles */
        .navbar { background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.03); z-index: 1030; }
        .navbar-brand { font-weight: 800; color: #a18cd1 !important; font-size: 1.5rem; }
        .nav-profile-img { width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 2px solid #fbc2eb; }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            color: white; border-radius: 20px; margin-bottom: 30px; 
            position: relative; overflow: hidden; transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(161, 140, 209, 0.2);
        }
        .hero-section::after {
            content: '\f004'; font-family: "Font Awesome 6 Free"; font-weight: 900;
            position: absolute; right: -20px; bottom: -30px; font-size: 150px; opacity: 0.1; transform: rotate(-20deg);
        }

        /* Animations */
        .fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Card Styling */
        .wish-card {
            border: none; border-radius: 20px; transition: all 0.3s; background: white; 
            overflow: hidden; height: 100%; position: relative; cursor: pointer; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        .wish-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }

        /* Skin Indicators */
        .skin-badge {
            position: absolute; top: 15px; right: 15px; width: 40px; height: 40px; border-radius: 12px; 
            display: flex; align-items: center; justify-content: center; color: white; font-size: 1.1rem; z-index: 2;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .skin-tree { background: linear-gradient(45deg, #61c0bf, #2193b0); }
        .skin-mading { background: linear-gradient(45deg, #ff9a9e, #fecfef); }
        .skin-mailbox { background: linear-gradient(45deg, #fce38a, #f38181); }

        .card-body { padding: 25px; }
        .card-title { font-weight: 700; color: #444; font-size: 1.1rem; }
        .card-text { color: #888; font-size: 0.9rem; min-height: 40px; }

        .btn-create { background: #fff; color: #a18cd1; font-weight: 800; border-radius: 50px; border: none; padding: 12px 30px; text-decoration: none; transition: 0.2s; }
        .btn-create:hover { background: #f0f0f0; transform: scale(1.05); }

        /* Desktop Tabs */
        .nav-pills .nav-link { cursor: pointer; transition: all 0.3s; user-select: none; font-weight: 600; color: #6c757d; }
        .nav-pills .nav-link.active { background-color: #a18cd1; color: white !important; box-shadow: 0 4px 10px rgba(161, 140, 209, 0.4); }
        .nav-pills .nav-link:hover:not(.active) { background-color: #e9ecef; }
        
        .empty-state { text-align: center; padding: 50px 0; color: #adb5bd; }
        .empty-state i { font-size: 4rem; margin-bottom: 20px; opacity: 0.5; }

        /* === MOBILE RESPONSIVE TWEAKS === */
        @media (max-width: 991px) {
            body { padding-bottom: 90px; } /* Space for Bottom Nav */
            .hero-section { border-radius: 0; margin-left: -12px; margin-right: -12px; width: calc(100% + 24px); border-radius: 0 0 25px 25px; }
            .navbar-brand { font-size: 1.3rem; }
            .container { padding-left: 20px; padding-right: 20px; }
        }

        /* === BOTTOM NAVIGATION (Mobile Only) === */
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; width: 100%; background: white;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.05); z-index: 1040;
            display: flex; justify-content: space-around; align-items: center;
            padding: 10px 0; border-radius: 20px 20px 0 0;
        }
        .nav-item-mobile {
            text-align: center; color: #adb5bd; text-decoration: none; font-size: 0.7rem; flex: 1;
            transition: all 0.3s; display: flex; flex-direction: column; align-items: center;
        }
        .nav-item-mobile i { font-size: 1.4rem; margin-bottom: 4px; transition: transform 0.2s; }
        .nav-item-mobile.active { color: #a18cd1; font-weight: 700; }
        .nav-item-mobile.active i { transform: translateY(-3px); }
        
        /* Floating Action Button (Center Add) */
        .fab-add {
            width: 55px; height: 55px; background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.6rem; box-shadow: 0 8px 15px rgba(161, 140, 209, 0.4);
            transform: translateY(-25px); border: 4px solid #f8f9fa; transition: transform 0.2s;
        }
        .fab-add:active { transform: scale(0.95); }

        /* Custom Offcanvas Style */
        .offcanvas-bottom { height: auto !important; max-height: 80vh; border-radius: 20px 20px 0 0; }
    </style>

    <!-- Navbar Atas (Desktop Full, Mobile Simple) -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="dashboard"><i class="fa-solid fa-gift me-2 text-warning"></i>Wishnotes</a>
            
            <!-- Mobile Search Toggle (Hanya di Mobile) -->
            <button class="btn btn-light rounded-circle shadow-sm d-lg-none text-muted" onclick="alert('Fitur cari akan segera hadir!')">
                <i class="fas fa-search"></i>
            </button>

            <!-- Desktop Menu (Disembunyikan di Mobile) -->
            <div class="collapse navbar-collapse justify-content-end d-none d-lg-block" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center bg-light rounded-pill px-3 py-1" href="#" role="button" data-bs-toggle="dropdown">
                            <span class="me-2 fw-bold text-dark small">{{ optional(auth()->user())->name ?? 'Tamu' }}</span>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(optional(auth()->user())->name ?? 'Guest') }}&background=a18cd1&color=fff" class="nav-profile-img">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 mt-2" style="border-radius: 15px;">
                            @auth
                                <li><a class="dropdown-item rounded py-2" href="profil"><i class="fas fa-user-circle me-2 text-primary"></i> Profil Saya</a></li>
                                <li><a class="dropdown-item rounded py-2" href="friendlist"><i class="fas fa-user-plus me-2 text-success"></i> Teman</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <li><button type="submit" class="dropdown-item rounded py-2 text-danger fw-bold"><i class="fas fa-sign-out-alt me-2"></i> Logout</button></li>
                                </form>
                            @else
                                <li><a class="dropdown-item rounded fw-bold text-primary" href="{{ route('login') }}">Masuk</a></li>
                                <li><a class="dropdown-item rounded" href="{{ route('register') }}">Daftar</a></li>
                            @endauth
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <!-- Hero Section -->
        <div class="hero-section d-flex justify-content-between align-items-center p-4 p-md-5" id="hero-section">
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
                    <!-- Tombol Desktop -->
                    <button class="btn btn-create shadow-sm d-none d-lg-inline-block" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus me-2"></i>Buat Wishnote
                    </button>
                    <!-- Tombol Mobile (Simplified) -->
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

        <!-- DESKTOP TABS (Disembunyikan di Mobile) -->
        <ul class="nav nav-pills mb-4 justify-content-center justify-content-md-start d-none d-lg-flex" id="pills-tab">
            @auth
                <li class="nav-item">
                    <a class="nav-link active rounded-pill px-4 nav-desk-item" id="desk-nav-beranda" onclick="switchSection('beranda')">
                        <i class="fas fa-home me-2"></i> Beranda
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <a class="nav-link rounded-pill px-4 nav-desk-item" id="desk-nav-teman" onclick="switchSection('teman')">
                        <i class="fas fa-user-friends me-2"></i> Teman
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <a class="nav-link rounded-pill px-4 nav-desk-item" id="desk-nav-saya" onclick="switchSection('saya')">
                        <i class="fas fa-folder-open me-2"></i> Milik Saya
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link active rounded-pill px-4 nav-desk-item" id="desk-nav-populer" onclick="switchSection('populer')">
                        <i class="fas fa-fire me-2"></i> Populer
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <a class="nav-link rounded-pill px-4 nav-desk-item" id="desk-nav-telusuri" onclick="switchSection('telusuri')">
                        <i class="fas fa-compass me-2"></i> Telusuri
                    </a>
                </li>
            @endauth
        </ul>

        <!-- CONTENT AREA -->
        <div id="main-content">
            @auth
                <!-- SECTION BERANDA -->
                <div id="section-beranda" class="section-content fade-in">
                    <h5 class="fw-bold text-secondary mb-3 d-flex align-items-center"><i class="fas fa-fire text-warning me-2"></i> Sedang Populer</h5>
                    <!-- Grid Responsif: 1 Kolom HP, 2 Tablet, 3 Desktop -->
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 mb-5">
                        @include('partials.wishnote_list', ['notes' => $popularWishnotes])
                    </div>

                    <h5 class="fw-bold text-secondary mb-3 d-flex align-items-center"><i class="fas fa-clock text-info me-2"></i> Terbaru</h5>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        @include('partials.wishnote_list', ['notes' => $recentWishnotes])
                    </div>
                </div>

                <!-- SECTION TEMAN -->
                <div id="section-teman" class="section-content fade-in d-none">
                    <h5 class="fw-bold text-secondary mb-3 ms-1">Update Teman</h5>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        @if($friendsWishnotes->count() > 0)
                            @include('partials.wishnote_list', ['notes' => $friendsWishnotes])
                        @else
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class="fas fa-users-slash"></i>
                                    <p>Belum ada update.<br>Cari teman baru yuk!</p>
                                    <a href="friendlist" class="btn btn-outline-primary rounded-pill btn-sm">Cari Teman</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- SECTION SAYA -->
                <div id="section-saya" class="section-content fade-in d-none">
                    <h5 class="fw-bold text-secondary mb-3 ms-1">Wishnote Saya</h5>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        @if($myWishnotes->count() > 0)
                            @include('partials.wishnote_list', ['notes' => $myWishnotes, 'isMine' => true])
                        @else
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class="fas fa-folder-open"></i>
                                    <p>Masih kosong nih.</p>
                                    <button class="btn btn-primary rounded-pill btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">Buat Sekarang</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            @else
                <!-- GUEST SECTIONS -->
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

    <!-- BOTTOM NAVBAR (Mobile Only) -->
    <div class="bottom-nav d-lg-none">
        @auth
            <a href="javascript:void(0)" class="nav-item-mobile active" id="mob-nav-beranda" onclick="switchSection('beranda')">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
            
            <a href="javascript:void(0)" class="nav-item-mobile" id="mob-nav-teman" onclick="switchSection('teman')">
                <i class="fas fa-user-friends"></i>
                <span>Teman</span>
            </a>

            <!-- FAB ADD BUTTON -->
            <a href="javascript:void(0)" class="fab-add" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus"></i>
            </a>

            <a href="javascript:void(0)" class="nav-item-mobile" id="mob-nav-saya" onclick="switchSection('saya')">
                <i class="fas fa-folder"></i>
                <span>Saya</span>
            </a>

            <!-- Menu Akun (DROP UP / OFFCANVAS) -->
            <!-- Sekarang mentrigger Offcanvas, bukan link langsung -->
            <a href="javascript:void(0)" class="nav-item-mobile" data-bs-toggle="offcanvas" data-bs-target="#accountOffcanvas">
                <i class="fas fa-user-circle"></i>
                <span>Akun</span>
            </a>
        @else
            <a href="javascript:void(0)" class="nav-item-mobile active" id="mob-nav-populer" onclick="switchSection('populer')">
                <i class="fas fa-fire"></i>
                <span>Populer</span>
            </a>
            <a href="javascript:void(0)" class="nav-item-mobile" id="mob-nav-telusuri" onclick="switchSection('telusuri')">
                <i class="fas fa-compass"></i>
                <span>Telusuri</span>
            </a>
            <!-- <a href="{{ route('login') }}" class="fab-add">
                <i class="fas fa-sign-in-alt"></i>
            </a> -->
            <a href="{{ route('login') }}" class="nav-item-mobile">
                <i class="fas fa-user"></i>
                <span>Masuk</span>
            </a>
        @endauth
    </div>

    <!-- OFFCANVAS AKUN (Mobile Only Menu Drop-Up) -->
    @auth
    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="accountOffcanvas">
        <div class="offcanvas-header bg-light border-bottom pb-2">
            <h5 class="offcanvas-title fw-bold text-secondary">Menu Akun</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body pt-3 pb-4">
            <!-- Info User Ringkas -->
            <div class="d-flex align-items-center mb-4 p-3 rounded-4" style="background: #f8f9fa;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(optional(auth()->user())->name ?? 'Guest') }}&background=a18cd1&color=fff" class="rounded-circle me-3" width="50" height="50">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">{{ optional(auth()->user())->name ?? 'User' }}</h5>
                    <small class="text-muted">{{ optional(auth()->user())->email ?? 'user@example.com' }}</small>
                </div>
            </div>

            <!-- List Menu -->
            <div class="list-group list-group-flush">
                <a href="profil" class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex align-items-center">
                    <i class="fas fa-user-edit me-3 text-primary text-center" style="width: 25px;"></i> 
                    <span class="fw-bold text-dark">Profil Saya</span>
                </a>
                <a href="friendlist" class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex align-items-center">
                    <i class="fas fa-users me-3 text-success text-center" style="width: 25px;"></i> 
                    <span class="fw-bold text-dark">Daftar Teman</span>
                </a>
                
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex align-items-center">
                        <i class="fas fa-user-shield me-3 text-purple text-center" style="width: 25px;"></i>
                        <span class="fw-bold text-dark">Admin Panel</span>
                    </a>
                @endif
                
                <div class="border-top my-2"></div>
                
                <form method="POST" action="{{ route('logout') }}" class="w-100">
                    @csrf
                    <button type="submit" class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex align-items-center text-danger w-100 bg-transparent">
                        <i class="fas fa-sign-out-alt me-3 text-center" style="width: 25px;"></i>
                        <span class="fw-bold">Keluar Aplikasi</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endauth

    <!-- Modal Create (Auth Only) -->
    @auth
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-secondary">✨ Wishnote Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="addWishForm" method="POST" action="{{ route('wishnote.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Judul</label>
                            <input type="text" class="form-control rounded-pill bg-light border-0" placeholder="Contoh: Resolusi 2025" required name='judul'>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Isi Pesan</label>
                            <textarea class="form-control bg-light border-0" rows="3" style="border-radius: 15px;" placeholder="Tulis harapanmu..." required name='deskripsi_singkat'></textarea>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Wadah</label>
                                <select class="form-select rounded-pill bg-light border-0" name='tipe_wadah'>
                                    <option value="tree">Pohon 🎄</option>
                                    <option value="mading">Mading 📝</option>
                                    <option value="mailbox">Surat 📮</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Privasi</label>
                                <select class="form-select rounded-pill bg-light border-0" name='privasi'>
                                    <option value="public">Public 🌍</option>
                                    <option value="private">Private 🔒</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-grid mt-3">
                            <button class="btn btn-primary rounded-pill fw-bold py-2" type="submit" style="background: #a18cd1; border: none;">Posting</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openDetail(id, type) {
            if(type==="tree") window.location.href=`/pohon/${id}`;
            else if(type==="mading") window.location.href=`/mading/${id}`;
            else if(type==="mailbox") window.location.href=`/mailbox/${id}`;
        }

        function switchSection(section) {
            // 1. Hide all content sections
            document.querySelectorAll('.section-content').forEach(el => el.classList.add('d-none'));
            
            // 2. Remove active class from Desktop Tabs
            document.querySelectorAll('.nav-desk-item').forEach(el => el.classList.remove('active'));
            
            // 3. Remove active class from Mobile Bottom Nav
            document.querySelectorAll('.nav-item-mobile').forEach(el => el.classList.remove('active'));

            // 4. Show target section
            const targetSection = document.getElementById('section-' + section);
            if(targetSection) targetSection.classList.remove('d-none');

            // 5. Add active class to Desktop Tab
            const deskNav = document.getElementById('desk-nav-' + section);
            if(deskNav) deskNav.classList.add('active');

            // 6. Add active class to Mobile Nav
            const mobNav = document.getElementById('mob-nav-' + section);
            if(mobNav) mobNav.classList.add('active');

            // 7. Update Hero Text (Optional, keep it simple)
            const heroTitle = document.getElementById('hero-title');
            if(heroTitle) {
                if(section === 'beranda') heroTitle.innerText = "Halo, Teman! 👋";
                else if(section === 'teman') heroTitle.innerText = "Kabar Temanmu 💌";
                else if(section === 'saya') heroTitle.innerText = "Koleksi Saya 📂";
            }
        }
    </script>
</body>
</html>