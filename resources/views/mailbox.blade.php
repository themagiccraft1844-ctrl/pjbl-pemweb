<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mailbox->judul }} - Wishnotes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Courier+Prime&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #a18cd1;
            min-height: 100vh;
            overflow: hidden;
            font-family: 'Nunito', sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        /* --- MAILBOX ANIMATIONS --- */
        @keyframes flyIn {
            0% { transform: translate(-50%, 100vh) scale(2) rotate(10deg); opacity: 1; }
            40% { transform: translate(-50%, -20%) scale(1.5) rotate(-5deg); }
            70% { transform: translate(-50%, 10%) scale(0.8) rotate(5deg); opacity: 1; }
            100% { transform: translate(-50%, 20%) scale(0.1) rotate(0deg); opacity: 0; }
        }
        
        .anim-flying-letter {
            animation: flyIn 1.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        .mailbox-hover {
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .mailbox-hover:hover {
            transform: scale(1.02);
        }

        /* Flag Animation */
        .flag-raised {
            transform: rotate(-90deg) !important;
        }
        
        /* Paper Texture */
        .paper-texture {
            background-color: #fdfbf7;
            background-image: linear-gradient(#e1e1e1 1px, transparent 1px);
            background-size: 100% 1.5rem;
            line-height: 1.5rem;
        }

        /* Custom Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 8px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scroll::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }

        /* Mail Item Style */
        .mail-item {
            border-left: 4px solid #0d6efd; /* Default Blue */
            transition: all 0.2s;
        }
        .mail-item.private {
            border-left-color: #6c757d; /* Gray for Private */
        }
        .mail-item:hover {
            background-color: #e9ecef;
            transform: translateX(5px);
        }
    </style>
</head>
<body>

    <!-- Tombol Kembali -->
    <a href="/dashboard" class="btn btn-light rounded-circle shadow position-absolute top-0 start-0 m-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; z-index: 1000;">
        <i class="fas fa-arrow-left text-primary"></i>
    </a>

    <!-- Header Judul -->
    <div class="position-absolute top-0 w-100 text-center pt-4" style="z-index: 500; pointer-events: none;">
        <h1 class="text-white fw-bold display-5 drop-shadow">{{ $mailbox->judul }}</h1>
        <p class="text-white-50">{{ $mailbox->deskripsi_singkat }}</p>
    </div>

    <!-- MAIN SCENE: MAILBOX SVG -->
    <div class="position-relative w-100 d-flex align-items-end justify-content-center" style="height: 85vh;">
        
        <!-- Flying Letter Element (Hidden default) -->
        <div id="flying-letter" class="position-fixed top-50 start-50 translate-middle bg-white border border-3 border-light shadow-lg rounded d-none align-items-center justify-content-center" style="width: 200px; height: 120px; z-index: 2000;">
            <div style="width: 30px; height: 30px; background: red; border-radius: 50%;"></div>
        </div>

        <!-- SVG Mailbox -->
        <svg id="mailbox-svg" viewBox="0 0 400 600" class="h-100 w-auto mw-100 drop-shadow mailbox-hover" onclick="openInbox()">
            <defs>
                <linearGradient id="postGrad" x1="0" y1="0" x2="1" y2="0">
                    <stop offset="0%" stop-color="#4a4a4a"/>
                    <stop offset="50%" stop-color="#7a7a7a"/>
                    <stop offset="100%" stop-color="#4a4a4a"/>
                </linearGradient>
                <linearGradient id="boxGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#e2e8f0"/>
                    <stop offset="100%" stop-color="#cbd5e1"/>
                </linearGradient>
            </defs>
            
            <ellipse cx="200" cy="580" rx="120" ry="15" fill="rgba(0,0,0,0.3)"/>
            <rect x="180" y="300" width="40" height="300" fill="url(#postGrad)"/>
            <path d="M100,150 Q200,50 300,150 V300 H100 Z" fill="#f8fafc" stroke="#94a3b8" stroke-width="2"/>
            <rect x="100" y="150" width="200" height="150" fill="url(#boxGrad)" />
            <rect x="120" y="160" width="160" height="10" rx="5" fill="#334155" />
            <rect x="110" y="160" width="180" height="130" rx="5" fill="none" stroke="#94a3b8" stroke-width="2" stroke-dasharray="10,5"/>

            <!-- Flag Group (Rotated via JS if mail exists) -->
            <g id="mailbox-flag" class="transition-transform {{ $mailbox->messages->count() > 0 ? 'flag-raised' : '' }}" style="transform-origin: 300px 250px; transition: transform 0.7s ease;">
                <rect x="300" y="240" width="10" height="80" fill="#dc2626" />
                <circle cx="305" cy="250" r="8" fill="#991b1b" />
                <rect x="305" y="240" width="60" height="20" fill="#dc2626" rx="2"/>
            </g>

            <text x="200" y="240" font-family="Courier Prime" font-weight="bold" font-size="24" fill="#475569" text-anchor="middle" letter-spacing="2">MAIL</text>
        </svg>
    </div>

    <!-- Controls Bottom -->
    <div class="position-absolute bottom-0 w-100 pb-5 d-flex flex-column align-items-center gap-3" style="z-index: 1000;">
        <!-- Notification Bubble -->
        <div class="bg-white bg-opacity-25 backdrop-blur border border-white rounded-pill px-4 py-2 text-white fw-bold shadow-sm">
            <i class="fas fa-envelope me-2"></i> {{ $mailbox->messages->count() }} Surat Masuk
        </div>

        <div class="d-flex gap-2">
            <!-- Tombol Kirim Surat -->
            <button onclick="openComposeModal()" class="btn btn-primary btn-lg rounded-pill shadow-lg px-5 fw-bold">
                <i class="fas fa-paper-plane me-2"></i> Kirim Surat
            </button>
            
            <!-- Tombol Like -->
            <form action="{{ route('pohon.like') }}" method="POST">
                @csrf
                <input type="hidden" name="tree_id" value="{{ $mailbox->id }}">
                <button type="submit" class="btn btn-light btn-lg rounded-circle shadow-lg text-danger fw-bold" style="width: 50px; height: 50px;">
                    <i class="{{ $isLiked ? 'fas' : 'far' }} fa-heart"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL 1: TULIS SURAT -->
    <div class="modal fade" id="composeModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-secondary"><i class="fas fa-pen-nib me-2"></i>Tulis Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pohon.store') }}" method="POST" id="composeForm" onsubmit="animateSend(event)">
                        @csrf
                        <input type="hidden" name="tree_id" value="{{ $mailbox->id }}">
                        
                        <!-- Koordinat Dummy (Mailbox tidak butuh posisi akurat) -->
                        <input type="hidden" name="x" value="50">
                        <input type="hidden" name="y" value="50">

                        <div class="btn-group w-100 mb-3" role="group">
                            <input type="radio" class="btn-check" name="visibility" id="vis_pub" value="public" checked>
                            <label class="btn btn-outline-primary" for="vis_pub"><i class="fas fa-globe me-1"></i> Publik</label>
                            <input type="radio" class="btn-check" name="visibility" id="vis_priv" value="private">
                            <label class="btn btn-outline-secondary" for="vis_priv"><i class="fas fa-lock me-1"></i> Privat</label>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold text-muted text-uppercase">Dari</label>
                            <input type="text" name="name" class="form-control bg-light" placeholder="Namamu (Boleh Anonim)">
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted text-uppercase">Pesan</label>
                            <textarea name="message" rows="4" class="form-control bg-light" placeholder="Apa kabar hari ini?" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-pill">
                            Masukkan ke Kotak Surat <i class="fas fa-level-down-alt ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 2: INBOX (DAFTAR SURAT) -->
    <div class="modal fade" id="inboxModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content bg-light rounded-4 shadow-lg h-75" style="min-height: 600px;">
                <div class="modal-header bg-white shadow-sm border-0">
                    <div>
                        <h4 class="modal-title fw-bold text-dark">Kotak Masuk</h4>
                        <small class="text-muted">Ketuk surat untuk membuka amplopnya</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 custom-scroll">
                    
                    <div class="row g-3">
                        @forelse($mailbox->messages as $msg)
                            {{-- LOGIKA AKSES: Public OR Penulis OR Pemilik Mailbox --}}
                            @if($msg->visibility == 'public' || ($msg->visibility == 'private' && (auth()->id() == $msg->user_id || auth()->id() == $mailbox->users_id)))
                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded shadow-sm cursor-pointer mail-item {{ $msg->visibility == 'private' ? 'private' : '' }}"
                                     onclick="openReadModal('{{ addslashes($msg->name) }}', '{{ addslashes($msg->message) }}', '{{ $msg->created_at->diffForHumans() }}', '{{ $msg->visibility }}', '{{ $msg->id }}', '{{ $msg->user_id }}')">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold text-dark mb-0 text-truncate">
                                            {{ $msg->name }} 
                                            @if($msg->visibility == 'private') <i class="fas fa-lock text-muted small ms-1"></i> @endif
                                        </h6>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $msg->created_at->format('d M') }}</small>
                                    </div>
                                    <p class="text-muted small mb-0 text-truncate">{{ $msg->message }}</p>
                                </div>
                            </div>
                            @endif
                        @empty
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                <p>Kotak surat masih kosong.</p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 3: BACA SURAT (DETAIL) -->
    <div class="modal fade" id="readModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="transform: rotate(1deg);">
                <!-- Hiasan Amplop -->
                <div class="w-100" style="height: 10px; background: linear-gradient(90deg, #dc3545 50%, #0d6efd 50%); background-size: 40px 100%;"></div>
                
                <div class="modal-body p-5 paper-texture" style="min-height: 400px; display: flex; flex-direction: column;">
                    <div class="d-flex justify-content-between mb-4">
                        <span id="read-badge" class="badge bg-secondary d-none"><i class="fas fa-lock me-1"></i> Privat</span>
                        <div class="border border-2 border-secondary p-2 ms-auto bg-light shadow-sm" style="transform: rotate(5deg);">
                            <i class="fas fa-stamp fa-2x text-secondary opacity-50"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1">
                        <p class="lead text-dark" style="font-family: 'Courier Prime', monospace; line-height: 1.8;" id="read-message-text">...</p>
                    </div>

                    <div class="border-top border-2 border-secondary pt-3 mt-4 border-dashed d-flex justify-content-between align-items-end">
                        <div>
                            <small class="text-muted text-uppercase fw-bold">Dari:</small>
                            <h5 class="fw-bold text-dark" style="font-family: 'Courier Prime', monospace;" id="read-author-text">...</h5>
                            <small class="text-muted" id="read-date-text">...</small>
                        </div>
                        
                        <!-- Tombol Hapus -->
                        <form action="{{ route('pohon.delete') }}" method="POST" id="form-delete-mail" class="d-none">
                            @csrf
                            <input type="hidden" name="tree_id" id="delete-mail-id">
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill fw-bold" onclick="return confirm('Buang surat ini?')">
                                <i class="fas fa-trash-alt me-1"></i> Buang
                            </button>
                        </form>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center bg-light">
                    <button type="button" class="btn btn-link text-decoration-none fw-bold" data-bs-dismiss="modal" onclick="openInbox()">Kembali ke Kotak Masuk</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let composeModal, inboxModal, readModal;
        const currentUserId = {{ auth()->check() ? auth()->id() : 'null' }};
        const isAdmin = {{ auth()->check() && auth()->user()->role === 'admin' ? 'true' : 'false' }};
        // ID Pemilik Mailbox (Server Side)
        const mailboxOwnerId = {{ $mailbox->users_id }};

        document.addEventListener('DOMContentLoaded', () => {
            composeModal = new bootstrap.Modal(document.getElementById('composeModal'));
            inboxModal = new bootstrap.Modal(document.getElementById('inboxModal'));
            readModal = new bootstrap.Modal(document.getElementById('readModal'));
        });

        function openComposeModal() {
            composeModal.show();
        }

        function openInbox() {
            // Tutup modal baca jika terbuka
            readModal.hide();
            inboxModal.show();
        }

        function openReadModal(name, message, date, visibility, id, ownerId) {
            inboxModal.hide(); // Tutup inbox dulu

            document.getElementById('read-author-text').innerText = name;
            document.getElementById('read-message-text').innerText = message;
            document.getElementById('read-date-text').innerText = date;

            // Privacy Badge
            const badge = document.getElementById('read-badge');
            if(visibility === 'private') badge.classList.remove('d-none');
            else badge.classList.add('d-none');

            // Delete Button Logic
            // Hapus muncul jika: Saya pemilik surat OR Saya pemilik mailbox OR Admin
            const deleteForm = document.getElementById('form-delete-mail');
            const deleteInput = document.getElementById('delete-mail-id');
            
            // Konversi ke string agar aman saat compare
            const isMyMailbox = String(mailboxOwnerId) === String(currentUserId);
            const isMyMessage = String(ownerId) === String(currentUserId);

            if(isMyMessage || isMyMailbox || isAdmin) {
                deleteForm.classList.remove('d-none');
                deleteInput.value = id;
            } else {
                deleteForm.classList.add('d-none');
            }

            readModal.show();
        }

        // Animasi Kirim Surat (Fake Animation sebelum Submit Form)
        function animateSend(e) {
            e.preventDefault();
            const form = e.target;
            
            // Sembunyikan modal
            composeModal.hide();
            
            // Animasi surat terbang
            const flyer = document.getElementById('flying-letter');
            flyer.classList.remove('d-none');
            flyer.classList.add('anim-flying-letter');
            
            setTimeout(() => {
                form.submit();
            }, 1000); // Tunggu 1 detik animasi terbang
        }
    </script>
</body>
</html>