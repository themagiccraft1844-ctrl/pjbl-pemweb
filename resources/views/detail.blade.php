<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Wishnotes</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #f8f9fa; min-height: 100vh; }
        
        .top-nav {
            background: white; padding: 15px 0; border-bottom: 1px solid #eee; margin-bottom: 20px;
        }

        .board-container {
            border-radius: 20px;
            padding: 30px;
            min-height: 80vh;
            height: 100%;
            background-repeat: no-repeat;
            background-attachment: local; 
            /* Default background jika tidak ada tipe */
            background-color: #f8f9fa;
            border: 2px dashed #ddd;
        }

        .sidebar-container {
            background-color: #e9ecef;
            border-radius: 20px;
            padding: 30px;
            height: 100%;
            min-height: 80vh;
        }

        .btn-back-circle {
            width: 40px; height: 40px; border-radius: 50%; background: #eee; 
            display: flex; align-items: center; justify-content: center; 
            color: #333; text-decoration: none; transition: 0.3s;
        }
        .btn-back-circle:hover { background: #ddd; color: #000; }

        .nav-link-custom {
            display: block; padding: 12px 20px; background: white; 
            margin-bottom: 10px; border-radius: 10px; text-decoration: none; 
            color: #555; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: 0.2s; cursor: pointer;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            background: #a18cd1; color: white; transform: translateX(5px);
        }

        /* --- STYLES BARU: STICKY NOTES --- */
        .pop-in { animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes popIn { from { transform: scale(0.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        
        .sticky-note {
            padding: 20px;
            border-radius: 2px 2px 15px 2px; /* Efek lipatan */
            box-shadow: 2px 4px 10px rgba(0,0,0,0.1);
            transform: rotate(-2deg); /* Miring sedikit */
            transition: transform 0.2s;
            min-height: 150px;
            display: flex; flex-direction: column; justify-content: space-between;
            font-family: 'Comic Sans MS', cursive, sans-serif; /* Font tulis tangan */
        }
        .sticky-note:hover { transform: scale(1.05) rotate(0deg); z-index: 10; cursor: pointer; }
        
        /* Warna Kertas */
        .bg-paper-yellow { background-color: #fff9c4; color: #555; }
        .bg-paper-blue { background-color: #e3f2fd; color: #444; }
        .bg-paper-pink { background-color: #f8bbd0; color: #666; }
    </style>
</head>
<body>

    <div class="top-nav">
        <div class="container d-flex align-items-center">
            <a href="dashboard" class="btn-back-circle me-3"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h5 class="fw-bold m-0" id="header-title">Judul Container</h5>
                <small class="text-muted" id="header-author">by Author</small>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row g-4">
            
            <div class="col-md-8">
                <div class="board-container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold" id="board-title-text" style="color: #d68c45;">
                            <i class="fas fa-thumbtack me-2"></i>Papan Pesan
                        </h4>
                        <span class="badge bg-warning text-dark">Public</span>
                    </div>

                    <div class="row g-3" id="messages-container">
                        <div id="empty-state" class="col-12 text-center py-5">
                            <h5 class="fw-bold text-muted opacity-50" id="empty-text">Belum ada pesan. Kirimkan pesan pertama Anda!</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="sidebar-container">

                    <h5 class="fw-bold mb-4 text-secondary">Menu Navigasi</h5>

                    <a href="#" class="nav-link-custom">
                        <i class="fas fa-thumbs-up me-2"></i> Like Postingan
                    </a>

                    <div class="nav-link-custom" data-bs-toggle="modal" data-bs-target="#messageModal">
                        <i class="fas fa-plus me-2"></i> Tambah Pesan
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="bg-white p-3 rounded-3 shadow-sm mb-4">
                        <h6 class="fw-bold mb-1" style="color:#a18cd1;">Apa Itu Wishnotes?</h6>
                        <p class="text-muted small mb-0">
                            Wishnotes adalah web untuk mengirimkan pesan secara anonim maupun public kepada siapapun yang kita mau.
                        </p>
                    </div>

                    <div class="bg-white p-3 rounded-3 shadow-sm text-center">
                        <h6 class="text-muted mb-1">Total Pesan</h6>
                        <h2 class="fw-bold text-primary mb-0" id="stats-count">0</h2>
                    </div>
                </div>
            </div>

        </div> 
    </div>

    <div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-secondary">💌 Tulis Pesan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="msgForm">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Nama Kamu (Boleh Anonim)</label>
                            <input type="text" class="form-control rounded-pill" id="msgName" placeholder="Contoh: Secret Admirer">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Isi Pesan</label>
                            <textarea class="form-control" id="msgText" rows="3" style="border-radius: 15px;" placeholder="Tulis harapanmu..." required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Warna Kertas</label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="paperColor" id="c1" value="yellow" checked>
                                <label class="btn rounded-circle p-3 border-0" for="c1" style="background-color: #fff9c4; border: 1px solid #ddd;"></label>

                                <input type="radio" class="btn-check" name="paperColor" id="c2" value="blue">
                                <label class="btn rounded-circle p-3 border-0" for="c2" style="background-color: #e3f2fd; border: 1px solid #ddd;"></label>

                                <input type="radio" class="btn-check" name="paperColor" id="c3" value="pink">
                                <label class="btn rounded-circle p-3 border-0" for="c3" style="background-color: #f8bbd0; border: 1px solid #ddd;"></label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="button" class="btn btn-primary rounded-pill fw-bold" onclick="postMessage()" style="background: #a18cd1; border: none;">Kirim Pesan 🚀</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Variabel Global untuk menghitung pesan
        let messageCount = 0;

        document.addEventListener('DOMContentLoaded', function() {
            // 1. SETUP TAMPILAN (BACKGROUND) BERDASARKAN URL
            const urlParams = new URLSearchParams(window.location.search);
            const title = urlParams.get('title') || 'Judul Container';
            const author = urlParams.get('author') || 'Anonim';
            const type = urlParams.get('type') || 'tree'; 

            document.getElementById('header-title').innerText = title;
            document.getElementById('header-author').innerText = 'by ' + author;

            const board = document.querySelector('.board-container');
            const boardTitle = document.querySelector('#board-title-text');
            const emptyText = document.getElementById('empty-text');

            // Reset Default
            board.style.backgroundColor = '';
            board.style.backgroundImage = '';
            
            if (type === 'tree') {
                board.style.backgroundColor = '#e8f5e9';
                board.style.borderColor = '#a5d6a7';
                boardTitle.style.color = '#2e7d32';
            } else if (type === 'mading') {
                board.style.backgroundColor = '#2B65EC'; 
                // Pastikan file gambar ada, atau ganti dengan warna solid
                board.style.backgroundImage = 'url("pohonNatalTerang.png")'; 
                board.style.backgroundSize = 'contain';
                board.style.backgroundPosition = 'center';
                board.style.borderColor = '#9c27b0'; 
                
                boardTitle.style.color = '#fff'; 
                boardTitle.style.textShadow = '1px 1px 3px rgba(0,0,0,0.8)'; 
                if(emptyText) {
                    emptyText.classList.remove('text-muted');
                    emptyText.classList.add('text-white');
                    emptyText.style.textShadow = '1px 1px 3px rgba(0,0,0,0.8)';
                }
            } else if (type === 'mailbox') {
                board.style.backgroundColor = '#f3e5f5';
                board.style.borderColor = '#ce93d8';
                boardTitle.style.color = '#8e24aa';
            }
        });

        // 2. FUNGSI POST MESSAGE (DIPANGGIL DARI MODAL)
        function postMessage() {
            // Ambil nilai dari Form
            const nameInput = document.getElementById('msgName').value;
            const textInput = document.getElementById('msgText').value;
            
            // Nama Default jika kosong
            const senderName = nameInput.trim() === "" ? "Anonim" : nameInput;

            // Validasi: Pesan tidak boleh kosong
            if(textInput.trim() === "") {
                alert("Isi pesan dulu dong!");
                return;
            }

            // Ambil Warna Kertas
            const colors = document.getElementsByName('paperColor');
            let selectedColor = 'bg-paper-yellow'; // Default
            for(let c of colors) {
                if(c.checked) selectedColor = `bg-paper-${c.value}`;
            }

            // Hilangkan tulisan "Belum ada pesan"
            const emptyState = document.getElementById('empty-state');
            if(emptyState) {
                emptyState.style.display = 'none';
            }

            // Buat HTML Sticky Note Baru
            const newNoteHTML = `
                <div class="col-6 col-md-4 pop-in">
                    <div class="sticky-note ${selectedColor}">
                        <p class="mb-2" style="font-size: 0.95rem;">"${textInput}"</p>
                        <div class="d-flex justify-content-between align-items-end mt-2">
                            <small class="fw-bold opacity-75">- ${senderName}</small>
                            <small class="opacity-50" style="font-size: 0.7rem;">Baru saja</small>
                        </div>
                    </div>
                </div>
            `;

            // Masukkan ke Container
            const container = document.getElementById('messages-container');
            container.insertAdjacentHTML('beforeend', newNoteHTML);

            // Update Counter
            messageCount++;
            document.getElementById('stats-count').innerText = messageCount;

            // Reset Form & Tutup Modal
            document.getElementById('msgForm').reset();
            
            // Tutup Modal secara manual
            const modalEl = document.getElementById('messageModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            modalInstance.hide();
        }
    </script>

</body>
</html>