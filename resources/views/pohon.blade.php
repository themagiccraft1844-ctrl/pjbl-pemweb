<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tree->judul }} - Wishnotes</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mountains+of+Christmas:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/games.css'])
</head>
<body class="tree-body">

    <!-- Salju -->
    <div id="snow-container" class="position-absolute w-100 h-100 top-0 start-0 pointer-events-none" style="z-index: 1;"></div>

    <a href="/dashboard" class="btn btn-danger rounded-circle shadow-lg position-absolute top-0 start-0 m-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; z-index: 1000;">
        <i class="fas fa-arrow-left"></i>
    </a>

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
            <button onclick="cancelPlacement()" class="btn btn-sm btn-outline-danger ms-3 rounded-circle"><i class="fas fa-times"></i></button>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="position-relative" style="height: 60vh; width: 100%; display: flex; justify-content: center; align-items: center;">
            <img src="/images/pohonNatal/pohonNatalTerang.png" style="height: 100%; width: auto; object-fit: contain; pointer-events: none; z-index: 10;" alt="Pohon Natal">
            
            <div id="ornament-wrapper" class="position-absolute top-0 start-50 translate-middle-x" style="height: 100%; width: auto; aspect-ratio: 100/120; z-index: 15;" onclick="handleTreeClick(event)">
                @foreach($tree->messages as $msg)
                    @if($msg->visibility == 'public' || ($msg->visibility == 'private' && (auth()->id() == $msg->user_id || auth()->id() == $tree->users_id)))
                    <div class="ornament animate-pop" style="left: {{ $msg->x }}%; top: {{ $msg->y }}%;" onclick="openViewModal('{{ $msg->id }}', '{{ addslashes($msg->name) }}', '{{ addslashes($msg->message) }}', '{{ $msg->user_id }}', '{{ $msg->visibility }}')">
                        <img src="{{ $msg->color ?? '/images/pohonNatal/ornamenBola.png' }}" alt="Ornamen">
                        @if($msg->visibility == 'private')
                            <div class="position-absolute top-50 start-50 translate-middle text-white" style="font-size: 0.8rem; text-shadow: 0 0 2px black;"><i class="fas fa-lock"></i></div>
                        @endif
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div id="controls" class="position-absolute bottom-0 w-100 pb-4 d-flex justify-content-center gap-3" style="z-index: 600;">
        <div class="bg-dark bg-opacity-75 text-white px-4 py-2 rounded-pill shadow border border-secondary d-flex align-items-center gap-3">
            <div class="text-center lh-1">
                <small class="text-uppercase" style="font-size: 0.6rem;">Bola</small>
                <div class="fw-bold">{{ $tree->messages->count() }}</div>
            </div>
            <div class="vr bg-secondary"></div>
            <form action="{{ route('pohon.like') }}" method="POST" class="d-flex align-items-center">
                @csrf
                <input type="hidden" name="tree_id" value="{{ $tree->id }}">
                <button type="submit" class="btn btn-link text-decoration-none p-0 text-white d-flex flex-column align-items-center lh-1">
                    <small class="text-uppercase" style="font-size: 0.6rem;">Likes</small>
                    <div class="fw-bold"><i class="{{ $isLiked ? 'fas text-danger' : 'far' }} fa-heart me-1"></i> {{ $tree->like_count }}</div>
                </button>
            </form>
        </div>
        <button onclick="startPlacementMode()" class="btn btn-danger btn-lg rounded-pill shadow-lg px-4 fw-bold border border-light">
            <i class="fas fa-plus me-2"></i> Gantung Harapan
        </button>
    </div>

    <!-- MODAL CREATE -->
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
                        <div class="btn-group w-100 mb-3" role="group">
                            <input type="radio" class="btn-check" name="visibility" id="vis_pub" value="public" checked>
                            <label class="btn btn-outline-light btn-sm" for="vis_pub">Publik</label>
                            <input type="radio" class="btn-check" name="visibility" id="vis_priv" value="private">
                            <label class="btn btn-outline-light btn-sm" for="vis_priv">Privat</label>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control bg-secondary text-white border-0" placeholder="Nama" value="{{ auth()->check() ? auth()->user()->name : '' }}">
                        </div>
                        <div class="mb-3">
                            <textarea name="message" class="form-control bg-secondary text-white border-0" rows="3" placeholder="Pesan..." required></textarea>
                        </div>
                        <label class="form-label small text-muted text-uppercase fw-bold mb-2">Pilih Ornamen</label>
                        <div class="d-flex justify-content-center gap-3 mb-4">
                            @php $ornaments = ['/images/pohonNatal/ornamenKaosKaki.png', '/images/pohonNatal/ornamenKado.png', '/images/pohonNatal/ornamenCandyCane.png', '/images/pohonNatal/ornamenLolipop.png', '/images/pohonNatal/ornamenBola.png']; @endphp
                            @foreach($ornaments as $index => $img)
                                <div>
                                    <input type="radio" name="color" id="orn_{{ $index }}" value="{{ $img }}" class="btn-check" {{ $index == 4 ? 'checked' : '' }}>
                                    <label for="orn_{{ $index }}" class="btn btn-outline-dark p-1 border-0 rounded-circle" style="width: 50px; height: 50px;"><img src="{{ $img }}" class="w-100 h-100 object-fit-contain"></label>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2 rounded-pill shadow">Gantungkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL VIEW -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden border-0">
                <div class="w-100" style="height: 8px; background: linear-gradient(90deg, #198754 50%, #dc3545 50%); background-size: 20px 100%;"></div>
                <div class="modal-header border-0 pb-0"><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body text-center px-4 pb-4">
                    <div class="mb-2"><i class="fas fa-gift text-danger fa-2x"></i></div>
                    <h6 class="text-uppercase text-muted small fw-bold mb-1">Pesan Dari</h6>
                    <h4 class="fw-bold text-dark mb-3" id="view-author-text">...</h4>
                    <div class="bg-light p-3 rounded position-relative mb-3">
                        <p class="fs-5 fst-italic text-dark mb-0 px-2" id="view-message-text">...</p>
                    </div>
                    <span id="view-badge" class="badge bg-warning text-dark d-none mb-3"><i class="fas fa-lock me-1"></i> Privat</span>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                        <form action="{{ route('pohon.delete') }}" method="POST" id="form-delete" class="d-none">
                            @csrf
                            <input type="hidden" name="tree_id" id="delete-msg-id">
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4" onclick="return confirm('Copot pesan ini?')">Copot</button>
                        </form>
                    </div>
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
        const treeOwnerId = {{ $tree->users_id }};

        document.addEventListener('DOMContentLoaded', () => {
            createModal = new bootstrap.Modal(document.getElementById('createModal'));
            viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
            createSnowflakes();
        });

        function createSnowflakes() {
            const container = document.getElementById('snow-container');
            for (let i = 0; i < 50; i++) {
                const flake = document.createElement('div');
                flake.classList.add('snowflake');
                flake.style.left = Math.random() * 100 + '%';
                flake.style.width = (Math.random() * 5 + 2) + 'px';
                flake.style.height = flake.style.width;
                flake.style.opacity = Math.random() * 0.5 + 0.3;
                flake.style.animationDuration = (Math.random() * 5 + 5) + 's';
                flake.style.animationDelay = (Math.random() * 5) + 's';
                container.appendChild(flake);
            }
        }

        function startPlacementMode() {
            isPlacementMode = true;
            document.getElementById('ornament-wrapper').style.cursor = 'crosshair';
            document.getElementById('ornament-wrapper').style.pointerEvents = 'auto';
            document.getElementById('placement-banner').classList.remove('d-none');
            document.getElementById('placement-banner').classList.add('d-flex');
            document.getElementById('controls').style.opacity = '0.3';
            document.getElementById('controls').style.pointerEvents = 'none';
        }

        function cancelPlacement() {
            isPlacementMode = false;
            document.getElementById('ornament-wrapper').style.cursor = 'default';
            document.getElementById('placement-banner').classList.add('d-none');
            document.getElementById('placement-banner').classList.remove('d-flex');
            document.getElementById('controls').style.opacity = '1';
            document.getElementById('controls').style.pointerEvents = 'auto';
        }

        function handleTreeClick(event) {
            if (event.target.closest('.ornament')) return;
            if (!isPlacementMode) return;
            const wrapper = event.currentTarget;
            const rect = wrapper.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;
            const xPercent = (x / rect.width) * 100;
            const yPercent = (y / rect.height) * 100;
            document.getElementById('inputX').value = xPercent.toFixed(2);
            document.getElementById('inputY').value = yPercent.toFixed(2);
            cancelPlacement();
            createModal.show();
        }

        function openViewModal(id, name, message, ownerId, visibility) {
            if (isPlacementMode) return;
            document.getElementById('view-author-text').innerText = name;
            document.getElementById('view-message-text').innerText = message;
            const badge = document.getElementById('view-badge');
            if (visibility === 'private') badge.classList.remove('d-none');
            else badge.classList.add('d-none');
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
</body>
</html>