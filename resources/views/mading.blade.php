<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mading->judul }} - Wishnotes</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&family=Patrick+Hand&display=swap" rel="stylesheet">

    @vite(['resources/css/games.css'])
</head>
<body class="mading-body">

    <a href="/dashboard" class="btn btn-light rounded-circle shadow position-absolute top-0 start-0 m-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; z-index: 1000;">
        <i class="fas fa-arrow-left text-primary"></i>
    </a>

    <div class="position-absolute top-0 w-100 d-flex justify-content-center pt-3" style="z-index: 500; pointer-events: none;">
        <div class="px-4 py-2 rounded shadow text-center bg-white" style="transform: rotate(-1deg);">
            <h1 class="h3 fw-bold mb-0 font-monospace text-dark">{{ $mading->judul }}</h1>
            <small class="text-muted">{{ $mading->deskripsi_singkat }}</small>
        </div>
    </div>

    <div id="placement-banner" class="position-absolute w-100 d-none justify-content-center" style="top: 100px; z-index: 1050;">
        <div class="alert alert-warning border-2 border-dark shadow-lg d-flex align-items-center gap-2 py-2">
            <i class="fas fa-thumbtack fa-bounce"></i>
            <strong>Klik area kosong di mading untuk menempel!</strong>
            <button onclick="cancelPlacement()" class="btn btn-sm btn-danger ms-2 rounded-circle"><i class="fas fa-times"></i></button>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="corkboard-wrapper px-3" style="width: 100%; height: 80vh; max-width: 1200px; position: relative;">
            <div id="mading-board" class="wooden-frame h-100 w-100 position-relative overflow-hidden" onclick="handleBoardClick(event)">
                @foreach($mading->messages as $msg)
                    @php $rot = rand(-5, 5); $bgClass = $msg->color ?? 'bg-yellow-200'; @endphp
                    @if($msg->visibility == 'public' || ($msg->visibility == 'private' && (auth()->id() == $msg->user_id || auth()->id() == $mading->users_id)))
                    <div class="sticky-note animate-pop {{ $bgClass }}" 
                         style="left: {{ $msg->x }}%; top: {{ $msg->y }}%; transform: rotate({{ $rot }}deg);"
                         onclick="openViewModal('{{ $msg->id }}', '{{ addslashes($msg->name) }}', '{{ addslashes($msg->message) }}', '{{ $bgClass }}', '{{ $msg->user_id }}', '{{ $msg->visibility }}')">
                        <div class="pin" style="width: 12px; height: 12px; border-radius: 50%; background: radial-gradient(circle at 30% 30%, #ff5252, #b71c1c); position: absolute; top: 10px; left: 50%; transform: translateX(-50%);"></div>
                        @if($msg->visibility == 'private')
                            <div class="position-absolute top-0 end-0 m-1 text-secondary small"><i class="fas fa-lock"></i></div>
                        @endif
                        <p class="note-text mb-1" style="font-size: 1.1rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">{{ $msg->message }}</p>
                        <div class="note-author small fw-bold text-muted">- {{ $msg->name }}</div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div id="controls" class="position-absolute bottom-0 w-100 pb-4 d-flex justify-content-center gap-3" style="z-index: 600;">
        <div class="bg-white px-3 py-2 rounded shadow d-flex align-items-center gap-2">
            <i class="fas fa-sticky-note text-warning"></i> <span class="fw-bold">{{ $mading->messages->count() }}</span> Pesan
        </div>
        <button onclick="startPlacementMode()" class="btn btn-primary fw-bold shadow-lg rounded-pill px-4"><i class="fas fa-plus me-1"></i> Tempel Baru</button>
        <form action="{{ route('pohon.like') }}" method="POST">
            @csrf
            <input type="hidden" name="tree_id" value="{{ $mading->id }}">
            <button type="submit" class="btn btn-light text-danger fw-bold shadow rounded-pill px-3"><i class="{{ $isLiked ? 'fas' : 'far' }} fa-heart"></i> {{ $mading->like_count }}</button>
        </form>
    </div>

    <!-- MODAL CREATE -->
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
                        <input type="hidden" name="x" id="inputX">
                        <input type="hidden" name="y" id="inputY">
                        <div class="btn-group w-100 mb-3" role="group">
                            <input type="radio" class="btn-check" name="visibility" id="vis_pub" value="public" checked>
                            <label class="btn btn-outline-dark btn-sm" for="vis_pub">Publik</label>
                            <input type="radio" class="btn-check" name="visibility" id="vis_priv" value="private">
                            <label class="btn btn-outline-dark btn-sm" for="vis_priv">Privat</label>
                        </div>
                        <div class="mb-2"><input type="text" name="name" class="form-control form-control-sm border-0 border-bottom rounded-0 bg-transparent" placeholder="Nama (Opsional)"></div>
                        <div class="mb-3"><textarea name="message" class="form-control border-0 bg-light" rows="3" placeholder="Tulis sesuatu..." required style="font-family: 'Indie Flower', cursive; font-size: 1.2rem;"></textarea></div>
                        <label class="form-label small fw-bold text-muted mb-1">Warna:</label>
                        <div class="d-flex justify-content-between mb-3 px-2">
                            <input type="radio" class="btn-check" name="color" id="clr_yellow" value="bg-yellow-200" checked><label class="btn rounded-circle shadow-sm" for="clr_yellow" style="width: 30px; height: 30px; background-color: #fff59d; border: 1px solid #ddd;"></label>
                            <input type="radio" class="btn-check" name="color" id="clr_pink" value="bg-pink-200"><label class="btn rounded-circle shadow-sm" for="clr_pink" style="width: 30px; height: 30px; background-color: #f8bbd0; border: 1px solid #ddd;"></label>
                            <input type="radio" class="btn-check" name="color" id="clr_green" value="bg-green-200"><label class="btn rounded-circle shadow-sm" for="clr_green" style="width: 30px; height: 30px; background-color: #c8e6c9; border: 1px solid #ddd;"></label>
                            <input type="radio" class="btn-check" name="color" id="clr_blue" value="bg-blue-200"><label class="btn rounded-circle shadow-sm" for="clr_blue" style="width: 30px; height: 30px; background-color: #bbdefb; border: 1px solid #ddd;"></label>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 font-monospace"><i class="fas fa-thumbtack me-1"></i> Tempel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL VIEW -->
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
                    <form action="{{ route('pohon.delete') }}" method="POST" id="form-delete" class="d-none">
                        @csrf
                        <input type="hidden" name="tree_id" id="delete-msg-id">
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3" onclick="return confirm('Copot catatan ini?')">Copot</button>
                    </form>
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let isPlacementMode = false;
        let createModal, viewModal;
        const currentUserId = {{ auth()->check() ? auth()->id() : 'null' }};
        const isAdmin = {{ auth()->check() && auth()->user()->role === 'admin' ? 'true' : 'false' }};
        const madingOwnerId = {{ $mading->users_id }};

        document.addEventListener('DOMContentLoaded', () => {
            createModal = new bootstrap.Modal(document.getElementById('createModal'));
            viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
        });

        function startPlacementMode() {
            isPlacementMode = true;
            document.getElementById('mading-board').style.cursor = 'crosshair';
            document.getElementById('placement-banner').classList.remove('d-none');
            document.getElementById('placement-banner').classList.add('d-flex');
            document.getElementById('controls').style.opacity = '0.2';
        }

        function cancelPlacement() {
            isPlacementMode = false;
            document.getElementById('mading-board').style.cursor = 'default';
            document.getElementById('placement-banner').classList.add('d-none');
            document.getElementById('placement-banner').classList.remove('d-flex');
            document.getElementById('controls').style.opacity = '1';
        }

        function handleBoardClick(event) {
            if (event.target.closest('.sticky-note')) return;
            if (!isPlacementMode) return;
            const board = event.currentTarget;
            const rect = board.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;
            const xPercent = (x / rect.width) * 100;
            const yPercent = (y / rect.height) * 100;
            document.getElementById('inputX').value = xPercent.toFixed(2);
            document.getElementById('inputY').value = yPercent.toFixed(2);
            cancelPlacement();
            createModal.show();
        }

        function openViewModal(id, name, message, colorClass, ownerId, visibility) {
            if (isPlacementMode) return;
            document.getElementById('view-message-text').innerText = message;
            document.getElementById('view-author-text').innerText = name;
            const modalContent = document.getElementById('view-modal-content');
            modalContent.className = 'modal-content border-0 shadow';
            if(colorClass.includes('yellow')) modalContent.style.backgroundColor = '#fff9c4';
            else if(colorClass.includes('pink')) modalContent.style.backgroundColor = '#f8bbd0';
            else if(colorClass.includes('green')) modalContent.style.backgroundColor = '#c8e6c9';
            else if(colorClass.includes('blue')) modalContent.style.backgroundColor = '#bbdefb';
            else modalContent.style.backgroundColor = '#fff';
            
            const badge = document.getElementById('view-badge');
            if(visibility === 'private') badge.classList.remove('d-none');
            else badge.classList.add('d-none');

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