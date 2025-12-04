<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mading->judul }} - Wishnotes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&family=Patrick+Hand&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #a18cd1;
            min-height: 100vh;
            overflow: hidden; /* Mencegah scroll body, scroll hanya di board jika perlu */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* --- CORKBOARD STYLES --- */
        .corkboard-wrapper {
            width: 100%;
            height: 80vh; /* Tinggi area mading */
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wooden-frame {
            width: 100%;
            height: 100%;
            background-color: #d8c29d;
            background-image: radial-gradient(#c7ad85 2px, transparent 2px), radial-gradient(#c7ad85 2px, transparent 2px);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
            border: 15px solid #5d4037;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            position: relative;
            overflow: hidden; /* Penting agar note tidak keluar frame */
            cursor: default;
        }

        /* --- STICKY NOTES --- */
        .sticky-note {
            position: absolute;
            width: 160px;
            height: 160px;
            padding: 15px;
            font-family: 'Indie Flower', cursive;
            text-align: center;
            box-shadow: 5px 5px 15px rgba(0,0,0,0.2);
            transition: transform 0.2s, z-index 0s;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            /* Warna Default jika class tidak termuat */
            background-color: #fff740; 
        }

        /* Warna-warni Note (Mapping dari Tailwind lama ke CSS biasa) */
        .bg-yellow-200 { background-color: #fff59d !important; }
        .bg-pink-200 { background-color: #f8bbd0 !important; }
        .bg-green-200 { background-color: #c8e6c9 !important; }
        .bg-blue-200 { background-color: #bbdefb !important; }

        .sticky-note:hover {
            z-index: 100 !important;
            transform: scale(1.1) !important;
            box-shadow: 10px 10px 25px rgba(0,0,0,0.3);
        }

        .pin {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #ff5252, #b71c1c);
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 1px 1px 2px rgba(0,0,0,0.5);
            z-index: 5;
        }

        .note-text {
            font-size: 1.1rem;
            line-height: 1.2;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            margin-bottom: 5px;
            color: #333;
        }

        .note-author {
            font-size: 0.8rem;
            font-weight: bold;
            color: #666;
        }

        /* --- UTILS --- */
        .cursor-pin {
            cursor: crosshair !important; /* Cursor saat mode tempel */
        }
        
        .header-title {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            transform: rotate(-1deg);
            border: 1px solid #ccc;
        }

        /* Animasi muncul */
        @keyframes popIn {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-pop {
            animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
    </style>
</head>
<body>

    <!-- Tombol Kembali -->
    <a href="/dashboard" class="btn btn-light rounded-circle shadow position-absolute top-0 start-0 m-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; z-index: 1000;">
        <i class="fas fa-arrow-left text-primary"></i>
    </a>

    <!-- Header Judul -->
    <div class="position-absolute top-0 w-100 d-flex justify-content-center pt-3" style="z-index: 500; pointer-events: none;">
        <div class="header-title px-4 py-2 rounded shadow text-center">
            <h1 class="h3 fw-bold mb-0 font-monospace text-dark">{{ $mading->judul }}</h1>
            <small class="text-muted">{{ $mading->deskripsi_singkat }}</small>
        </div>
    </div>

    <!-- Alert Mode Menempel (Hidden Default) -->
    <div id="placement-banner" class="position-absolute w-100 d-none justify-content-center" style="top: 100px; z-index: 1050;">
        <div class="alert alert-warning border-2 border-dark shadow-lg d-flex align-items-center gap-2 py-2">
            <i class="fas fa-thumbtack fa-bounce"></i>
            <strong>Klik area kosong di mading untuk menempel!</strong>
            <button onclick="cancelPlacement()" class="btn btn-sm btn-danger ms-2 rounded-circle" style="width: 25px; height: 25px; padding: 0;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Main Content: Mading Board -->
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="corkboard-wrapper px-3">
            
            <!-- THE BOARD -->
            <div id="mading-board" class="wooden-frame" onclick="handleBoardClick(event)">
                
                <!-- LOOP PESAN DARI DATABASE (BLADE) -->
                @foreach($mading->messages as $msg)
                    @php
                        // Logika rotasi acak biar natural
                        $rot = rand(-5, 5); 
                        // Menentukan warna background
                        $bgClass = $msg->color ?? 'bg-yellow-200';
                    @endphp

                    {{-- LOGIKA AKSES: Public OR Penulis OR Pemilik Mading --}}
                    @if($msg->visibility == 'public' || ($msg->visibility == 'private' && (auth()->id() == $msg->user_id || auth()->id() == $mading->users_id)))
                    <div class="sticky-note animate-pop {{ $bgClass }}" 
                         style="left: {{ $msg->x }}%; top: {{ $msg->y }}%; transform: rotate({{ $rot }}deg);"
                         onclick="openViewModal(
                            '{{ $msg->id }}', 
                            '{{ addslashes($msg->name) }}', 
                            '{{ addslashes($msg->message) }}', 
                            '{{ $bgClass }}',
                            '{{ $msg->user_id }}',
                            '{{ $msg->visibility }}'
                         )">
                        
                        <!-- Paku -->
                        <div class="pin"></div>
                        
                        <!-- Gembok jika private -->
                        @if($msg->visibility == 'private')
                            <div class="position-absolute top-0 end-0 m-1 text-secondary small">
                                <i class="fas fa-lock"></i>
                            </div>
                        @endif

                        <!-- Konten -->
                        <p class="note-text">{{ $msg->message }}</p>
                        <div class="note-author">- {{ $msg->name }}</div>
                    </div>
                    @endif
                @endforeach

            </div>
        </div>
    </div>

    <!-- Controls (Bawah) -->
    <div id="controls" class="position-absolute bottom-0 w-100 pb-4 d-flex justify-content-center gap-3" style="z-index: 600;">
        <!-- Statistik -->
        <div class="bg-white px-3 py-2 rounded shadow d-flex align-items-center gap-2">
            <i class="fas fa-sticky-note text-warning"></i>
            <span class="fw-bold">{{ $mading->messages->count() }}</span> Pesan
        </div>

        <!-- Tombol Tambah -->
        <button onclick="startPlacementMode()" class="btn btn-primary fw-bold shadow-lg rounded-pill px-4">
            <i class="fas fa-plus me-1"></i> Tempel Baru
        </button>

        <!-- Tombol Like (Madingnya) -->
        <form action="{{ route('pohon.like') }}" method="POST">
            @csrf
            <input type="hidden" name="tree_id" value="{{ $mading->id }}">
            <button type="submit" class="btn btn-light text-danger fw-bold shadow rounded-pill px-3">
                <i class="{{ $isLiked ? 'fas' : 'far' }} fa-heart"></i> {{ $mading->like_count }}
            </button>
        </form>
    </div>

    <!-- MODAL 1: INPUT NOTE (Bootstrap) -->
    <div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg" style="background-color: #fff9c4; transform: rotate(-2deg);">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-monospace fw-bold">Tulis Pesan...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pohon.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tree_id" value="{{ $mading->id }}">
                        <!-- Input Hidden Koordinat (Diisi via JS) -->
                        <input type="hidden" name="x" id="inputX">
                        <input type="hidden" name="y" id="inputY">

                        <!-- Pilihan Privasi -->
                        <div class="btn-group w-100 mb-3" role="group">
                            <input type="radio" class="btn-check" name="visibility" id="vis_pub" value="public" checked>
                            <label class="btn btn-outline-dark btn-sm" for="vis_pub">Publik</label>
                            <input type="radio" class="btn-check" name="visibility" id="vis_priv" value="private">
                            <label class="btn btn-outline-dark btn-sm" for="vis_priv">Privat</label>
                        </div>

                        <div class="mb-2">
                            <input type="text" name="name" class="form-control form-control-sm border-0 border-bottom rounded-0 bg-transparent" placeholder="Nama Pengirim (Opsional)">
                        </div>
                        <div class="mb-3">
                            <textarea name="message" class="form-control border-0 bg-light" rows="3" placeholder="Tulis sesuatu..." required style="font-family: 'Indie Flower', cursive; font-size: 1.2rem;"></textarea>
                        </div>

                        <!-- Pilihan Warna -->
                        <label class="form-label small fw-bold text-muted mb-1">Warna Kertas:</label>
                        <div class="d-flex justify-content-between mb-3 px-2">
                            <input type="radio" class="btn-check" name="color" id="clr_yellow" value="bg-yellow-200" checked>
                            <label class="btn rounded-circle shadow-sm" for="clr_yellow" style="width: 30px; height: 30px; background-color: #fff59d; border: 1px solid #ddd;"></label>
                            
                            <input type="radio" class="btn-check" name="color" id="clr_pink" value="bg-pink-200">
                            <label class="btn rounded-circle shadow-sm" for="clr_pink" style="width: 30px; height: 30px; background-color: #f8bbd0; border: 1px solid #ddd;"></label>
                            
                            <input type="radio" class="btn-check" name="color" id="clr_green" value="bg-green-200">
                            <label class="btn rounded-circle shadow-sm" for="clr_green" style="width: 30px; height: 30px; background-color: #c8e6c9; border: 1px solid #ddd;"></label>
                            
                            <input type="radio" class="btn-check" name="color" id="clr_blue" value="bg-blue-200">
                            <label class="btn rounded-circle shadow-sm" for="clr_blue" style="width: 30px; height: 30px; background-color: #bbdefb; border: 1px solid #ddd;"></label>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 font-monospace">
                            <i class="fas fa-thumbtack me-1"></i> Tempel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 2: VIEW NOTE (Bootstrap) -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" id="view-modal-content">
                <div class="modal-header border-0">
                    <div class="pin position-absolute start-50 top-0 translate-middle mt-2" style="background: red; width: 15px; height: 15px;"></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center pt-0">
                    <span id="view-badge" class="badge bg-secondary mb-2 d-none"><i class="fas fa-lock"></i> Privat</span>
                    
                    <h3 id="view-message-text" class="mb-4 text-dark" style="font-family: 'Indie Flower', cursive; line-height: 1.5;">...</h3>
                    
                    <div class="border-top pt-2">
                        <small class="text-muted text-uppercase fw-bold">Tertanda,</small>
                        <h5 id="view-author-text" class="fw-bold" style="font-family: 'Patrick Hand', cursive;">...</h5>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    
                    <!-- Tombol Hapus (Hanya muncul jika owner) -->
                    <form action="{{ route('pohon.delete') }}" method="POST" id="form-delete" class="d-none">
                        @csrf
                        <input type="hidden" name="tree_id" id="delete-msg-id">
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3" onclick="return confirm('Copot catatan ini?')">
                            <i class="fas fa-trash-alt me-1"></i> Copot
                        </button>
                    </form>

                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // State
        let isPlacementMode = false;
        let createModal, viewModal;
        
        // Data User Login & Pemilik Mading
        const currentUserId = {{ auth()->check() ? auth()->id() : 'null' }};
        const isAdmin = {{ auth()->check() && auth()->user()->role === 'admin' ? 'true' : 'false' }};
        const madingOwnerId = {{ $mading->users_id }};

        document.addEventListener('DOMContentLoaded', () => {
            createModal = new bootstrap.Modal(document.getElementById('createModal'));
            viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
        });

        // --- MODE MENEMPEL ---
        function startPlacementMode() {
            isPlacementMode = true;
            document.getElementById('mading-board').classList.add('cursor-pin');
            document.getElementById('placement-banner').classList.remove('d-none');
            document.getElementById('placement-banner').classList.add('d-flex');
            
            // Sembunyikan kontrol bawah agar tidak menghalangi
            document.getElementById('controls').style.opacity = '0.2';
        }

        function cancelPlacement() {
            isPlacementMode = false;
            document.getElementById('mading-board').classList.remove('cursor-pin');
            document.getElementById('placement-banner').classList.add('d-none');
            document.getElementById('placement-banner').classList.remove('d-flex');
            document.getElementById('controls').style.opacity = '1';
        }

        // --- KLIK PADA PAPAN ---
        function handleBoardClick(event) {
            // Hentikan jika event berasal dari sticky note (bubbling)
            if (event.target.closest('.sticky-note')) return;

            if (!isPlacementMode) return;

            // Hitung Koordinat %
            const board = event.currentTarget;
            const rect = board.getBoundingClientRect();
            
            // Posisi relatif mouse terhadap papan
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            // Konversi ke Persen
            const xPercent = (x / rect.width) * 100;
            const yPercent = (y / rect.height) * 100;

            // Masukkan ke Input Hidden Form
            document.getElementById('inputX').value = xPercent.toFixed(2);
            document.getElementById('inputY').value = yPercent.toFixed(2);

            // Buka Modal & Reset UI
            cancelPlacement();
            createModal.show();
        }

        // --- KLIK PADA NOTE (LIHAT DETAIL) ---
        function openViewModal(id, name, message, colorClass, ownerId, visibility) {
            if (isPlacementMode) return; // Jangan buka kalau lagi mode tempel

            // Isi konten modal
            document.getElementById('view-message-text').innerText = message;
            document.getElementById('view-author-text').innerText = name;

            // Set Warna Modal agar sama dengan note
            const modalContent = document.getElementById('view-modal-content');
            // Reset class warna dulu
            modalContent.className = 'modal-content border-0 shadow';
            // Mapping warna manual karena class tailwind tidak jalan di bootstrap background
            if(colorClass.includes('yellow')) modalContent.style.backgroundColor = '#fff9c4'; // lighten-4
            else if(colorClass.includes('pink')) modalContent.style.backgroundColor = '#f8bbd0';
            else if(colorClass.includes('green')) modalContent.style.backgroundColor = '#c8e6c9';
            else if(colorClass.includes('blue')) modalContent.style.backgroundColor = '#bbdefb';
            else modalContent.style.backgroundColor = '#fff';

            // Badge Privat
            const badge = document.getElementById('view-badge');
            if(visibility === 'private') badge.classList.remove('d-none');
            else badge.classList.add('d-none');

            // Logika Tombol Hapus: Pemilik Note OR Pemilik Mading OR Admin
            const deleteForm = document.getElementById('form-delete');
            const deleteInput = document.getElementById('delete-msg-id');
            
            const isMyNote = String(ownerId) === String(currentUserId);
            const isMyMading = String(madingOwnerId) === String(currentUserId);

            if (isMyNote || isMyMading || isAdmin) {
                deleteForm.classList.remove('d-none');
                deleteInput.value = id;
            } else {
                deleteForm.classList.add('d-none');
            }

            viewModal.show();
        }
    </script>
</body>
</html>