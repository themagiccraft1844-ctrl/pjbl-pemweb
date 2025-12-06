<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mailbox->judul }} - Wishnotes</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Courier+Prime&display=swap" rel="stylesheet">

    @vite(['resources/css/games.css'])
</head>
<body class="mailbox-body">

    <a href="/dashboard" class="btn btn-light rounded-circle shadow position-absolute top-0 start-0 m-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; z-index: 1000;">
        <i class="fas fa-arrow-left text-primary"></i>
    </a>

    <div class="position-absolute top-0 w-100 text-center pt-4" style="z-index: 500; pointer-events: none;">
        <h1 class="text-white fw-bold display-5 drop-shadow">{{ $mailbox->judul }}</h1>
        <p class="text-white-50">{{ $mailbox->deskripsi_singkat }}</p>
    </div>

    <div class="position-relative w-100 d-flex align-items-end justify-content-center" style="height: 85vh;">
        <div id="flying-letter" class="position-fixed top-50 start-50 translate-middle bg-white border border-3 border-light shadow-lg rounded d-none align-items-center justify-content-center" style="width: 200px; height: 120px; z-index: 2000;">
            <div style="width: 30px; height: 30px; background: red; border-radius: 50%;"></div>
        </div>

        <svg id="mailbox-svg" viewBox="0 0 400 600" class="h-100 w-auto mw-100 drop-shadow" style="cursor: pointer; transition: transform 0.3s ease;" onclick="openInbox()">
            <defs>
                <linearGradient id="postGrad" x1="0" y1="0" x2="1" y2="0"><stop offset="0%" stop-color="#4a4a4a"/><stop offset="50%" stop-color="#7a7a7a"/><stop offset="100%" stop-color="#4a4a4a"/></linearGradient>
                <linearGradient id="boxGrad" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#e2e8f0"/><stop offset="100%" stop-color="#cbd5e1"/></linearGradient>
            </defs>
            <ellipse cx="200" cy="580" rx="120" ry="15" fill="rgba(0,0,0,0.3)"/>
            <rect x="180" y="300" width="40" height="300" fill="url(#postGrad)"/>
            <path d="M100,150 Q200,50 300,150 V300 H100 Z" fill="#f8fafc" stroke="#94a3b8" stroke-width="2"/>
            <rect x="100" y="150" width="200" height="150" fill="url(#boxGrad)" />
            <rect x="120" y="160" width="160" height="10" rx="5" fill="#334155" />
            <rect x="110" y="160" width="180" height="130" rx="5" fill="none" stroke="#94a3b8" stroke-width="2" stroke-dasharray="10,5"/>
            <g id="mailbox-flag" class="{{ $mailbox->messages->count() > 0 ? 'flag-raised' : '' }}" style="transform-origin: 300px 250px; transition: transform 0.7s ease;">
                <rect x="300" y="240" width="10" height="80" fill="#dc2626" />
                <circle cx="305" cy="250" r="8" fill="#991b1b" />
                <rect x="305" y="240" width="60" height="20" fill="#dc2626" rx="2"/>
            </g>
            <text x="200" y="240" font-family="Courier Prime" font-weight="bold" font-size="24" fill="#475569" text-anchor="middle" letter-spacing="2">MAIL</text>
        </svg>
    </div>

    <div class="position-absolute bottom-0 w-100 pb-5 d-flex flex-column align-items-center gap-3" style="z-index: 1000;">
        <div class="bg-white bg-opacity-25 backdrop-blur border border-white rounded-pill px-4 py-2 text-white fw-bold shadow-sm">
            <i class="fas fa-envelope me-2"></i> {{ $mailbox->messages->count() }} Surat Masuk
        </div>
        <div class="d-flex gap-2">
            <button onclick="openComposeModal()" class="btn btn-primary btn-lg rounded-pill shadow-lg px-5 fw-bold"><i class="fas fa-paper-plane me-2"></i> Kirim Surat</button>
            <form action="{{ route('pohon.like') }}" method="POST">
                @csrf
                <input type="hidden" name="tree_id" value="{{ $mailbox->id }}">
                <button type="submit" class="btn btn-light btn-lg rounded-circle shadow-lg text-danger fw-bold" style="width: 50px; height: 50px;"><i class="{{ $isLiked ? 'fas' : 'far' }} fa-heart"></i></button>
            </form>
        </div>
    </div>

    <!-- MODAL COMPOSE -->
    <div class="modal fade" id="composeModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-secondary">💌 Tulis Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pohon.store') }}" method="POST" id="composeForm" onsubmit="animateSend(event)">
                        @csrf
                        <input type="hidden" name="tree_id" value="{{ $mailbox->id }}">
                        <input type="hidden" name="x" value="50">
                        <input type="hidden" name="y" value="50">
                        <div class="btn-group w-100 mb-3" role="group">
                            <input type="radio" class="btn-check" name="visibility" id="vis_pub" value="public" checked><label class="btn btn-outline-primary" for="vis_pub">Publik</label>
                            <input type="radio" class="btn-check" name="visibility" id="vis_priv" value="private"><label class="btn btn-outline-secondary" for="vis_priv">Privat</label>
                        </div>
                        <div class="mb-3"><input type="text" name="name" class="form-control bg-light" placeholder="Namamu"></div>
                        <div class="mb-3"><textarea name="message" rows="4" class="form-control bg-light" placeholder="Isi surat..." required></textarea></div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-pill">Masukkan ke Kotak Surat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL INBOX -->
    <div class="modal fade" id="inboxModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content bg-light rounded-4 shadow-lg h-75" style="min-height: 600px;">
                <div class="modal-header bg-white shadow-sm border-0">
                    <div><h4 class="modal-title fw-bold text-dark">Kotak Masuk</h4><small class="text-muted">Ketuk surat untuk membuka</small></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        @forelse($mailbox->messages as $msg)
                            @if($msg->visibility == 'public' || ($msg->visibility == 'private' && (auth()->id() == $msg->user_id || auth()->id() == $mailbox->users_id)))
                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded shadow-sm cursor-pointer" onclick="openReadModal('{{ addslashes($msg->name) }}', '{{ addslashes($msg->message) }}', '{{ $msg->created_at->diffForHumans() }}', '{{ $msg->visibility }}', '{{ $msg->id }}', '{{ $msg->user_id }}')" style="border-left: 4px solid {{ $msg->visibility == 'private' ? '#6c757d' : '#0d6efd' }};">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $msg->name }} @if($msg->visibility == 'private') <i class="fas fa-lock text-muted small ms-1"></i> @endif</h6>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $msg->created_at->format('d M') }}</small>
                                    </div>
                                    <p class="text-muted small mb-0 text-truncate">{{ $msg->message }}</p>
                                </div>
                            </div>
                            @endif
                        @empty
                            <div class="col-12 text-center py-5 text-muted"><i class="fas fa-inbox fa-3x mb-3 opacity-25"></i><p>Kosong.</p></div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL READ -->
    <div class="modal fade" id="readModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="transform: rotate(1deg);">
                <div class="w-100" style="height: 10px; background: linear-gradient(90deg, #dc3545 50%, #0d6efd 50%); background-size: 40px 100%;"></div>
                <div class="modal-body p-5" style="background-color: #fdfbf7; background-image: linear-gradient(#e1e1e1 1px, transparent 1px); background-size: 100% 1.5rem; line-height: 1.5rem; min-height: 400px; display: flex; flex-direction: column;">
                    <div class="d-flex justify-content-between mb-4">
                        <span id="read-badge" class="badge bg-secondary d-none"><i class="fas fa-lock me-1"></i> Privat</span>
                        <div class="border border-2 border-secondary p-2 ms-auto bg-light shadow-sm" style="transform: rotate(5deg);"><i class="fas fa-stamp fa-2x text-secondary opacity-50"></i></div>
                    </div>
                    <div class="flex-grow-1"><p class="lead text-dark" style="font-family: 'Courier Prime', monospace;" id="read-message-text">...</p></div>
                    <div class="border-top border-2 border-secondary pt-3 mt-4 border-dashed d-flex justify-content-between align-items-end">
                        <div><small class="text-muted text-uppercase fw-bold">Dari:</small><h5 class="fw-bold text-dark" style="font-family: 'Courier Prime', monospace;" id="read-author-text">...</h5><small class="text-muted" id="read-date-text">...</small></div>
                        <form action="{{ route('pohon.delete') }}" method="POST" id="form-delete-mail" class="d-none">@csrf<input type="hidden" name="tree_id" id="delete-mail-id"><button type="submit" class="btn btn-outline-danger btn-sm rounded-pill fw-bold" onclick="return confirm('Buang surat ini?')"><i class="fas fa-trash-alt me-1"></i> Buang</button></form>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center bg-light"><button type="button" class="btn btn-link text-decoration-none fw-bold" data-bs-dismiss="modal" onclick="openInbox()">Kembali</button></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let composeModal, inboxModal, readModal;
        const currentUserId = {{ auth()->check() ? auth()->id() : 'null' }};
        const isAdmin = {{ auth()->check() && auth()->user()->role === 'admin' ? 'true' : 'false' }};
        const mailboxOwnerId = {{ $mailbox->users_id }};

        document.addEventListener('DOMContentLoaded', () => {
            composeModal = new bootstrap.Modal(document.getElementById('composeModal'));
            inboxModal = new bootstrap.Modal(document.getElementById('inboxModal'));
            readModal = new bootstrap.Modal(document.getElementById('readModal'));
        });

        function openComposeModal() { composeModal.show(); }
        function openInbox() { readModal.hide(); inboxModal.show(); }
        function openReadModal(name, message, date, visibility, id, ownerId) {
            inboxModal.hide();
            document.getElementById('read-author-text').innerText = name;
            document.getElementById('read-message-text').innerText = message;
            document.getElementById('read-date-text').innerText = date;
            const badge = document.getElementById('read-badge');
            if(visibility === 'private') badge.classList.remove('d-none'); else badge.classList.add('d-none');
            const deleteForm = document.getElementById('form-delete-mail');
            const deleteInput = document.getElementById('delete-mail-id');
            const isMyMailbox = String(mailboxOwnerId) === String(currentUserId);
            const isMyMessage = String(ownerId) === String(currentUserId);
            if(isMyMessage || isMyMailbox || isAdmin) { deleteForm.classList.remove('d-none'); deleteInput.value = id; } else { deleteForm.classList.add('d-none'); }
            readModal.show();
        }
        function animateSend(e) {
            e.preventDefault();
            const form = e.target;
            composeModal.hide();
            const flyer = document.getElementById('flying-letter');
            flyer.classList.remove('d-none');
            flyer.classList.add('anim-flying-letter');
            setTimeout(() => { form.submit(); }, 1000);
        }
    </script>
</body>
</html>