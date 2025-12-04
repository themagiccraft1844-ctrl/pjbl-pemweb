<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishnotes Pohon Natal</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
            color: white;
        }

        .bg-christmas {
            background: linear-gradient(to bottom, #a18cd1, #7f6eb0);
            min-height: 100vh;
            position: relative;
        }

        /* Glassmorphism Panel */
        .glass-panel {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
        }

        /* Animasi Salju */
        @keyframes fall {
            0% { transform: translateY(-10vh) translateX(0); opacity: 1; }
            100% { transform: translateY(110vh) translateX(20px); opacity: 0.3; }
        }
        .snowflake {
            position: absolute;
            top: -10px;
            color: white;
            border-radius: 50%;
            background-color: white;
            animation: fall linear infinite;
            z-index: 0;
        }

        .ornament-hover {
            cursor: pointer;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            transform-origin: center;
            transform-box: fill-box;
        }
        .ornament-hover:hover {
            transform: scale(1.4);
            z-index: 50;
        }

        .color-radio:checked + label {
            transform: scale(1.2);
            border: 2px solid white;
            box-shadow: 0 0 10px rgba(255,255,255,0.5);
        }

        .cursor-crosshair-custom {
            cursor: crosshair !important;
        }
        
        .tree-container {
            height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 10;
        }
        
        /* Form Control Dark Mode */
        .form-control-dark {
            background-color: #343a40;
            border-color: #495057;
            color: white;
        }
        .form-control-dark:focus {
            background-color: #3b4248;
            color: white;
            border-color: #ffc107;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
        }
        
        /* Tombol Delete di Modal agar terlihat rapi */
        .btn-delete-submit {
            background: none;
            border: none;
            color: inherit;
            padding: 0;
            font: inherit;
            cursor: pointer;
            outline: inherit;
        }
    </style>
</head>
<body>

    <div class="bg-christmas d-flex flex-column align-items-center justify-content-center w-100">
        
        <div id="snow-container" class="position-absolute w-100 h-100 top-0 start-0 pointer-events-none"></div>
        
        @if(session('success'))
        <div class="alert alert-success position-absolute top-0 mt-5 z-3 shadow-lg rounded-pill px-4 animate-bounce">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
        @endif

        <a href="/dashboard" class="position-absolute top-0 start-0 m-3 btn btn-danger rounded-circle shadow-lg d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; z-index: 30;">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div class="position-absolute top-0 w-100 text-center z-2 mt-4" id="main-header">
            <h1 class="display-4 fw-bold text-warning" style="text-shadow: 0 0 10px rgba(250,204,21,0.5);">
                {{ $tree->judul }}
            </h1>
            <p class="lead text-white opacity-75">
                {{ $tree->deskripsi_singkat }}
            </p>
        </div>

        <div id="placement-banner" class="position-absolute top-0 mt-5 pt-5 z-3 d-none flex-column align-items-center">
            <div class="bg-dark text-white px-4 py-2 rounded-pill border border-warning shadow d-flex align-items-center gap-2">
                <i class="fas fa-hand-pointer text-warning"></i>
                <span class="fw-bold">Klik di mana saja pada pohon!</span>
            </div>
            <button onclick="cancelPlacement()" class="btn btn-sm btn-danger mt-2 rounded-pill px-3">Batal</button>
        </div>

        <div class="tree-container w-100 px-3">
            <svg id="christmas-tree-svg" viewBox="0 0 100 120" class="h-100 w-auto overflow-visible" preserveAspectRatio="xMidYMax meet" onclick="handleSvgClick(event)">
                <image href="/images/pohonNatal/pohonNatalTerang.png" x="0" y="0" width="100" height="120"/>
                <g id="ornaments-layer"></g>
            </svg>
        </div>

        <div id="controls-dashboard" class="position-absolute bottom-0 mb-4 d-flex flex-column flex-md-row align-items-center gap-3" style="z-index: 20;">
            
            <div class="glass-panel p-2 px-4 d-flex align-items-center gap-4 shadow">
                <div class="text-center">
                    <small class="text-info text-uppercase fw-bold" style="font-size: 0.7rem;">Total Bola</small>
                    <div class="h4 fw-bold mb-0">{{ count($tree->messages) }}</div>
                </div>
                <div style="width: 1px; height: 30px; background: rgba(255,255,255,0.4);"></div>
                
                <form action="/pohon/like" method="POST" class="text-center cursor-pointer">
                    @csrf
                    <input type="hidden" name="tree_id" value="{{ $tree->id }}">
                    <button type="submit" class="bg-transparent border-0 p-0 text-white">
                        <small class="text-info text-uppercase fw-bold d-block" style="font-size: 0.7rem;">Likes</small>
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <i class="fas fa-heart transition-all {{ $isLiked ? 'text-danger' : 'text-white' }}"></i>
                            <span class="h4 fw-bold mb-0">{{ $tree->like_count }}</span>
                        </div>
                    </button>
                </form>
            </div>

            <button onclick="startPlacementMode()" class="btn btn-danger btn-lg rounded-pill shadow-lg px-4 py-3 d-flex align-items-center gap-2 border border-light">
                <i class="fas fa-plus"></i>
                <span class="fw-bold">Tambah Pesan</span>
            </button>

        </div>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden">
                <div class="h-1 w-100" style="height: 5px; background: linear-gradient(90deg, green, red, green);"></div>
                <div class="modal-header border-0 pb-0 justify-content-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center pt-0 px-4 pb-4">
                    <div class="d-inline-block bg-danger bg-opacity-10 p-3 rounded-circle mb-3 mt-n3">
                        <i class="fas fa-gift text-danger fs-2"></i>
                    </div>
                    
                    <h6 class="text-muted text-uppercase fw-bold small mb-1" id="view-label">Pesan Dari</h6>
                    <h3 class="fw-bold text-dark mb-3">
                        <span id="view-author">Nama</span>
                        <i id="view-private-icon" class="fas fa-lock fs-6 text-secondary ms-2 d-none"></i>
                    </h3>
                    
                    <div class="bg-light p-4 rounded-3 position-relative mb-4">
                        <i class="fas fa-quote-left text-secondary opacity-25 position-absolute top-0 start-0 m-2 fs-4"></i>
                        <p id="view-message" class="fs-5 fst-italic text-dark mb-0">Isi pesan...</p>
                        <i class="fas fa-quote-right text-secondary opacity-25 position-absolute bottom-0 end-0 m-2 fs-4"></i>
                    </div>

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                        
                        <form id="form-delete" action="" method="POST" class="d-none">
                            @csrf
                            <input type="hidden" name="tree_id" id="delete-tree-id">
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4" onclick="return confirm('Yakin ingin mencopot pesan ini?')">
                                <i class="fas fa-trash-alt me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-warning font-serif">Tulis Harapanmu</h5>
                    <button type="button" class="btn-close btn-close-white" onclick="closeFormModal()"></button>
                </div>
                <div class="modal-body">
                    
                    <form action="/pohon/store" method="POST">
                        @csrf
                        
                        <input type="hidden" name="tree_id" value="{{ $tree->id }}">
                        <input type="hidden" name="x" id="inputX">
                        <input type="hidden" name="y" id="inputY">

                        <div class="btn-group w-100 mb-3" role="group">
                            <input type="radio" class="btn-check" name="visibility" id="vis_public" value="public" checked onchange="updateVisDesc()">
                            <label class="btn btn-outline-light" for="vis_public"><i class="fas fa-globe me-1"></i> Publik</label>

                            <input type="radio" class="btn-check" name="visibility" id="vis_private" value="private" onchange="updateVisDesc()">
                            <label class="btn btn-outline-light" for="vis_private"><i class="fas fa-lock me-1"></i> Privat</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">Nama (Boleh Anonim)</label>
                            <input type="text" name="name" class="form-control form-control-dark rounded-3" placeholder="Contoh: Secret Santa">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">Pesan Natal</label>
                            <textarea name="message" rows="3" class="form-control form-control-dark rounded-3" placeholder="Tulis harapanmu..." required></textarea>
                            <div id="vis-desc" class="form-text mt-2 text-white-50 small">
                                <i class="fas fa-info-circle"></i> Semua orang dapat melihat pesan ini.
                            </div>
                        </div>

                        <div class="mb-4 text-center">
                            <label class="form-label text-secondary small fw-bold d-block mb-3">Pilih Warna Bola</label>
                            <div class="d-flex justify-content-center gap-3">
                                <div class="position-relative">
                                    <input type="radio" name="color" value="/images/pohonNatal/ornamenKaosKaki.png" class="position-absolute opacity-0 w-100 h-100 color-radio" checked style="cursor: pointer;">
                                    <label class="d-block rounded-circle bg-danger" style="width: 40px; height: 40px; cursor: pointer;"></label>
                                </div>
                                <div class="position-relative">
                                    <input type="radio" name="color" value="/images/pohonNatal/ornamenKado.png" class="position-absolute opacity-0 w-100 h-100 color-radio" style="cursor: pointer;">
                                    <label class="d-block rounded-circle bg-warning" style="width: 40px; height: 40px; cursor: pointer;"></label>
                                </div>
                                <div class="position-relative">
                                    <input type="radio" name="color" value="/images/pohonNatal/ornamenCandyCane.png" class="position-absolute opacity-0 w-100 h-100 color-radio" style="cursor: pointer;">
                                    <label class="d-block rounded-circle bg-success" style="width: 40px; height: 40px; cursor: pointer;"></label>
                                </div>
                                <div class="position-relative">
                                    <input type="radio" name="color" value="/images/pohonNatal/ornamenLolipop.png" class="position-absolute opacity-0 w-100 h-100 color-radio" style="cursor: pointer;">
                                    <label class="d-block rounded-circle bg-primary" style="width: 40px; height: 40px; cursor: pointer;"></label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow">
                            Gantung Pesan <i class="fas fa-tree ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // --- IDENTITAS USER (Local Storage) ---
        // Masih dibutuhkan untuk validasi visual di sisi client (siapa yg punya pesan)
        function getUserId() {
            let id = localStorage.getItem('wishnotes_uid');
            if (!id) {
                id = 'user_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                localStorage.setItem('wishnotes_uid', id);
            }
            return id;
        }

        const currentUserId = getUserId(); 
        // Note: Sebaiknya di backend menggunakan Auth::id() atau session untuk keamanan sejati.
        // localStorage hanya untuk visual "ini bola saya" jika user tidak login.
        
        // Data dari Laravel
        let messages = @json($tree->messages);
        let currentViewId = null;
        let isPlacementMode = false;
        let viewModalInstance, formModalInstance;

        document.addEventListener('DOMContentLoaded', () => {
            viewModalInstance = new bootstrap.Modal(document.getElementById('viewModal'));
            formModalInstance = new bootstrap.Modal(document.getElementById('formModal'));
            
            createSnowflakes();
            renderAllOrnaments();

            // Auto hide flash message setelah 3 detik
            const alertBox = document.querySelector('.alert');
            if(alertBox) {
                setTimeout(() => {
                    alertBox.style.transition = 'opacity 0.5s ease';
                    alertBox.style.opacity = '0';
                    setTimeout(() => alertBox.remove(), 500);
                }, 3000);
            }
        });

        // --- VISUAL SALJU ---
        function createSnowflakes() {
            const container = document.getElementById('snow-container');
            container.innerHTML = ''; 
            for (let i = 0; i < 40; i++) {
                const flake = document.createElement('div');
                flake.classList.add('snowflake');
                flake.style.left = Math.random() * 100 + '%';
                flake.style.width = (Math.random() * 0.4 + 0.2) + 'rem';
                flake.style.height = flake.style.width;
                flake.style.opacity = Math.random() * 0.5 + 0.3;
                flake.style.animationDuration = (Math.random() * 5 + 5) + 's';
                flake.style.animationDelay = (Math.random() * 5) + 's';
                container.appendChild(flake);
            }
        }

        // --- MODE KLIK POHON ---
        function startPlacementMode() {
            isPlacementMode = true;
            document.getElementById('controls-dashboard').classList.add('opacity-0', 'translate-middle-y');
            document.getElementById('controls-dashboard').style.pointerEvents = 'none';
            document.getElementById('placement-banner').classList.remove('d-none');
            document.getElementById('placement-banner').classList.add('d-flex');
            document.getElementById('christmas-tree-svg').classList.add('cursor-crosshair-custom');
            document.getElementById('main-header').style.opacity = '0.3';
        }

        function cancelPlacement() {
            isPlacementMode = false;
            document.getElementById('controls-dashboard').classList.remove('opacity-0', 'translate-middle-y');
            document.getElementById('controls-dashboard').style.pointerEvents = 'auto';
            document.getElementById('placement-banner').classList.add('d-none');
            document.getElementById('placement-banner').classList.remove('d-flex');
            document.getElementById('christmas-tree-svg').classList.remove('cursor-crosshair-custom');
            document.getElementById('main-header').style.opacity = '1';
        }

        // --- LOGIKA UTAMA: DAPATKAN KOORDINAT LALU ISI KE FORM HIDDEN ---
        function handleSvgClick(evt) {
            if (!isPlacementMode) return;
            evt.stopPropagation();

            const svg = document.getElementById('christmas-tree-svg');
            const pt = svg.createSVGPoint();
            pt.x = evt.clientX;
            pt.y = evt.clientY;
            const cursorPoint = pt.matrixTransform(svg.getScreenCTM().inverse());
            
            // 1. Dapatkan Koordinat
            const x = Math.round(cursorPoint.x * 10) / 10;
            const y = Math.round(cursorPoint.y * 10) / 10;

            // 2. Masukkan ke Input Hidden di Form
            document.getElementById('inputX').value = x;
            document.getElementById('inputY').value = y;

            // 3. Reset UI dan Buka Modal
            cancelPlacement(); // Keluar mode placement
            formModalInstance.show();
        }

        function closeFormModal() {
            formModalInstance.hide();
        }

        // --- RENDER BOLA (VISUAL SAJA) ---
        function renderAllOrnaments() {
            const container = document.getElementById('ornaments-layer');
            container.innerHTML = '';
            messages.forEach(renderBall);
        }

        function renderBall(data) {
            // Filter Privasi visual Client Side
            // Note: Data tetap ada di source code jika tidak difilter di backend.
            // Pastikan Controller Anda hanya mengirim data 'private' milik user yg login.
            
            const container = document.getElementById('ornaments-layer');
            const pos = { x: data.x || 50, y: data.y || 50 };

            const g = document.createElementNS("http://www.w3.org/2000/svg", "g");
            g.setAttribute("class", "ornament-hover");

            // Persiapan data untuk modal view
            const safeName = (data.name || '').replace(/'/g, "\\'");
            const safeMsg = (data.message || '').replace(/'/g, "\\'");
            
            // Saat diklik, panggil fungsi buka modal
            g.setAttribute("onclick", `event.stopPropagation(); openViewModal(${data.id}, '${safeName}', '${safeMsg}', '${data.visibility}', '${data.user_id}')`);

            // Tali
            const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
            line.setAttribute("x1", pos.x);
            line.setAttribute("y1", pos.y - 3);
            line.setAttribute("x2", pos.x);
            line.setAttribute("y2", pos.y - 6);
            line.setAttribute("stroke", "#FCD34D");
            line.setAttribute("stroke-width", "0.5");
            line.setAttribute("opacity", "0.8");

            // Gambar Ornamen
            const ornamentImg = document.createElementNS("http://www.w3.org/2000/svg", "image");
            ornamentImg.setAttribute("href", data.color || "/images/pohonNatal/ornamenBola.png");
            ornamentImg.setAttribute("x", pos.x - 5);
            ornamentImg.setAttribute("y", pos.y - 5);
            ornamentImg.setAttribute("width", "10");
            ornamentImg.setAttribute("height", "10");

            g.appendChild(line);
            g.appendChild(ornamentImg);

            // Icon Gembok
            if (data.visibility === 'private') {
                const lockIcon = document.createElementNS("http://www.w3.org/2000/svg", "text");
                lockIcon.setAttribute("x", pos.x);
                lockIcon.setAttribute("y", pos.y + 1.5);
                lockIcon.setAttribute("text-anchor", "middle");
                lockIcon.setAttribute("font-family", "FontAwesome");
                lockIcon.setAttribute("font-size", "3");
                lockIcon.setAttribute("fill", "white");
                lockIcon.textContent = '\uf023'; 
                g.appendChild(lockIcon);
            }

            // Animasi masuk
            g.style.opacity = '0';
            g.style.transform = 'scale(0)';
            container.appendChild(g);
            setTimeout(() => {
                g.style.transition = "all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)";
                g.style.opacity = '1';
                g.style.transform = 'scale(1)';
            }, 50);
        }

        // --- BUKA MODAL VIEW & SIAPKAN FORM DELETE ---
        function openViewModal(id, name, message, visibility, ownerId) {
            if (isPlacementMode) return;
            
            currentViewId = id; 
            
            // Isi Konten
            document.getElementById('view-author').textContent = name;
            document.getElementById('view-message').textContent = message;
            
            // Icon Gembok
            const icon = document.getElementById('view-private-icon');
            const label = document.getElementById('view-label');
            if (visibility === 'private') {
                icon.classList.remove('d-none');
                label.textContent = "Pesan Privat Dari";
            } else {
                icon.classList.add('d-none');
                label.textContent = "Pesan Dari";
            }

            // LOGIKA TOMBOL HAPUS (FORM ACTION)
            const formDelete = document.getElementById('form-delete');
            const inputDeleteId = document.getElementById('delete-tree-id');
            
            // Cek kepemilikan. 
            // '{{ auth()->user()->id ?? 0 }}' adalah ID user yang sedang login dari Laravel Blade.
            const loggedInUserId = {{ auth()->user()->id ?? 0 }};
            const isAdmin = {{ request('admin') == 'true' ? 'true' : 'false' }}; // Contoh logika admin

            if (String(ownerId) === String(loggedInUserId) || isAdmin) {
                formDelete.classList.remove('d-none');
                
                // PENTING: Update action form agar mengarah ke ID yang benar
                // Sesuaikan route ini dengan route Laravel Anda, misal: /pohon/delete
                formDelete.action = "/api/pohon/delete"; 
                inputDeleteId.value = id; 
            } else {
                formDelete.classList.add('d-none');
            }

            viewModalInstance.show();
        }

        function updateVisDesc() {
            const desc = document.getElementById('vis-desc');
            const isPrivate = document.getElementById('vis_private').checked;
            
            if(isPrivate) {
                desc.innerHTML = '<i class="fas fa-user-secret text-warning"></i> Hanya kamu yang bisa melihat pesan ini.';
                desc.className = "form-text mt-2 text-warning small fw-bold";
            } else {
                desc.innerHTML = '<i class="fas fa-info-circle"></i> Semua orang dapat melihat pesan ini.';
                desc.className = "form-text mt-2 text-white-50 small";
            }
        }
    </script>
</body>
</html>