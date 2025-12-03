<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishnotes - Mailbox</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Courier+Prime&display=swap" rel="stylesheet">

    <style>
        /* Animasi Amplop Terbang */
        @keyframes flyIn {
            0% { 
                transform: translate(-50%, 100vh) scale(2) rotate(10deg); 
                opacity: 1; 
            }
            40% {
                transform: translate(-50%, -20%) scale(1.5) rotate(-5deg);
            }
            70% {
                transform: translate(-50%, 10%) scale(0.8) rotate(5deg);
                opacity: 1;
            }
            100% { 
                transform: translate(-50%, 20%) scale(0.1) rotate(0deg); 
                opacity: 0; 
            }
        }
        
        .anim-flying-letter {
            animation: flyIn 1.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        /* Animasi Modal Pop */
        @keyframes popUp {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .modal-anim {
            animation: popUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        /* Mailbox Hover Effect */
        .mailbox-hover {
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .mailbox-hover:hover {
            transform: scale(1.02);
        }

        /* Flag Animation Class (Toggle via JS) */
        .flag-raised {
            transform: rotate(-90deg) !important; /* Adjust based on SVG pivot */
        }
        
        /* Paper Texture for Letters */
        .paper-texture {
            background-color: #fdfbf7;
            background-image: linear-gradient(#e1e1e1 1px, transparent 1px);
            background-size: 100% 1.5rem;
            line-height: 1.5rem;
        }

        /* Custom Scrollbar */
        .custom-scroll::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
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
<body class="bg-[#a18cd1] h-screen w-full overflow-hidden font-sans flex flex-col items-center justify-end">

    <!-- Back Button -->
    <a href="/dashboard" class="absolute top-4 left-4 z-40 bg-white text-purple-600 w-10 h-10 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
        <i class="fas fa-arrow-left"></i>
    </a>

    <!-- Header -->
    <div class="absolute top-8 z-10 text-center pointer-events-none">
        <h1 class="text-3xl md:text-5xl font-bold text-white drop-shadow-md mb-2">{{ $mailbox->judul }}</h1>
        <p class="text-purple-100 text-sm opacity-90">{{ $mailbox->deskripsi_singkat }}</p>
    </div>

    <!-- MAIN SCENE: MAILBOX SVG -->
    <div class="relative w-full h-[85vh] flex items-end justify-center pb-0">
        
        <!-- Flying Letter Container (Absolute Center of Mailbox) -->
        <div id="fly-target" class="absolute top-[40%] left-1/2 -translate-x-1/2 w-10 h-10 z-30 pointer-events-none"></div>

        <!-- Flying Letter Element (Hidden by default, used for animation) -->
        <div id="flying-letter" class="fixed left-1/2 top-1/2 w-48 h-32 bg-white border-4 border-gray-200 shadow-xl z-50 rounded hidden flex items-center justify-center">
            <div class="border-t-4 border-r-4 border-gray-200 w-full h-full transform rotate-45 translate-y-4 scale-75 opacity-20"></div>
            <div class="absolute bg-red-500 w-8 h-8 rounded-full shadow-inner"></div>
        </div>

        <!-- SVG Mailbox -->
        <svg id="mailbox-svg" viewBox="0 0 400 600" class="h-full w-auto max-w-full drop-shadow-2xl mailbox-hover" onclick="openInbox()">
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
            
            <!-- Ground Shadow -->
            <ellipse cx="200" cy="580" rx="120" ry="15" fill="rgba(0,0,0,0.3)"/>

            <!-- Post -->
            <rect x="180" y="300" width="40" height="300" fill="url(#postGrad)"/>

            <!-- Main Box Body -->
            <path d="M100,150 Q200,50 300,150 V300 H100 Z" fill="#f8fafc" stroke="#94a3b8" stroke-width="2"/>
            <rect x="100" y="150" width="200" height="150" fill="url(#boxGrad)" />
            
            <!-- Mail Slot (Dark Area) -->
            <rect x="120" y="160" width="160" height="10" rx="5" fill="#334155" />

            <!-- Door Outline -->
            <rect x="110" y="160" width="180" height="130" rx="5" fill="none" stroke="#94a3b8" stroke-width="2" stroke-dasharray="10,5"/>

            <!-- Flag Group -->
            <!-- Transform Origin sekitar (300, 250) -->
            <g id="mailbox-flag" class="transition-transform duration-700 ease-in-out" style="transform-origin: 300px 250px; transform: rotate(0deg);">
                <rect x="300" y="240" width="10" height="80" fill="#dc2626" />
                <circle cx="305" cy="250" r="8" fill="#991b1b" /> <!-- Pivot -->
                <rect x="305" y="240" width="60" height="20" fill="#dc2626" rx="2"/>
            </g>

            <!-- Text on Box -->
            <text x="200" y="240" font-family="Courier Prime" font-weight="bold" font-size="24" fill="#475569" text-anchor="middle" letter-spacing="2">MAIL</text>
        </svg>

    </div>

    <!-- Controls -->
    <div class="absolute bottom-10 z-40 flex flex-col items-center gap-4 w-full">
        <!-- Notification Bubble -->
        <div class="bg-white/20 backdrop-blur px-4 py-2 rounded-full border border-white/40 text-white font-bold text-sm shadow-sm flex items-center gap-2">
            <i class="fas fa-envelope"></i> <span id="mail-count">0</span> Surat Masuk
        </div>

        <button onclick="openComposeModal()" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-400 hover:to-blue-500 text-white font-bold py-3 px-8 rounded-full shadow-lg transform hover:scale-105 transition-all flex items-center gap-2">
            <i class="fas fa-paper-plane"></i> Kirim Surat
        </button>
    </div>

    <!-- Compose Modal -->
    <div id="compose-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div onclick="closeComposeModal()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <div class="modal-anim relative bg-white w-full max-w-md rounded-xl shadow-2xl p-6">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-xl font-bold text-gray-700"><i class="fas fa-pen-nib mr-2"></i>Tulis Surat</h3>
                <button onclick="closeComposeModal()" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
            </div>

            <form id="composeForm" onsubmit="event.preventDefault(); sendMail();">
                <!-- Visibility Toggle -->
                <div class="flex mb-4 bg-gray-100 rounded-lg p-1">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="visibility" value="public" class="hidden peer" checked>
                        <div class="text-center py-2 rounded text-sm font-bold text-gray-500 peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm transition-all">
                            <i class="fas fa-globe mr-1"></i> Publik
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="visibility" value="private" class="hidden peer">
                        <div class="text-center py-2 rounded text-sm font-bold text-gray-500 peer-checked:bg-white peer-checked:text-gray-800 peer-checked:shadow-sm transition-all">
                            <i class="fas fa-lock mr-1"></i> Privat
                        </div>
                    </label>
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-bold text-gray-500 uppercase">Dari</label>
                    <input type="text" id="inputName" class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 outline-none focus:border-blue-500 transition-colors" placeholder="Namamu (bisa Anonim)" >
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase">Pesan</label>
                    <textarea id="inputMessage" rows="4" class="w-full bg-gray-50 border border-gray-300 rounded px-3 py-2 outline-none focus:border-blue-500 transition-colors" placeholder="Apa kabar hari ini?" required></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700 transition-colors shadow-md">
                    Masukkan ke Kotak Surat
                </button>
            </form>
        </div>
    </div>

    <!-- Inbox Modal (List of Letters) -->
    <div id="inbox-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div onclick="closeInboxModal()" class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
        
        <div class="modal-anim relative bg-[#f1f5f9] w-full max-w-2xl h-[80vh] rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <!-- Inbox Header -->
            <div class="bg-white p-4 shadow-sm z-10 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Kotak Masuk</h2>
                    <p class="text-xs text-gray-500">Ketuk surat untuk membaca</p>
                </div>
                <button onclick="closeInboxModal()" class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300 flex items-center justify-center transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Scrollable List -->
            <div id="inbox-list" class="flex-1 overflow-y-auto p-4 grid grid-cols-1 md:grid-cols-2 gap-4 content-start custom-scroll">
                <!-- Items injected here -->
            </div>
            
            <!-- Empty State -->
            <div id="empty-state" class="hidden absolute inset-0 flex flex-col items-center justify-center text-gray-400 pointer-events-none">
                <i class="fas fa-inbox text-6xl mb-4 opacity-50"></i>
                <p>Kotak surat masih kosong.</p>
            </div>
        </div>
    </div>

    <!-- Read Letter Modal (Single View) -->
    <div id="read-modal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 hidden">
        <div onclick="closeReadModal()" class="absolute inset-0 bg-black/40"></div>
        
        <div class="modal-anim relative bg-white w-full max-w-lg shadow-2xl p-0 transform rotate-1">
            <!-- Envelope Top Flap Design -->
            <div class="h-4 w-full bg-red-500 mb-0"></div>
            <div class="h-4 w-full bg-blue-500 mb-0"></div>
            
            <div class="p-8 paper-texture min-h-[400px] flex flex-col">
                <div class="flex justify-between items-start">
                    <!-- Status Badge -->
                    <div id="read-status-badge" class="px-2 py-1 bg-gray-200 text-gray-600 rounded text-xs font-bold hidden">
                        <i class="fas fa-lock mr-1"></i> Privat
                    </div>
                    
                    <div class="w-16 h-20 border-2 border-gray-300 flex items-center justify-center bg-gray-50 transform rotate-3 ml-auto">
                        <i class="fas fa-stamp text-gray-300 fa-2x"></i>
                    </div>
                </div>
                
                <div class="mt-4 flex-grow">
                    <p class="font-['Courier_Prime'] text-lg text-gray-800 leading-8" id="read-message">
                        ...
                    </p>
                </div>

                <div class="mt-8 pt-4 border-t-2 border-gray-200 border-dashed">
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Dari:</p>
                            <p class="font-bold text-gray-800 text-lg font-['Courier_Prime']" id="read-author">...</p>
                        </div>
                        <div class="flex gap-3">
                            <button id="btn-delete-mail" onclick="deleteCurrentMail()" class="hidden text-red-500 hover:text-red-700 text-sm font-bold flex items-center gap-1">
                                <i class="fas fa-trash-alt"></i> Buang
                            </button>
                            <button onclick="closeReadModal()" class="text-blue-600 hover:underline text-sm font-bold">
                                Lipat Kembali
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- USER IDENTITY ---
        function getUserId() {
            let id = localStorage.getItem('mailbox_uid');
            if (!id) {
                id = 'user_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                localStorage.setItem('mailbox_uid', id);
            }
            return id;
        }
        
        const currentUserId = getUserId();
        // Cek admin (simulasi)
        const isAdmin = new URLSearchParams(window.location.search).get('admin') === 'true';

        // --- DATA ---
        const initialMessages = [
            { id: 1, from: "Secret Admirer", text: "Hai, aku cuma mau bilang kalau kamu hebat banget semester ini. Semangat terus ya!", date: "2 jam lalu", visibility: 'public', ownerId: 'sys' },
            { id: 2, from: "Budi", text: "Bro, jangan lupa balikin buku komik gue ya wkwk.", date: "1 hari lalu", visibility: 'public', ownerId: 'sys' }
        ];
        
        let messages = [...initialMessages];
        let currentReadId = null;

        // --- FLAG LOGIC ---
        function updateFlag() {
            const flag = document.getElementById('mailbox-flag');
            const countEl = document.getElementById('mail-count');
            
            // Hitung pesan yg visible buat user ini
            const visibleMsgs = messages.filter(m => m.visibility === 'public' || m.ownerId === currentUserId);
            countEl.innerText = visibleMsgs.length;

            if (visibleMsgs.length > 0) {
                flag.classList.add('flag-raised');
            } else {
                flag.classList.remove('flag-raised');
            }
        }

        // --- ANIMATION & SEND ---
        function sendMail() {
            const name = document.getElementById('inputName').value || 'Anonim';
            const msg = document.getElementById('inputMessage').value;
            const visibility = document.querySelector('input[name="visibility"]:checked').value;

            if(!msg) return;

            // 1. Close Modal
            closeComposeModal();

            // 2. Play Flying Animation
            const flyer = document.getElementById('flying-letter');
            flyer.classList.remove('hidden');
            flyer.classList.add('anim-flying-letter');

            // 3. Wait for animation to finish
            setTimeout(() => {
                // Hide flyer
                flyer.classList.remove('anim-flying-letter');
                flyer.classList.add('hidden');

                // Add data
                messages.unshift({
                    id: Date.now(),
                    from: name,
                    text: msg,
                    date: "Baru saja",
                    visibility: visibility,
                    ownerId: currentUserId
                });

                // Trigger Flag
                updateFlag();
                
                // Reset form
                document.getElementById('composeForm').reset();
                document.querySelector('input[value="public"]').checked = true;

            }, 1500); 
        }

        // --- MODAL CONTROLS ---
        function openComposeModal() {
            document.getElementById('compose-modal').classList.remove('hidden');
            setTimeout(() => document.getElementById('inputName').focus(), 100);
        }

        function closeComposeModal() {
            document.getElementById('compose-modal').classList.add('hidden');
        }

        function openInbox() {
            renderInbox();
            document.getElementById('inbox-modal').classList.remove('hidden');
        }

        function closeInboxModal() {
            document.getElementById('inbox-modal').classList.add('hidden');
        }

        function openReadModal(id) {
            const msg = messages.find(m => m.id === id);
            if (!msg) return;

            currentReadId = id;

            document.getElementById('read-author').innerText = msg.from;
            document.getElementById('read-message').innerText = msg.text;
            
            // Toggle Delete Button
            const deleteBtn = document.getElementById('btn-delete-mail');
            if (msg.ownerId === currentUserId || isAdmin) {
                deleteBtn.classList.remove('hidden');
            } else {
                deleteBtn.classList.add('hidden');
            }

            // Toggle Privacy Badge
            const badge = document.getElementById('read-status-badge');
            if (msg.visibility === 'private') {
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }

            document.getElementById('read-modal').classList.remove('hidden');
        }

        function closeReadModal() {
            document.getElementById('read-modal').classList.add('hidden');
            currentReadId = null;
        }

        function deleteCurrentMail() {
            if(!currentReadId) return;
            
            if(confirm("Buang surat ini dari kotak surat?")) {
                messages = messages.filter(m => m.id !== currentReadId);
                updateFlag();
                
                // Jika inbox sedang terbuka, re-render
                if(!document.getElementById('inbox-modal').classList.contains('hidden')){
                    renderInbox();
                }
                
                closeReadModal();
            }
        }

        // --- RENDER INBOX ---
        function renderInbox() {
            const list = document.getElementById('inbox-list');
            const empty = document.getElementById('empty-state');
            
            list.innerHTML = '';
            
            // Filter: Public OR My Private Messages
            const visibleMessages = messages.filter(m => {
                if (m.visibility === 'public') return true;
                if (m.visibility === 'private' && (m.ownerId === currentUserId || isAdmin)) return true;
                return false;
            });
            
            if (visibleMessages.length === 0) {
                empty.classList.remove('hidden');
            } else {
                empty.classList.add('hidden');
                visibleMessages.forEach(msg => {
                    const item = document.createElement('div');
                    item.className = "bg-white p-4 rounded-lg shadow-sm hover:shadow-md cursor-pointer border-l-4 transition-all hover:bg-blue-50 relative group";
                    
                    // Style border based on privacy
                    if (msg.visibility === 'private') {
                        item.classList.add('border-gray-500');
                    } else {
                        item.classList.add('border-blue-500');
                    }

                    item.onclick = () => openReadModal(msg.id);
                    
                    let lockIcon = '';
                    if (msg.visibility === 'private') {
                        lockIcon = '<i class="fas fa-lock text-gray-400 text-xs ml-2" title="Privat"></i>';
                    }

                    item.innerHTML = `
                        <div class="flex justify-between items-start mb-1">
                            <span class="font-bold text-gray-700 truncate flex items-center">
                                ${msg.from} ${lockIcon}
                            </span>
                            <span class="text-xs text-gray-400 whitespace-nowrap ml-2">${msg.date}</span>
                        </div>
                        <p class="text-gray-500 text-sm line-clamp-2">${msg.text}</p>
                    `;
                    list.appendChild(item);
                });
            }
        }

        // --- INIT ---
        document.addEventListener('DOMContentLoaded', () => {
            updateFlag();
        });

    </script>
</body>
</html>