<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tree->judul }} - Wishnotes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts (Opsional untuk nuansa Natal) -->
    <link href="https://fonts.googleapis.com/css2?family=Mountains+of+Christmas:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to bottom, #a18cd1, #7f6eb0);
            min-height: 100vh;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
        }

        /* --- ORNAMENT STYLES --- */
        .ornament {
            position: absolute;
            width: 40px; /* Ukuran default */
            height: 40px;
            cursor: pointer;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 20;
            /* Pusatkan titik transformasi agar membesar dari tengah */
            transform-origin: center center;
            /* Geser -50% agar koordinat X/Y tepat di tengah gambar */
            transform: translate(-50%, -50%);
        }

        .ornament:hover {
            transform: translate(-50%, -50%) scale(1.5);
            z-index: 100;
        }

        .ornament img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));
        }

        /* Tali Gantungan (Opsional: digambar pakai border-left div kecil) */
        .ornament::before {
            content: '';
            position: absolute;
            top: -15px; /* Panjang tali */
            left: 50%;
            width: 1px;
            height: 15px;
            background-color: #ffd700; /* Warna Emas */
            opacity: 0.7;
            z-index: -1;
        }

        /* --- UTILS --- */
        .cursor-crosshair-custom {
            cursor: crosshair !important;
        }

        .tree-container {
            position: relative;
            height: 60vh; /* Sesuaikan tinggi area pohon */
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .tree-image {
            height: 100%;
            width: auto;
            object-fit: contain;
            /* Penting: pointer-events none agar klik tembus ke wrapper saat mode normal,
               tapi saat mode placement, wrapper yang menangkap klik. */
            pointer-events: none; 
            z-index: 10;
        }

        /* Wrapper khusus untuk menangkap klik placement */
        .click-area {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            height: 100%;
            /* Aspek rasio harus mirip dengan gambar pohon agar koordinat akurat */
            width: auto; 
            aspect-ratio: 100/120; /* Sesuaikan dengan rasio gambar pohon asli */
            z-index: 15;
        }

        /* Animasi Salju */
        .snowflake {
            position: absolute;
            top: -10px;
            color: white;
            border-radius: 50%;
            background-color: white;
            animation: fall linear infinite;
            pointer-events: none;
        }
        @keyframes fall {
            0% { transform: translateY(-10vh); opacity: 1; }
            100% { transform: translateY(110vh); opacity: 0.3; }
        }

        /* Animasi Muncul */
        @keyframes popIn {
            0% { transform: translate(-50%, -50%) scale(0); opacity: 0; }
            100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
        }
        .animate-pop {
            animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
    </style>
</head>
<body>

    <!-- Salju -->
    <div id="snow-container" class="position-absolute w-100 h-100 top-0 start-0 pointer-events-none" style="z-index: 1;"></div>

    <!-- Tombol Kembali -->
    <a href="/dashboard" class="btn btn-danger rounded-circle shadow-lg position-absolute top-0 start-0 m-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; z-index: 1000;">
        <i class="fas fa-arrow-left"></i>
    </a>

    <!-- Header Judul -->
    <div class="position-absolute top-0 w-100 text-center pt-4" style="z-index: 500; pointer-events: none;">
        <h1 class="display-4 fw-bold text-warning" style="text-shadow: 0 0 10px rgba(255,215,0,0.5); font-family: 'Mountains of Christmas', cursive;">
            {{ $tree->judul }}
        </h1>
        <p class="text-white opacity-75">{{ $tree->deskripsi_singkat }}</p>
    </div>

    <!-- Banner Mode Penempatan -->
    <div id="placement-banner" class="position-absolute w-100 d-none justify-content-center" style="top: 120px; z-index: 1050;">
        <div class="alert alert-dark border border-warning text-warning shadow-lg d-flex align-items-center gap-2 py-2 rounded-pill px-4">
            <i class="fas fa-hand-pointer fa-bounce"></i>
            <strong>Klik di mana saja pada pohon!</strong>
            <button onclick="cancelPlacement()" class="btn btn-sm btn-outline-danger ms-3 rounded-circle" style="width: 25px; height: 25px; padding: 0; line-height: 1;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- MAIN AREA -->
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="tree-container">
            
            <!-- Gambar Pohon (Background) -->
            <img src="/images/pohonNatal/pohonNatalTerang.png" class="tree-image" alt="Pohon Natal">

            <!-- Area Klik & Container Bola -->
            <!-- Kita pakai div transparan di atas pohon untuk menampung bola dan menangkap klik -->
            <div id="ornament-wrapper" class="click-area" onclick="handleTreeClick(event)">
                
                <!-- LOOP PESAN (BOLA) -->
                @foreach($tree->messages as $msg)
                    {{-- LOGIKA AKSES: Public OR Penulis OR Pemilik Pohon --}}
                    @if($msg->visibility == 'public' || ($msg->visibility == 'private' && (auth()->id() == $msg->user_id || auth()->id() == $tree->users_id)))
                    
                    <div class="ornament animate-pop" 
                         style="left: {{ $msg->x }}%; top: {{ $msg->y }}%;"
                         onclick="openViewModal(
                            '{{ $msg->id }}', 
                            '{{ addslashes($msg->name) }}', 
                            '{{ addslashes($msg->message) }}', 
                            '{{ $msg->user_id }}',
                            '{{ $msg->visibility }}'
                         )">
                        
                        <!-- Gambar Bola/Ornamen -->
                        <img src="{{ $msg->color ?? '/images/pohonNatal/ornamenBola.png' }}" alt="Ornamen">
                        
                        <!-- Ikon Gembok (Privat) -->
                        @if($msg->visibility == 'private')
                            <div class="position-absolute top-50 start-50 translate-middle text-white" style="font-size: 0.8rem; text-shadow: 0 0 2px black;">
                                <i class="fas fa-lock"></i>
                            </div>
                        @endif
                    </div>
                    @endif
                @endforeach

            </div>
        </div>
    </div>

    <!-- Controls (Bawah) -->
    <div id="controls" class="position-absolute bottom-0 w-100 pb-4 d-flex justify-content-center gap-3" style="z-index: 600;">
        
        <!-- Statistik -->
        <div class="bg-dark bg-opacity-75 text-white px-4 py-2 rounded-pill shadow border border-secondary d-flex align-items-center gap-3">
            <div class="text-center lh-1">
                <small class="text-uppercase text-white" style="font-size: 0.6rem; letter-spacing: 1px;">Bola</small>
                <div class="fw-bold">{{ $tree->messages->count() }}</div>
            </div>
            <div class="vr bg-secondary"></div>
            
            <!-- Tombol Like -->
            <form action="{{ route('pohon.like') }}" method="POST" class="d-flex align-items-center">
                @csrf
                <input type="hidden" name="tree_id" value="{{ $tree->id }}">
                <button type="submit" class="btn btn-link text-decoration-none p-0 text-white d-flex flex-column align-items-center lh-1">
                    <small class="text-uppercase text-white" style="font-size: 0.6rem; letter-spacing: 1px;">Likes</small>
                    <div class="fw-bold">
                        <i class="{{ $isLiked ? 'fas text-danger' : 'far' }} fa-heart me-1"></i> {{ $tree->like_count }}
                    </div>
                </button>
            </form>
        </div>

        <!-- Tombol Tambah -->
        <button onclick="startPlacementMode()" class="btn btn-danger btn-lg rounded-pill shadow-lg px-4 fw-bold border border-light">
            <i class="fas fa-plus me-2"></i> Gantung Harapan
        </button>

    </div>

    <!-- MODAL 1: FORM TAMBAH PESAN -->
    <div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-warning" style="font-family: 'Mountains of Christmas', cursive; font-size: 1.5rem;">Tulis Harapanmu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pohon.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tree_id" value="{{ $tree->id }}">
                        <input type="hidden" name="x" id="inputX">
                        <input type="hidden" name="y" id="inputY">

                        <!-- Visibility -->
                        <div class="btn-group w-100 mb-3" role="group">
                            <input type="radio" class="btn-check" name="visibility" id="vis_pub" value="public" checked>
                            <label class="btn btn-outline-light btn-sm" for="vis_pub"><i class="fas fa-globe me-1"></i> Publik</label>
                            <input type="radio" class="btn-check" name="visibility" id="vis_priv" value="private">
                            <label class="btn btn-outline-light btn-sm" for="vis_priv"><i class="fas fa-lock me-1"></i> Privat</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted text-uppercase fw-bold">Nama</label>
                            <input type="text" name="name" class="form-control bg-secondary text-white border-0" placeholder="Contoh: Secret Santa" value="{{ auth()->check() ? auth()->user()->name : '' }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted text-uppercase fw-bold">Pesan</label>
                            <textarea name="message" class="form-control bg-secondary text-white border-0" rows="3" placeholder="Semoga tahun ini..." required></textarea>
                        </div>

                        <!-- Pilihan Bola (Gambar) -->
                        <label class="form-label small text-muted text-uppercase fw-bold mb-2">Pilih Ornamen</label>
                        <div class="d-flex justify-content-center gap-3 mb-4">
                            <!-- Helper function untuk radio gambar -->
                            @php
                                $ornaments = [
                                    '/images/pohonNatal/ornamenKaosKaki.png',
                                    '/images/pohonNatal/ornamenKado.png',
                                    '/images/pohonNatal/ornamenCandyCane.png',
                                    '/images/pohonNatal/ornamenLolipop.png',
                                    '/images/pohonNatal/ornamenBola.png'
                                ];
                            @endphp

                            @foreach($ornaments as $index => $img)
                                <div class="position-relative">
                                    <input type="radio" name="color" id="orn_{{ $index }}" value="{{ $img }}" class="btn-check" {{ $index == 4 ? 'checked' : '' }}>
                                    <label for="orn_{{ $index }}" class="btn btn-outline-dark p-1 border-0 rounded-circle" style="width: 50px; height: 50px; cursor: pointer;">
                                        <img src="{{ $img }}" class="w-100 h-100 object-fit-contain hover-scale">
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-bold py-2 rounded-pill shadow">
                            Gantungkan <i class="fas fa-tree ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 2: LIHAT PESAN -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden border-0">
                <!-- Hiasan Atas -->
                <div class="w-100" style="height: 8px; background: linear-gradient(90deg, #198754 50%, #dc3545 50%); background-size: 20px 100%;"></div>
                
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body text-center px-4 pb-4">
                    <div class="mb-2">
                        <i class="fas fa-gift text-danger fa-2x"></i>
                    </div>
                    
                    <h6 class="text-uppercase text-muted small fw-bold mb-1">Pesan Dari</h6>
                    <h4 class="fw-bold text-dark mb-3" id="view-author-text">...</h4>
                    
                    <div class="bg-light p-3 rounded position-relative mb-3">
                        <i class="fas fa-quote-left text-secondary opacity-25 position-absolute top-0 start-0 m-2"></i>
                        <p class="fs-5 fst-italic text-dark mb-0 px-2" id="view-message-text">...</p>
                        <i class="fas fa-quote-right text-secondary opacity-25 position-absolute bottom-0 end-0 m-2"></i>
                    </div>

                    <!-- Badge Privat -->
                    <span id="view-badge" class="badge bg-warning text-dark d-none mb-3">
                        <i class="fas fa-lock me-1"></i> Pesan Privat
                    </span>

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                        
                        <!-- Tombol Hapus -->
                        <form action="{{ route('pohon.delete') }}" method="POST" id="form-delete" class="d-none">
                            @csrf
                            <input type="hidden" name="tree_id" id="delete-msg-id">
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4" onclick="return confirm('Copot pesan ini?')">
                                <i class="fas fa-trash-alt me-1"></i> Copot
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // State & Auth
        let isPlacementMode = false;
        let createModal, viewModal;
        const currentUserId = {{ auth()->check() ? auth()->id() : 'null' }};
        const isAdmin = {{ auth()->check() && auth()->user()->role === 'admin' ? 'true' : 'false' }};
        // ID Pemilik Pohon (Server Side)
        const treeOwnerId = {{ $tree->users_id }};

        document.addEventListener('DOMContentLoaded', () => {
            createModal = new bootstrap.Modal(document.getElementById('createModal'));
            viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
            createSnowflakes();
        });

        // --- SALJU ---
        function createSnowflakes() {
            const container = document.getElementById('snow-container');
            for (let i = 0; i < 50; i++) {
                const flake = document.createElement('div');
                flake.classList.add('snowflake');
                flake.style.left = Math.random() * 100 + '%';
                flake.style.width = (Math.random() * 5 + 2) + 'px'; // Ukuran random
                flake.style.height = flake.style.width;
                flake.style.opacity = Math.random() * 0.5 + 0.3;
                flake.style.animationDuration = (Math.random() * 5 + 5) + 's';
                flake.style.animationDelay = (Math.random() * 5) + 's';
                container.appendChild(flake);
            }
        }

        // --- MODE PENEMPATAN ---
        function startPlacementMode() {
            isPlacementMode = true;
            const wrapper = document.getElementById('ornament-wrapper');
            wrapper.classList.add('cursor-crosshair-custom');
            wrapper.style.pointerEvents = 'auto'; // Aktifkan klik di wrapper

            document.getElementById('placement-banner').classList.remove('d-none');
            document.getElementById('placement-banner').classList.add('d-flex');
            
            // Redupkan kontrol bawah
            document.getElementById('controls').style.opacity = '0.3';
            document.getElementById('controls').style.pointerEvents = 'none';
        }

        function cancelPlacement() {
            isPlacementMode = false;
            const wrapper = document.getElementById('ornament-wrapper');
            wrapper.classList.remove('cursor-crosshair-custom');
            // Kembalikan ke normal (klik wrapper tembus/tidak aktif jika tidak ada ornamen)
            // Tapi karena ornamen ada di dalam wrapper, kita biarkan auto agar ornamen bisa diklik.
            // Cuma handler klik wrapper di bawah akan menolak jika !isPlacementMode.

            document.getElementById('placement-banner').classList.add('d-none');
            document.getElementById('placement-banner').classList.remove('d-flex');
            
            document.getElementById('controls').style.opacity = '1';
            document.getElementById('controls').style.pointerEvents = 'auto';
        }

        // --- KLIK WRAPPER (UNTUK MENEMPEL) ---
        function handleTreeClick(event) {
            // Jika klik kena ornamen (bubbling), abaikan
            if (event.target.closest('.ornament')) return;

            if (!isPlacementMode) return;

            const wrapper = event.currentTarget;
            const rect = wrapper.getBoundingClientRect();
            
            // Hitung posisi relatif mouse terhadap wrapper
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            // Konversi ke Persen
            const xPercent = (x / rect.width) * 100;
            const yPercent = (y / rect.height) * 100;

            // Masukkan ke Form
            document.getElementById('inputX').value = xPercent.toFixed(2);
            document.getElementById('inputY').value = yPercent.toFixed(2);

            // Buka Modal & Reset
            cancelPlacement();
            createModal.show();
        }

        // --- KLIK ORNAMEN (LIHAT DETAIL) ---
        function openViewModal(id, name, message, ownerId, visibility) {
            if (isPlacementMode) return; // Jangan buka detail kalau lagi mau nempel

            document.getElementById('view-author-text').innerText = name;
            document.getElementById('view-message-text').innerText = message;

            // Badge Privat
            const badge = document.getElementById('view-badge');
            if (visibility === 'private') badge.classList.remove('d-none');
            else badge.classList.add('d-none');

            // Tombol Hapus: Pemilik Note OR Pemilik Pohon OR Admin
            const deleteForm = document.getElementById('form-delete');
            const deleteInput = document.getElementById('delete-msg-id');

            const isMyNote = String(ownerId) === String(currentUserId);
            const isMyTree = String(treeOwnerId) === String(currentUserId);

            if (isMyNote || isMyTree || isAdmin) {
                deleteForm.classList.remove('d-none');
                deleteInput.value = id;
            } else {
                deleteForm.classList.add('d-none');
            }

            viewModal.show();
        }
    </script>

    <!-- Custom CSS Helper untuk hover ornamen radio button -->
    <style>
        .hover-scale { transition: transform 0.2s; }
        .btn-check:checked + label img { transform: scale(1.2); filter: drop-shadow(0 0 5px gold); }
        .btn-check:checked + label { border-color: gold !important; background-color: rgba(255, 215, 0, 0.2); }
    </style>
</body>
</html>