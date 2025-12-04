<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishnotes - Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/resources/css/dashboard.css">
</head>
<body>
    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #f8f9fa; }
        .navbar { background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .navbar-brand { font-weight: 700; color: #a18cd1 !important; font-size: 1.5rem; }
        .nav-profile-img { width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 2px solid #fbc2eb; }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            color: white; border-radius: 20px; margin-bottom: 30px; 
            position: relative; overflow: hidden; transition: all 0.3s ease;
        }
        .hero-section::after {
            content: '\f004'; font-family: "Font Awesome 6 Free"; font-weight: 900;
            position: absolute; right: -20px; bottom: -30px; font-size: 150px; opacity: 0.1; transform: rotate(-20deg);
        }

        .fade-in { animation: fadeIn 0.5s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Card Styling */
        .wish-card {
            border: none; border-radius: 20px; transition: all 0.3s; background: white; 
            overflow: hidden; height: 100%; position: relative; cursor: pointer; 
        }
        .wish-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

        /* Skin Indicators */
        .skin-badge {
            position: absolute; top: 15px; right: 15px; width: 40px; height: 40px; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; z-index: 2;
        }
        .skin-tree { background-color: #61c0bf; }
        .skin-mading { background-color: #ffb6b9; }
        .skin-mailbox { background-color: #fce38a; color: #555; }

        .card-body { padding: 25px; }
        .card-title { font-weight: 700; color: #444; }
        .card-text { color: #888; font-size: 0.9rem; min-height: 40px; }

        .status-pill { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; margin-top: 10px; }
        .status-public { background-color: #e3f2fd; color: #2196f3; }
        .status-private { background-color: #fbe9e7; color: #ff5722; }

        .btn-create { background: #fff; color: #a18cd1; font-weight: 700; border-radius: 50px; border: none; padding: 10px 25px; }

        /* Tabs */
        .nav-pills .nav-link { cursor: pointer; transition: all 0.3s; user-select: none; }
        .nav-pills .nav-link.active { background-color: #a18cd1; color: white !important; }
        .nav-pills .nav-link:not(.active) { color: #6c757d; background-color: transparent; }

        /* Styling khusus untuk card teman */
        .friend-card .wish-card {
            border: 2px dashed #61c0bf;
            background-color: #e0f7fa;
        }
        .friend-card .skin-badge {
            background-color: #4db6ac;
        }
        .friend-card .wish-card:hover {
            transform: translateY(-5px) scale(1.03);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
    </style>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="dashboard"><i class="fa-solid fa-gift me-2"></i>Wishnotes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item me-3">
                        <a class="nav-link text-muted" href="#" data-bs-toggle="modal" data-bs-target="#exploreModal">
                            <i class="fas fa-compass me-1"></i> Jelajahi
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <span class="me-2 fw-bold text-dark">{{ auth()->user()->name ?? 'Guest' }}</span>
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Guest' }}&background=random" class="nav-profile-img">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow p-2" style="border-radius: 15px;">
                            <li><a class="dropdown-item rounded" href="profil"><i class="fas fa-user-circle me-2 text-primary"></i> Profile</a></li>
                            <li><a class="dropdown-item rounded" href="friendlist"><i class="fas fa-user-plus me-2 text-success"></i> Teman</a></li>
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item rounded fw-bold text-purple" href="{{ route('admin.dashboard') }}" style="color: #8A2BE2;">
                                        <i class="fas fa-user-shield me-2"></i> Admin Panel
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <li><button type="submit" class="dropdown-item rounded text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</button></li>
                            </form>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <!-- Hero -->
        <div class="hero-section d-flex justify-content-between align-items-center p-4 p-md-5" id="hero-section">
            <div id="hero-text-container" class="fade-in">
                <h2 class="fw-bold mb-2" id="hero-title">Selamat Datang, {{ auth()->user()->name ?? 'Guest' }}</h2>
                <p class="mb-3 opacity-75" id="hero-desc">Buat wadah harapanmu atau sampaikan doa untuk temanmu.</p>
                <button class="btn btn-create shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus me-2"></i>Buat Wishnote Baru
                </button>
            </div>
            <div class="d-none d-md-block fade-in" id="hero-icon" style="font-size: 5rem; opacity: 0.8;">🎄 📝</div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-pills mb-4 justify-content-center justify-content-md-start" id="pills-tab">
            <li class="nav-item"><a class="nav-link active rounded-pill px-4" data-filter="all">Semua</a></li>
            <li class="nav-item ms-2"><a class="nav-link rounded-pill px-4" data-filter="mine">Milik Saya</a></li>
            <li class="nav-item ms-2"><a class="nav-link rounded-pill px-4" data-filter="friends">Teman</a></li>
        </ul>

        <!-- Cards -->
        <div class="row g-4">
            @isset($wishnotes)
            @foreach ($wishnotes as $note)
            <section class="col-12 col-sm-6 col-lg-4 filter-item {{ $note->users_id == auth()->id() ? '' : 'friend-card' }}" 
                     data-category="{{ ($note->users_id == auth()->id()) ? 'mine' : 'friends' }}">
                <article class="wish-card shadow-sm" role="region" aria-labelledby="wish-{{ $note->id }}" onclick="openDetail({{ $note->id }}, '{{ $note->tipe_wadah }}')">
                    
                    <div class="skin-badge 
                        {{ $note->tipe_wadah == 'tree' ? 'skin-tree' : ($note->tipe_wadah == 'mading' ? 'skin-mading' : 'skin-mailbox') }}">
                        <i class="fa-solid
                            {{ $note->tipe_wadah == 'tree' ? 'fa-tree' : ($note->tipe_wadah == 'mading' ? 'fa-note-sticky' : 'fa-envelope-open-text') }}">
                        </i>
                    </div>

                    <div class="card-body mt-4">
                        @if ($note->users_id == auth()->id())
                            <form action="{{ route('wishnotes.destroy', $note->id) }}" 
                                  method="POST" 
                                  onsubmit="event.stopPropagation(); return confirm ('Hapus wishnote ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mb-2" onclick="event.stopPropagation();">Hapus</button>
                            </form>
                        @endif


                        <h5 id="wish-{{ $note->id }}" class="card-title">{{ $note->judul }}</h5>
                        <p class="card-text text-truncate">{{ $note->deskripsi_singkat }} <br><small class="text-muted">ID: {{ $note->id }}</small></p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="status-pill {{ $note->privasi == 'public' ? 'status-public' : 'status-private' }}">
                                {{ ucfirst($note->privasi) }}
                            </span>
                            <small class="text-muted">
                                <i class="fas fa-envelope me-1"></i> {{ $note->messages_count ?? 0 }} Pesan
                            </small>
                        </div>

                        <hr class="my-3 text-muted opacity-25">

                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($note->user->name ?? 'User') }}&background=random" 
                                 class="rounded-circle me-2" width="25">
                            <small class="text-muted">by {{ $note->user->name ?? 'Unknown' }}</small>
                        </div>
                    </div>
                </article>
            </section>
            @endforeach
            @endisset
            @isset($friendsWishnotes)
                @foreach($friendsWishnotes as $friendNote)
                    <section class="col-12 col-sm-6 col-lg-4 filter-item friend-card" data-category="friends">
                        <article class="wish-card shadow-sm" onclick="openDetail({{ $friendNote->id }}, '{{ $friendNote->tipe_wadah }}')">
                            
                            {{-- Badge Icon Sesuai Tipe --}}
                            <div class="skin-badge 
                                {{ $friendNote->tipe_wadah == 'tree' ? 'skin-tree' : ($friendNote->tipe_wadah == 'mading' ? 'skin-mading' : 'skin-mailbox') }}">
                                <i class="fa-solid
                                    {{ $friendNote->tipe_wadah == 'tree' ? 'fa-tree' : ($friendNote->tipe_wadah == 'mading' ? 'fa-note-sticky' : 'fa-envelope-open-text') }}">
                                </i>
                            </div>

                            <div class="card-body mt-4">
                                <h5 class="card-title">{{ $friendNote->judul }}</h5>
                                <p class="card-text text-truncate">{{ $friendNote->deskripsi_singkat }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    {{-- Status Pill --}}
                                    <span class="status-pill {{ $friendNote->privasi == 'public' ? 'status-public' : 'status-private' }}">
                                        {{ ucfirst($friendNote->privasi) }}
                                    </span>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope me-1"></i> {{ $friendNote->messages_count ?? 0 }} Pesan
                                    </small>
                                </div>
                                
                                <hr class="my-3 text-muted opacity-25">
                                
                                {{-- Info Pembuat (Teman) --}}
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($friendNote->user->name ?? 'Unknown') }}&background=random" 
                                         class="rounded-circle me-2" width="25">
                                    <small class="text-muted">by {{ $friendNote->user->name ?? 'Unknown' }}</small>
                                </div>
                            </div>
                        </article>
                    </section>
                @endforeach
            @endisset
            @if(isset($friendsWishnotes) && $friendsWishnotes->isEmpty())
                 <div class="col-12 text-center py-5 filter-item d-none" data-category="friends">
                    <p class="text-muted">Teman-temanmu belum membuat Wishnote apapun (atau belum di-acc).</p>
                 </div>
            @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-secondary">✨ Wishnote Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="addWishForm" method="POST" action="{{ route('wishnote.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Judul</label>
                            <input type="text" class="form-control rounded-pill" id="inputTitle" placeholder="Contoh: Resolusi 2025" required name='judul'>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Deskripsi Singkat</label>
                            <textarea class="form-control" id="inputDesc" rows="3" style="border-radius: 15px;" placeholder="Tulis harapanmu disini..." required name='deskripsi_singkat'></textarea>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Tipe Wadah</label>
                                <select class="form-select rounded-pill" id="inputType" name='tipe_wadah'>
                                    <option value="tree">Pohon Natal 🎄</option>
                                    <option value="mading">Mading 📝</option>
                                    <option value="mailbox">Kotak Surat 📮</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Privasi</label>
                                <select class="form-select rounded-pill" id="inputStatus" name='privasi'>
                                    <option value="public">Public 🌍</option>
                                    <option value="private">Private 🔒</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button class="btn btn-primary rounded-pill fw-bold" type="submit" style="background: #a18cd1; border: none;">Simpan Wishnote</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exploreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg"> <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-secondary">🌍 Jelajahi Harapan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    
                    <div class="input-group mb-4 shadow-sm rounded-pill overflow-hidden">
                        <span class="input-group-text border-0 bg-white ps-3"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="searchExplore" class="form-control border-0 py-2" placeholder="Cari judul, deskripsi, atau nama pembuat..." style="box-shadow: none;">
                    </div>

                    <div class="explore-list" style="max-height: 400px; overflow-y: auto;">
                        <div class="row g-3" id="exploreResults">
                            @isset($exploreWishnotes)
                                        @foreach ($exploreWishnotes as $note)
                                            {{-- Tidak perlu cek if privasi public lagi di sini karena sudah difilter di Controller, tapi untuk jaga-jaga boleh saja --}}
                                            <div class="col-12 explore-item">
                                                <div class="card border-0 shadow-sm h-100" style="background: #f8f9fa; border-radius: 15px; cursor: pointer; transition: transform 0.2s;" 
                                                        onclick="openDetail({{ $note->id }}, '{{ $note->tipe_wadah }}'); document.getElementById('closeExplore').click();">
                                                    
                                                    <div class="card-body d-flex justify-content-between align-items-center">
                                                        <div style="overflow: hidden;">
                                                            <h6 class="fw-bold mb-1 text-dark search-target-title">{{ $note->judul }}</h6>
                                                            <small class="text-muted d-block text-truncate search-target-desc" style="max-width: 300px;">
                                                                {{ $note->deskripsi_singkat }}
                                                            </small>
                                                            <small class="text-primary search-target-user" style="font-size: 0.75rem;">
                                                                <i class="fas fa-user-circle me-1"></i> {{ $note->user->name ?? 'Unknown' }}
                                                            </small>
                                                        </div>
                                                        <div class="text-end">
                                                            <span class="badge rounded-pill bg-white text-secondary shadow-sm mb-1">
                                                                <i class="fas {{ $note->tipe_wadah == 'tree' ? 'fa-tree' : ($note->tipe_wadah == 'mading' ? 'fa-note-sticky' : 'fa-envelope-open-text') }}"></i>
                                                            </span>
                                                            <div class="small text-muted">{{ $note->messages_count ?? 0 }} <i class="fas fa-comment-dots"></i></div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                    @endisset
                            
                            <div id="noResults" class="text-center py-5 d-none">
                                <i class="fas fa-ghost fa-3x text-muted mb-3 opacity-50"></i>
                                <p class="text-muted">Tidak ditemukan harapan dengan kata kunci tersebut.</p>
                            </div>
                        </div>
                    </div>

                </div>
                <button id="closeExplore" type="button" class="d-none" data-bs-dismiss="modal"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openDetail(id, type) {
            if(type==="tree") window.location.href=`/pohon/${id}`;
            else if(type==="mading") window.location.href=`/mading/${id}`;
            else if(type==="mailbox") window.location.href=`/mailbox/${id}`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const heroContent = {
                all: { title: "Selamat Datang, {{ auth()->user()->name ?? 'Guest' }}", desc: "Buat wadah harapanmu atau sampaikan doa untuk temanmu.", icon: "🎄 📝" },
                mine: { title: "Koleksi Saya", desc: "Semua catatan yang telah Anda buat.", icon: "📂" },
                friends: { title: "Harapan Teman", desc: "Lihat semua wishnote temanmu.", icon: "💌" }
            };

            const tabs = document.querySelectorAll('.nav-link[data-filter]');
            const cards = document.querySelectorAll('.filter-item');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    const filterValue = this.getAttribute('data-filter');

                    cards.forEach(card => {
                        card.classList.toggle('d-none', filterValue !== 'all' && card.dataset.category !== filterValue);
                    });

                    const hero = heroContent[filterValue];
                    document.getElementById('hero-title').innerText = hero.title;
                    document.getElementById('hero-desc').innerText = hero.desc;
                    document.getElementById('hero-icon').innerText = hero.icon;
                });
            });
        });
        document.getElementById('searchExplore').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let items = document.querySelectorAll('.explore-item');
            let hasResults = false;

            items.forEach(function(item) {
                let title = item.querySelector('.search-target-title').innerText.toLowerCase();
                let desc = item.querySelector('.search-target-desc').innerText.toLowerCase();
                let user = item.querySelector('.search-target-user').innerText.toLowerCase();

                if (title.includes(filter) || desc.includes(filter) || user.includes(filter)) {
                    item.classList.remove('d-none');
                    hasResults = true;
                } else {
                    item.classList.add('d-none');
                }
            });

            // Tampilkan pesan "Tidak ditemukan" jika kosong
            let noResultsMsg = document.getElementById('noResults');
            if (hasResults) {
                noResultsMsg.classList.add('d-none');
            } else {
                noResultsMsg.classList.remove('d-none');
            }
        });
    </script>
</body>
</html>
