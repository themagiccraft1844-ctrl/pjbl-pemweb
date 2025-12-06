<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishnotes - Profil & Pengaturan</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .profile-card {
            background-color: #ffffff;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: none;
            width: 100%;
            max-width: 500px;
            overflow: hidden;
            position: relative;
        }

        .btn-back-dashboard {
            position: absolute; top: 20px; left: 20px; color: white; font-size: 1.2rem; z-index: 10;
            cursor: pointer; background: rgba(255,255,255,0.25); width: 40px; height: 40px;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            transition: all 0.2s; text-decoration: none; backdrop-filter: blur(5px);
        }
        .btn-back-dashboard:hover { background: rgba(255,255,255,0.4); color: white; transform: scale(1.05); }

        .profile-header {
            background: linear-gradient(to right, #a18cd1, #fbc2eb);
            padding: 40px 20px 30px; text-align: center; color: white; position: relative;
        }
        .profile-header::after {
            content: ''; position: absolute; bottom: -1px; left: 0; width: 100%; height: 40px;
            background: #fff; border-radius: 50% 50% 0 0 / 100% 100% 0 0; transform: scaleX(1.5);
        }

        .avatar-container { position: relative; width: 110px; height: 110px; margin: 0 auto 10px; }
        .avatar-img {
            width: 100%; height: 100%; border-radius: 50%; border: 4px solid rgba(255, 255, 255, 0.8);
            object-fit: cover; box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .btn-camera {
            position: absolute; bottom: 5px; right: 5px; background: #fff; color: #a18cd1; border: none;
            width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center;
            justify-content: center; box-shadow: 0 3px 10px rgba(0,0,0,0.1); cursor: pointer; transition: transform 0.2s;
        }
        .btn-camera:hover { transform: scale(1.1); color: #8a6dc5; }
        /* Style khusus tombol kamera disabled */
        .btn-camera.disabled {
            background: #e9ecef; color: #adb5bd; cursor: not-allowed; box-shadow: none;
        }
        .btn-camera.disabled:hover { transform: none; }

        .user-name { font-weight: 700; margin-bottom: 2px; font-size: 1.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .user-email { font-size: 0.9rem; opacity: 0.9; margin-bottom: 8px; font-weight: 500; }
        .badge-member {
            background: #ffffff; color: #a18cd1; padding: 4px 14px; border-radius: 20px; font-size: 0.75rem;
            font-weight: 800; box-shadow: 0 3px 6px rgba(0,0,0,0.15); display: inline-block; letter-spacing: 0.5px; text-transform: uppercase;
        }

        .profile-nav { display: flex; justify-content: center; margin-top: 10px; margin-bottom: 20px; position: relative; z-index: 2; }
        .nav-btn { background: none; border: none; padding: 10px 20px; color: #aaa; font-weight: 600; position: relative; transition: color 0.3s; font-size: 0.95rem; }
        .nav-btn.active { color: #a18cd1; }
        .nav-btn.active::after {
            content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
            width: 20px; height: 3px; background-color: #a18cd1; border-radius: 3px;
        }

        .content-area { padding: 0 30px 40px; min-height: 300px; }
        .form-label { font-size: 0.85rem; color: #888; font-weight: 600; margin-bottom: 5px; }
        .form-control { border-radius: 15px; border: 2px solid #f0f0f0; padding: 10px 15px; font-size: 0.95rem; transition: all 0.3s; }
        .form-control:focus { border-color: #a18cd1; box-shadow: 0 0 0 4px rgba(161, 140, 209, 0.1); background-color: #fff; }
        
        /* Disabled Input Style */
        .form-control:disabled { background-color: #f8f9fa; color: #6c757d; cursor: not-allowed; }

        .settings-list { list-style: none; padding: 0; }
        .settings-item { display: flex; align-items: center; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #f5f5f5; }
        .settings-item:last-child { border-bottom: none; }
        .settings-item.clickable { cursor: pointer; transition: background 0.2s; padding-left: 5px; padding-right: 5px; border-radius: 10px; }
        .settings-item.clickable:hover { background-color: #fcfaff; }
        /* Disabled Setting Item */
        .settings-item.disabled { opacity: 0.6; cursor: not-allowed; }
        .settings-item.disabled:hover { background-color: transparent; }

        .settings-info { display: flex; align-items: center; gap: 15px; }
        .settings-icon { width: 40px; height: 40px; background: #f8f5ff; color: #a18cd1; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
        .settings-text h6 { margin: 0; font-weight: 700; color: #555; font-size: 0.95rem; }
        .settings-text p { margin: 0; font-size: 0.75rem; color: #999; }

        .btn-save {
            background: linear-gradient(to right, #a18cd1, #fbc2eb); border: none; border-radius: 50px;
            padding: 12px; font-weight: 700; color: white; width: 100%; margin-top: 15px;
            box-shadow: 0 5px 15px rgba(161, 140, 209, 0.3); transition: transform 0.2s;
        }
        .btn-save:hover { transform: translateY(-2px); color: white; }
        /* Disabled Save Button */
        .btn-save:disabled { background: #adb5bd; cursor: not-allowed; transform: none; box-shadow: none; }
        
        .btn-logout {
            color: #ff6b6b; background: #fff5f5; border: none; border-radius: 50px; padding: 10px; width: 100%;
            font-weight: 700; font-size: 0.9rem; transition: background 0.2s; text-decoration: none; display: block; text-align: center;
        }
        .btn-logout:hover { background: #ffe3e3; color: #e03131; }

        .fade-in { animation: fadeIn 0.4s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .d-none { display: none !important; }
        
        /* Alert Styles */
        .alert { border-radius: 15px; font-size: 0.9rem; margin-bottom: 20px; border: none; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-danger { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

    <div class="profile-card">
        
        <a href="/dashboard" class="btn-back-dashboard" title="Kembali ke Dashboard">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div class="profile-header">
            <div class="avatar-container">
                @php
                    $user = auth()->user();
                    $isGuest = !$user; // Cek apakah guest
                    
                    if ($user && $user->avatar) {
                        $avatarUrl = asset('storage/' . $user->avatar);
                    } else {
                        $nameForAvatar = $user ? $user->name : 'Tamu';
                        $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($nameForAvatar) . "&background=fff&color=a18cd1&size=128";
                    }
                @endphp
                <img src="{{ $avatarUrl }}" alt="Profile" class="avatar-img">
                
                @auth
                    <!-- Fitur Ganti Foto (Hanya User Login) -->
                    <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                        @csrf
                        <input type="file" name="avatar" id="avatarInput" accept="image/*" class="d-none" onchange="document.getElementById('avatarForm').submit()">
                    </form>
                    <button class="btn-camera" title="Ganti Foto" onclick="document.getElementById('avatarInput').click()">
                        <i class="fas fa-camera"></i>
                    </button>
                @else
                    <!-- Fitur Ganti Foto (Guest - Disabled) -->
                    <button class="btn-camera disabled" title="Login untuk ganti foto" onclick="alert('Silakan login terlebih dahulu untuk mengganti foto profil!')">
                        <i class="fas fa-lock"></i>
                    </button>
                @endauth
            </div>
            
            <h2 class="user-name">{{ $user->name ?? 'Tamu (Guest)' }}</h2>
            <p class="user-email">{{ $user->email ?? 'Belum login' }}</p>
            <span class="badge-member">
                <i class="fas {{ $isGuest ? 'fa-user-secret' : 'fa-star' }} me-1"></i> 
                {{ $isGuest ? 'Mode Tamu' : 'Wishnotes ' . ($user->role ?? 'Member') }}
            </span>
        </div>

        <div class="px-4 mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        @php
            $activeTab = 'edit-profile'; 
            if($errors->hasBag('password_update')) {
                $activeTab = 'change-password';
            }
        @endphp

        <div class="profile-nav" id="mainNav" class="@if($activeTab == 'change-password') d-none @endif">
            <button class="nav-btn @if($activeTab == 'edit-profile') active @endif" onclick="switchTab('edit-profile')">Edit Profil</button>
            <button class="nav-btn @if($activeTab == 'settings') active @endif" onclick="switchTab('settings')">Pengaturan</button>
        </div>

        <div class="content-area">
            
            <!-- EDIT PROFILE TAB -->
            <div id="edit-profile" class="tab-content fade-in @if($activeTab != 'edit-profile') d-none @endif">
                
                @if($isGuest)
                    <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning text-center">
                        <small><i class="fas fa-lock me-1"></i> Fitur edit profil dikunci untuk tamu.</small>
                    </div>
                @endif

                <form action="{{ $isGuest ? '#' : route('profile.update') }}" method="POST" onsubmit="{{ $isGuest ? 'return false;' : '' }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" 
                               value="{{ old('name', $user->name ?? 'Tamu') }}" 
                               {{ $isGuest ? 'disabled' : 'required' }}>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted" style="border-radius: 15px 0 0 15px;">@</span>
                            <input type="email" class="form-control" name="email" 
                                   value="{{ old('email', $user->email ?? 'guest@wishnotes.com') }}" 
                                   style="border-radius: 0 15px 15px 0;" 
                                   {{ $isGuest ? 'disabled' : 'required' }}>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bio Singkat</label>
                        <textarea class="form-control" name="bio" rows="2" {{ $isGuest ? 'disabled' : '' }}>{{ old('bio', $user->bio ?? 'Saya sedang berkunjung sebagai tamu 👀') }}</textarea>
                    </div>
                    
                    @if($isGuest)
                        <button type="button" class="btn btn-save" disabled>
                            Simpan Perubahan (Login Dulu)
                        </button>
                    @else
                        <button type="submit" class="btn btn-save">
                            Simpan Perubahan
                        </button>
                    @endif
                </form>
            </div>

            <!-- SETTINGS TAB -->
            <div id="settings" class="tab-content fade-in @if($activeTab != 'settings') d-none @endif">
                <ul class="settings-list">
                    <li class="settings-item">
                        <div class="settings-info">
                            <div class="settings-icon"><i class="far fa-bell"></i></div>
                            <div class="settings-text">
                                <h6>Notifikasi</h6>
                                <p>Terima update harapan baru</p>
                            </div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked disabled title="Fitur ini akan segera hadir">
                        </div>
                    </li>
                    
                    @if($isGuest)
                        <!-- Ganti Password Disabled untuk Guest -->
                        <li class="settings-item disabled" onclick="alert('Tamu tidak memiliki password untuk diganti. Silakan daftar akun!')">
                            <div class="settings-info">
                                <div class="settings-icon bg-light text-muted"><i class="fas fa-lock"></i></div>
                                <div class="settings-text">
                                    <h6 class="text-muted">Ganti Password</h6>
                                    <p>Hanya untuk member terdaftar</p>
                                </div>
                            </div>
                            <i class="fas fa-ban text-muted small"></i>
                        </li>
                    @else
                        <!-- Ganti Password Enabled -->
                        <li class="settings-item clickable" onclick="openPasswordTab()">
                            <div class="settings-info">
                                <div class="settings-icon"><i class="fas fa-key"></i></div>
                                <div class="settings-text">
                                    <h6>Ganti Password</h6>
                                    <p>Amankan akunmu secara berkala</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </li>
                    @endif
                </ul>

                <div class="mt-4 pt-3 border-top">
                    @auth
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-logout" onclick="return confirm('Yakin ingin keluar?')">
                                <i class="fas fa-sign-out-alt me-2"></i> Keluar dari Akun
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-save text-center text-decoration-none d-block">
                            <i class="fas fa-sign-in-alt me-2"></i> Login / Daftar Sekarang
                        </a>
                    @endauth
                </div>
            </div>

            <!-- CHANGE PASSWORD TAB -->
            <div id="change-password" class="tab-content fade-in @if($activeTab != 'change-password') d-none @endif">
                <div class="text-center mb-4">
                    <h5 class="fw-bold" style="color: #666;">Ganti Password</h5>
                    <p class="small text-muted">Pastikan password baru kamu aman!</p>
                </div>
                
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="current_password" id="current_password" style="border-right: none; border-radius: 15px 0 0 15px;" required>
                            <span class="input-group-text bg-white border-start-0 toggle-password" onclick="togglePass('current_password', this)" style="cursor: pointer; border-radius: 0 15px 15px 0; border: 2px solid #f0f0f0; border-left: none;">
                                <i class="far fa-eye-slash text-muted small"></i>
                            </span>
                        </div>
                        @error('current_password', 'password_update')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="new_password" id="new_password" style="border-right: none; border-radius: 15px 0 0 15px;" required>
                            <span class="input-group-text bg-white border-start-0 toggle-password" onclick="togglePass('new_password', this)" style="cursor: pointer; border-radius: 0 15px 15px 0; border: 2px solid #f0f0f0; border-left: none;">
                                <i class="far fa-eye-slash text-muted small"></i>
                            </span>
                        </div>
                         @error('new_password', 'password_update')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" style="border-right: none; border-radius: 15px 0 0 15px;" required>
                            <span class="input-group-text bg-white border-start-0 toggle-password" onclick="togglePass('new_password_confirmation', this)" style="cursor: pointer; border-radius: 0 15px 15px 0; border: 2px solid #f0f0f0; border-left: none;">
                                <i class="far fa-eye-slash text-muted small"></i>
                            </span>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-save">
                        Update Password
                    </button>
                    <button type="button" class="btn btn-link w-100 text-decoration-none mt-2 text-muted small" onclick="closePasswordTab()">
                        Batal
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Simple Tab Switcher
        function switchTab(targetId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.nav-btn').forEach(el => el.classList.remove('active'));
            document.getElementById(targetId).classList.remove('d-none');
            
            const btns = document.querySelectorAll('.nav-btn');
            btns.forEach(btn => {
                if(btn.getAttribute('onclick').includes(targetId)) {
                    btn.classList.add('active');
                }
            });

            if(targetId !== 'change-password') {
                document.getElementById('mainNav').classList.remove('d-none');
            }
        }

        function openPasswordTab() {
            document.getElementById('settings').classList.add('d-none');
            document.getElementById('mainNav').classList.add('d-none');
            document.getElementById('change-password').classList.remove('d-none');
        }

        function closePasswordTab() {
            document.getElementById('change-password').classList.add('d-none');
            document.getElementById('settings').classList.remove('d-none');
            document.getElementById('mainNav').classList.remove('d-none');
            document.querySelectorAll('.nav-btn').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-btn')[1].classList.add('active'); 
        }

        function togglePass(inputId, iconEl) {
            const input = document.getElementById(inputId);
            const icon = iconEl.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>