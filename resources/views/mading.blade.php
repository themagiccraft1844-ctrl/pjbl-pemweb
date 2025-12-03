<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishnotes - Mading Sekolah</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

         <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Handwriting style -->
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&family=Patrick+Hand&display=swap" rel="stylesheet">

    <style>
        /* CSS Pattern untuk Papan Gabus (Corkboard) */
        .corkboard-pattern {
            background-color: #d8c29d;
            background-image: radial-gradient(#c7ad85 2px, transparent 2px), radial-gradient(#c7ad85 2px, transparent 2px);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
            box-shadow: inset 0 0 50px rgba(0,0,0,0.3);
        }

        /* Frame Kayu */
        .wooden-frame {
            border: 15px solid #8d6e63;
            border-image: linear-gradient(to bottom right, #8d6e63, #5d4037) 1;
            box-shadow: 0 10px 20px rgba(0,0,0,0.4);
        }

        /* Sticky Note Styles */
        .sticky-note {
            font-family: 'Indie Flower', cursive;
            transition: transform 0.2s, box-shadow 0.2s, z-index 0s;
            box-shadow: 3px 3px 5px rgba(0,0,0,0.2);
            /* Transform origin di tengah atas (lokasi paku) */
            transform-origin: top center; 
            will-change: transform;
        }
        .sticky-note:hover {
            z-index: 50 !important;
            box-shadow: 15px 15px 25px rgba(0,0,0,0.3);
            cursor: pointer;
        }

        /* Paku Payung (Pin) */
        .pin {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #ff5252, #b71c1c);
            position: absolute;
            top: 5px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 1px 1px 2px rgba(0,0,0,0.5);
            z-index: 10;
        }

        /* Lock Icon on Note */
        .lock-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            color: rgba(0,0,0,0.4);
            font-size: 0.8rem;
        }

        /* Modal Animation (Popup Form) */
        @keyframes notePop {
            0% { transform: scale(0.5) rotate(0deg); opacity: 0; }
            100% { transform: scale(1) rotate(-2deg); opacity: 1; }
        }
        .note-anim {
            animation: notePop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        /* Animasi "Slap" saat Menempel Sticky Note */
        @keyframes slapIn {
            0% {
                opacity: 0;
                transform: translate(-50%, -80px) scale(1.5) rotate(var(--rot)); /* Mulai dari atas & besar */
                box-shadow: 20px 20px 40px rgba(0,0,0,0.5);
            }
            60% {
                opacity: 1;
                transform: translate(-50%, 0) scale(0.9) rotate(var(--rot)); /* Membentur papan (gepeng dikit) */
                box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
            }
            80% {
                transform: translate(-50%, 0) scale(1.05) rotate(var(--rot)); /* Membal sedikit */
            }
            100% {
                transform: translate(-50%, 0) scale(1) rotate(var(--rot)); /* Posisi normal */
            }
        }
        
        .animate-slap {
            animation: slapIn 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        /* Custom Cursor for Placement */
        .cursor-pin {
            cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="%23dc2626" stroke="white" stroke-width="2"><path d="M12 2L12 15M12 15L8 22M12 15L16 22"/></svg>') 16 32, crosshair;
        }

        /* Toggle Button Styles */
        .toggle-radio:checked + div {
            background-color: #4b5563; /* gray-600 */
            color: white;
            border-color: #374151;
        }
    </style>
<script type="importmap">
{
  "imports": {
    "react": "https://aistudiocdn.com/react@^19.2.0",
    "react/": "https://aistudiocdn.com/react@^19.2.0/"
  }
}
</script>
</head>
<body class="bg-[#a18cd1] h-screen w-full overflow-hidden flex flex-col items-center justify-center font-sans">

    <!-- Back Button -->
    <a href="/dashboard" class="absolute top-4 left-4 z-40 bg-white hover:bg-gray-100 text-purple-600 w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition-transform hover:scale-110">
        <i class="fas fa-arrow-left"></i>
    </a>

    <!-- Header -->
    <div class="absolute top-4 z-10 text-center pointer-events-none" id="header-area">
        <div class="bg-white/90 backdrop-blur px-6 py-2 rounded-lg shadow-md transform -rotate-1 border border-gray-300">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 font-['Patrick_Hand'] tracking-wider">
                {{ $mading->judul }}
            </h1>
            <p class="text-gray-600 text-sm">{{ $mading->deskripsi_singkat }}</p>
        </div>
    </div>

    <!-- Alert Banner (Placement Mode) -->
    <div id="placement-banner" class="absolute top-24 z-50 hidden animate-bounce">
        <div class="bg-yellow-400 text-black px-4 py-2 rounded shadow-lg border-2 border-black font-bold flex items-center gap-2 transform rotate-1">
            <i class="fas fa-thumbtack"></i>
            <span>Klik bebas di mana saja!</span>
            <button onclick="cancelPlacement()" class="ml-2 bg-red-600 text-white w-6 h-6 rounded-full text-xs hover:bg-red-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Main Corkboard Area -->
    <div class="relative w-full h-full max-w-5xl max-h-[80vh] p-4 flex items-center justify-center">
        
        <!-- The Board -->
        <div id="mading-board" class="w-full h-full wooden-frame corkboard-pattern relative overflow-hidden rounded shadow-2xl transition-all" onclick="handleBoardClick(event)">
            
            <!-- Notes Container -->
            <div id="notes-container" class="w-full h-full relative">
                <!-- Notes will be injected here via JS -->
            </div>

        </div>
    </div>

    <!-- Dashboard Controls -->
    <div id="controls" class="absolute bottom-6 flex gap-4 z-40 transition-transform duration-300">
        
        <!-- Stats -->
        <div class="bg-white/90 backdrop-blur px-4 py-2 rounded shadow border border-gray-300 flex items-center gap-3">
            <div class="text-center">
                <span class="block text-xs text-gray-500 font-bold uppercase">Notes</span>
                <span class="font-bold text-xl text-gray-800" id="total-notes">{{ $mading->messages->count() }}</span>
            </div>
        </div>

        <!-- Add Button -->
        <button onclick="startPlacementMode()" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded shadow-lg transform hover:-translate-y-1 transition-all flex items-center gap-2 font-bold border-b-4 border-blue-800 active:border-b-0 active:translate-y-1">
            <i class="fas fa-plus"></i> Tempel Catatan
        </button>
    </div>

    <!-- Modal Form (Input Note) -->
    <div id="form-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div onclick="closeFormModal()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <!-- Form Card -->
        <div class="relative bg-white w-full max-w-sm p-1 shadow-2xl transform rotate-1 note-anim">
            <div class="bg-yellow-100 p-6 border border-gray-200">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-32 h-8 bg-gray-300/50 backdrop-blur transform -rotate-1 skew-x-12"></div> <!-- Tape Effect -->

                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-['Patrick_Hand'] text-2xl font-bold text-gray-700">Tulis Pesan...</h3>
                    <button onclick="closeFormModal()" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
                </div>

                <form id="noteForm" onsubmit="event.preventDefault(); submitNote();">
                    
                    <!-- Visibility Toggle -->
                    <div class="flex mb-4 bg-white/50 rounded-lg p-1 border border-gray-300">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="visibility" value="public" class="hidden toggle-radio" checked>
                            <div class="text-center py-1 rounded text-sm font-bold text-gray-500 transition-colors">
                                <i class="fas fa-globe mr-1"></i> Publik
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="visibility" value="private" class="hidden toggle-radio">
                            <div class="text-center py-1 rounded text-sm font-bold text-gray-500 transition-colors">
                                <i class="fas fa-lock mr-1"></i> Privat
                            </div>
                        </label>
                    </div>

                    <div class="mb-3">
                        <input type="text" id="inputName" class="w-full bg-transparent border-b-2 border-gray-300 focus:border-blue-500 outline-none py-1 font-['Indie_Flower'] text-xl placeholder-gray-400" placeholder="Namamu(bisa Anonim)" required>
                    </div>
                    <div class="mb-4">
                        <textarea id="inputMessage" rows="4" class="w-full bg-white/50 border border-gray-200 rounded p-2 font-['Indie_Flower'] text-lg focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Tulis sesuatu yang seru..." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Pilih Warna Kertas</label>
                        <div class="flex gap-3 justify-center">
                            <label class="cursor-pointer transform hover:scale-110 transition-transform">
                                <input type="radio" name="noteColor" value="bg-yellow-200" class="hidden peer" checked>
                                <div class="w-8 h-8 bg-yellow-200 border-2 border-transparent peer-checked:border-gray-600 shadow-sm"></div>
                            </label>
                            <label class="cursor-pointer transform hover:scale-110 transition-transform">
                                <input type="radio" name="noteColor" value="bg-pink-200" class="hidden peer">
                                <div class="w-8 h-8 bg-pink-200 border-2 border-transparent peer-checked:border-gray-600 shadow-sm"></div>
                            </label>
                            <label class="cursor-pointer transform hover:scale-110 transition-transform">
                                <input type="radio" name="noteColor" value="bg-green-200" class="hidden peer">
                                <div class="w-8 h-8 bg-green-200 border-2 border-transparent peer-checked:border-gray-600 shadow-sm"></div>
                            </label>
                            <label class="cursor-pointer transform hover:scale-110 transition-transform">
                                <input type="radio" name="noteColor" value="bg-blue-200" class="hidden peer">
                                <div class="w-8 h-8 bg-blue-200 border-2 border-transparent peer-checked:border-gray-600 shadow-sm"></div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gray-800 text-white font-['Patrick_Hand'] text-xl py-2 rounded hover:bg-gray-700 transition-colors shadow-md">
                        Tempel <i class="fas fa-check ml-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal View Note (Detail) -->
    <div id="view-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div onclick="closeViewModal()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <!-- View Note Card -->
        <div id="view-card-content" class="relative w-full max-w-md shadow-2xl transform transition-transform duration-300 scale-100 note-anim p-1">
            <div class="bg-white p-8 min-h-[300px] flex flex-col relative">
                <!-- Visual Pin -->
                <div class="pin top-2 left-1/2 -translate-x-1/2 w-4 h-4 shadow-md bg-red-600"></div>

                <button onclick="closeViewModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>

                <div class="mt-4 flex-grow flex flex-col justify-center items-center text-center">
                    <!-- Status Header -->
                    <div id="view-private-badge" class="hidden mb-2 px-2 py-1 bg-gray-800/10 rounded-full text-xs font-bold text-gray-600 flex items-center gap-1">
                        <i class="fas fa-lock"></i> Catatan Privat
                    </div>

                    <p class="font-['Indie_Flower'] text-3xl text-gray-800 leading-relaxed mb-6" id="view-message">
                        "Loading message..."
                    </p>
                    <div class="w-16 h-1 bg-gray-300 rounded mb-4"></div>
                    <p class="font-['Patrick_Hand'] text-xl text-gray-600" id="view-author">
                        - Author
                    </p>
                </div>

                <!-- Footer Buttons -->
                <div class="mt-6 flex justify-between items-center border-t border-gray-400/20 pt-4">

                     <button onclick="closeViewModal()" class="text-sm text-gray-500 font-bold hover:text-gray-800">Tutup</button>
                     <button onclick="deleteCurrentNote()" class="text-sm text-red-500 font-bold hover:text-gray-800">Delete</button>
                     <button id="btn-delete-note" onclick="deleteCurrentNote()" class="hidden text-sm text-red-500 font-bold hover:text-red-700 bg-red-100 px-3 py-1 rounded hover:bg-red-200 transition-colors">
                        <i class="fas fa-trash-alt mr-1"></i> Copot Catatan
                     </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- USER IDENTITY ---
        function getUserId() {
            let id = localStorage.getItem('mading_uid');
            if (!id) {
                id = 'user_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                localStorage.setItem('mading_uid', id);
            }
            return id;
        }
        
        const currentUserId = getUserId();
        const ADMIN_ID = 'admin'; 
        // Simulasi Admin via URL parameter ?admin=true
        const isAdminSession = new URLSearchParams(window.location.search).get('admin') === 'true';

        // --- DATA & STATE ---
        const initialNotes = [
            { id: 1, name: "Ketua Osis", message: "Jangan lupa rapat besok jam 3 sore ya guys!", color: "bg-yellow-200", x: 20, y: 30, rotation: -2, visibility: 'public', ownerId: 'osis' },
            { id: 2, name: "Anonim", message: "Semangat ujiannya kakak-kakak kelas 12!", color: "bg-pink-200", x: 60, y: 20, rotation: 3, visibility: 'public', ownerId: 'student1' },
            { id: 3, name: "Eskul Musik", message: "Dicari: Vokalis baru. Hubungi kami di ruang musik.", color: "bg-green-200", x: 40, y: 60, rotation: -1, visibility: 'public', ownerId: 'musik' }
        ];

        let notes = @json($mading->messages);
        console.log(notes)
        let isPlacementMode = false;
        let tempCoords = null;
        let currentViewNoteId = null; // Menyimpan ID note yang sedang dibuka

        // --- RENDER LOGIC ---
        function renderAllNotes() {
            const container = document.getElementById('notes-container');
            container.innerHTML = ''; // Reset container
            notes.forEach(renderNote);
            updateStats();
        }

        function renderNote(data) {
            // --- PRIVACY FILTER ---
            // Jika pesan privat DAN bukan milik user saat ini, jangan ditampilkan
            if (data.visibility === 'private' && data.ownerId !== currentUserId) {
                return;
            }

            const container = document.getElementById('notes-container');
            const noteEl = document.createElement('div');
            
            // Randomize rotation if new or use saved
            const rotation = data.rotation !== undefined ? data.rotation : (Math.random() * 10 - 5); 
            
            // Set CSS variable untuk rotasi agar bisa dibaca oleh keyframe animation
            noteEl.style.setProperty('--rot', rotation + 'deg');

            // Note Styling
            // Tambahkan class 'animate-slap' agar animasi berjalan saat elemen dibuat
            noteEl.className = `sticky-note animate-slap absolute w-32 md:w-40 aspect-square p-3 shadow-md flex flex-col justify-center items-center text-center cursor-pointer ${data.color}`;
            
            // Positioning Logic:
            noteEl.style.left = data.x + '%';
            noteEl.style.top = data.y + '%';
            noteEl.style.transform = `translate(-50%, 0) rotate(${rotation}deg)`;
            
            // Icon Gembok (jika privat)
            let lockHtml = '';
            if (data.visibility === 'private') {
                lockHtml = '<div class="lock-badge"><i class="fas fa-lock"></i></div>';
            }

            // Inner HTML (Pin + Text Preview + Lock)
            noteEl.innerHTML = `
                <div class="pin"></div>
                ${lockHtml}
                <div class="w-full h-full overflow-hidden pointer-events-none select-none flex flex-col justify-center">
                    <p class="text-gray-800 text-sm md:text-base leading-tight line-clamp-4 overflow-hidden" style="font-family: 'Indie Flower', cursive;">
                        ${data.message}
                    </p>
                    <p class="mt-2 text-xs font-bold text-gray-500 truncate">- ${data.name}</p>
                </div>
            `;

            // Hapus class animasi setelah selesai agar transisi hover berfungsi normal
            noteEl.addEventListener('animationend', () => {
                noteEl.classList.remove('animate-slap');
            });

            // Interaction Effects
            noteEl.onmouseenter = () => {
                noteEl.style.transform = `translate(-50%, 0) rotate(${rotation}deg) scale(1.15)`;
            };
            noteEl.onmouseleave = () => {
                noteEl.style.transform = `translate(-50%, 0) rotate(${rotation}deg) scale(1)`;
            };

            // Pass full data object to modal
            noteEl.onclick = (e) => {
                e.stopPropagation();
                openViewModal(data);
            };

            container.appendChild(noteEl);
        }

        function updateStats() {
            // Hitung hanya yang visible untuk user ini
            const visibleCount = notes.filter(n => n.visibility === 'public' || n.ownerId === currentUserId).length;
            // document.getElementById('total-notes').innerText = visibleCount;
        }

        // --- PLACEMENT LOGIC ---
        function startPlacementMode() {
            isPlacementMode = true;
            document.getElementById('mading-board').classList.add('cursor-pin');
            document.getElementById('placement-banner').classList.remove('hidden');
            document.getElementById('placement-banner').classList.add('flex');
            document.getElementById('controls').classList.add('translate-y-20', 'opacity-0');
            document.getElementById('header-area').classList.add('opacity-50');
        }

        function stopPlacementMode() {
            isPlacementMode = false;
            document.getElementById('mading-board').classList.remove('cursor-pin');
            document.getElementById('placement-banner').classList.add('hidden');
            document.getElementById('placement-banner').classList.remove('flex');
            document.getElementById('controls').classList.remove('translate-y-20', 'opacity-0');
            document.getElementById('header-area').classList.remove('opacity-50');
        }

        function cancelPlacement() {
            stopPlacementMode();
            tempCoords = null; 
        }

        function handleBoardClick(e) {
            if (!isPlacementMode) return;

            const board = document.getElementById('mading-board');
            const rect = board.getBoundingClientRect();
            
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            let xPercent = (x / rect.width) * 100;
            let yPercent = (y / rect.height) * 100;

            tempCoords = { x: xPercent, y: yPercent };

            stopPlacementMode(); 
            openFormModal();
        }

        // --- FORM LOGIC ---
        function openFormModal() {
            document.getElementById('form-modal').classList.remove('hidden');
            setTimeout(() => document.getElementById('inputName').focus(), 100);
        }

        function closeFormModal() {
            document.getElementById('form-modal').classList.add('hidden');
            document.getElementById('noteForm').reset();
            tempCoords = null; 
        }

        function submitNote() {
            const name = document.getElementById('inputName').value;
            const message = document.getElementById('inputMessage').value;
            const color = document.querySelector('input[name="noteColor"]:checked').value;
            const visibility = document.querySelector('input[name="visibility"]:checked').value;

            if (!message) return;

            const coords = tempCoords || { x: 50, y: 50 };

 const newMessage = {
                id: Date.now(), // Unique ID based on timestamp
                tree_id: {{ $id }}, 
                user_id: {{ auth()->user()->id }},
                name: name || "Anonim",
                message: message,
                color: color,
                x: coords.x,
                y: coords.y,
                visibility: visibility,
                ownerId: currentUserId // Simpan ID pemilik saat ini
            };

            axios.post('/api/pohon/store', newMessage).then((response)=>{
                console.log(response)
            })

            notes.push(newMessage);
            renderAllNotes(); // Render ulang semua untuk memastikan urutan
            
            document.getElementById('form-modal').classList.add('hidden');
            document.getElementById('noteForm').reset();
            // Reset visibility ke public secara default
            document.querySelector('input[name="visibility"][value="public"]').checked = true;
            tempCoords = null;
            window.open(window.location.href, '_self')

        }

        // --- VIEW & DELETE LOGIC ---
        function openViewModal(data) {
            if (isPlacementMode) return;
            
            currentViewNoteId = data.id; // Simpan ID untuk fungsi hapus
            
            document.getElementById('view-author').innerText = "- " + data.name;
            document.getElementById('view-message').innerText = `"${data.message}"`;
            
            // Atur warna kartu di modal
            const cardContent = document.getElementById('view-card-content').querySelector('div');
            // Hapus class warna lama
            cardContent.classList.remove('bg-white', 'bg-yellow-100', 'bg-pink-100', 'bg-green-100', 'bg-blue-100');
            // Tambah class warna baru (versi lebih terang '100')
            const baseColor = data.color.replace('200', '100');
            cardContent.classList.add(baseColor);

            // Tampilkan badge Privat jika perlu
            const privateBadge = document.getElementById('view-private-badge');
            if (data.visibility === 'private') {
                privateBadge.classList.remove('hidden');
            } else {
                privateBadge.classList.add('hidden');
            }

            // Cek Izin Hapus (Owner atau Admin)
            const deleteBtn = document.getElementById('btn-delete-note');
            if (data.ownerId === currentUserId || isAdminSession) {
                deleteBtn.classList.remove('hidden');
            } else {
                deleteBtn.classList.add('hidden');
            }

            document.getElementById('view-modal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('view-modal').classList.add('hidden');
            currentViewNoteId = null;
        }

        function deleteCurrentNote() {
            if (!currentViewNoteId) return;

            if (confirm("Yakin ingin mencopot catatan ini dari mading?")) {
                // Hapus dari array
                notes = notes.filter((n) => {
                    n.id !== currentViewNoteId
                
            axios.post('/api/pohon/delete', {tree_id: currentViewNoteId}).then((response)=>{
                console.log(response)
                window.open(window.location.href, '_self')
            })
                });
                // Render ulang papan


                renderAllNotes();
                closeViewModal();
            }
        }

        // --- INIT ---
        document.addEventListener('DOMContentLoaded', () => {
            renderAllNotes();
        });

    </script>
</body>
</html>