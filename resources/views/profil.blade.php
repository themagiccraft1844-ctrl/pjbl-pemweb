<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishnotes - Profil & Pengaturan</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Nunito -->
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

        /* Container Kartu Utama */
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

        /* Tombol Kembali ke Dashboard */
        .btn-back-dashboard {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 1.2rem;
            z-index: 10;
            cursor: pointer;
            background: rgba(255,255,255,0.25);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            text-decoration: none;
            backdrop-filter: blur(5px);
        }
        .btn-back-dashboard:hover {
            background: rgba(255,255,255,0.4);
            color: white;
            transform: scale(1.05);
        }

        /* Header Profil dengan Gradasi */
        .profile-header {
            background: linear-gradient(to right, #a18cd1, #fbc2eb);
            padding: 40px 20px 30px;
            text-align: center;
            color: white;
            position: relative;
        }

        /* Dekorasi Gelombang di bawah header */
        .profile-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 40px;
            background: #fff;
            border-radius: 50% 50% 0 0 / 100% 100% 0 0;
            transform: scaleX(1.5);
        }

        .avatar-container {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 0 auto 10px; /* Reduced margin */
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.8);
            object-fit: cover;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .btn-camera {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #fff;
            color: #a18cd1;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn-camera:hover {
            transform: scale(1.1);
            color: #8a6dc5;
        }

        .user-name { font-weight: 700; margin-bottom: 2px; font-size: 1.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .user-email { font-size: 0.9rem; opacity: 0.9; margin-bottom: 8px; font-weight: 500; }
        
        .badge-member {
            background: #ffffff;
            color: #a18cd1;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 800;
            box-shadow: 0 3px 6px rgba(0,0,0,0.15);
            display: inline-block;
            margin-top: 0; /* Removed margin-top to pull it up */
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Navigasi Tab */
        .profile-nav {
            display: flex;
            justify-content: center;
            margin-top: 10px;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .nav-btn {
            background: none;
            border: none;
            padding: 10px 20px;
            color: #aaa;
            font-weight: 600;
            position: relative;
            transition: color 0.3s;
            font-size: 0.95rem;
        }
        
        .nav-btn.active {
            color: #a18cd1;
        }

        .nav-btn.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background-color: #a18cd1;
            border-radius: 3px;
        }

        /* Area Konten */
        .content-area {
            padding: 0 30px 40px;
            min-height: 300px;
        }

        /* Form Styles */
        .form-label {
            font-size: 0.85rem;
            color: #888;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-control {
            border-radius: 15px;
            border: 2px solid #f0f0f0;
            padding: 10px 15px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #a18cd1;
            box-shadow: 0 0 0 4px rgba(161, 140, 209, 0.1);
            background-color: #fff;
        }

        /* Settings List Styles */
        .settings-list {
            list-style: none;
            padding: 0;
        }

        .settings-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .settings-item:last-child { border-bottom: none; }
        
        .settings-item.clickable { cursor: pointer; transition: background 0.2s; padding-left: 5px; padding-right: 5px; border-radius: 10px; }
        .settings-item.clickable:hover { background-color: #fcfaff; }

        .settings-info { display: flex; align-items: center; gap: 15px; }
        .settings-icon {
            width: 40px;
            height: 40px;
            background: #f8f5ff;
            color: #a18cd1;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        .settings-text h6 { margin: 0; font-weight: 700; color: #555; font-size: 0.95rem; }
        .settings-text p { margin: 0; font-size: 0.75rem; color: #999; }

        /* Custom Switch */
        .form-switch .form-check-input {
            width: 2.5em;
            height: 1.25em;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%28161, 140, 209, 0.25%29'/%3e%3c/svg%3e");
        }
        .form-switch .form-check-input:checked {
            background-color: #a18cd1;
            border-color: #a18cd1;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        }

        /* Buttons */
        .btn-save {
            background: linear-gradient(to right, #a18cd1, #fbc2eb);
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-weight: 700;
            color: white;
            width: 100%;
            margin-top: 15px;
            box-shadow: 0 5px 15px rgba(161, 140, 209, 0.3);
            transition: transform 0.2s;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            color: white;
        }

        .btn-logout {
            color: #ff6b6b;
            background: #fff5f5;
            border: none;
            border-radius: 50px;
            padding: 10px;
            width: 100%;
            font-weight: 700;
            font-size: 0.9rem;
            transition: background 0.2s;
        }
        .btn-logout:hover {
            background: #ffe3e3;
            color: #e03131;
        }

        /* Animations */
        .fade-in { animation: fadeIn 0.4s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Utility */
        .d-none { display: none !important; }
        .text-purple { color: #a18cd1; }
    </style>

</head>
<body>

    <div class="profile-card">
        
        <!-- Back to Dashboard Button -->
        <a href="dashboard" class="btn-back-dashboard" title="Kembali ke Dashboard">
            <i class="fas fa-arrow-left"></i>
        </a>

        <!-- Header -->
        <div class="profile-header">
            <div class="avatar-container">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Guest' }}&background=fff&color=a18cd1&size=128" alt="Profile" class="avatar-img" id="avatarPreview">
                <button class="btn-camera" title="Ganti Foto">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            <h2 class="user-name">{{ auth()->user()->name ?? 'Guest' }}</h2>
            <p class="user-email">{{ auth()->user()->email ?? 'Guest' }}</p>
            <span class="badge-member"><i class="fas fa-star me-1"></i> Wishnotes Member</span>
        </div>

        <!-- Navigation Tabs (Only visible when not changing password) -->
        <div class="profile-nav" id="mainNav">
            <button class="nav-btn active" data-target="edit-profile">Edit Profil</button>
            <button class="nav-btn" data-target="settings">Pengaturan</button>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            
            <!-- Tab: Edit Profile -->
            <div id="edit-profile" class="tab-content fade-in">
                <form id="profileForm">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name ?? 'Guest' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted" style="border-radius: 15px 0 0 15px;">@</span>
                            <input type="text" class="form-control" value="{{ auth()->user()->email ?? 'Guest' }}" style="border-radius: 0 15px 15px 0;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bio Singkat</label>
                        <textarea class="form-control" rows="2">Suka berbagi harapan dan mimpi-mimpi kecil ✨</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-save">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Tab: Settings -->
            <div id="settings" class="tab-content fade-in d-none">
                <ul class="settings-list">
                    <!-- Notifications -->
                    <li class="settings-item">
                        <div class="settings-info">
                            <div class="settings-icon"><i class="far fa-bell"></i></div>
                            <div class="settings-text">
                                <h6>Notifikasi</h6>
                                <p>Terima update harapan baru</p>
                            </div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked>
                        </div>
                    </li>
                    
                    <!-- Privacy -->
                    <li class="settings-item">
                        <div class="settings-info">
                            <div class="settings-icon"><i class="fas fa-lock-open"></i></div>
                            <div class="settings-text">
                                <h6>Profil Publik</h6>
                                <p>Orang lain bisa melihat profilmu</p>
                            </div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked>
                        </div>
                    </li>

                    <!-- Change Password (Clickable) -->
                    <li class="settings-item clickable" id="btnToChangePassword">
                        <div class="settings-info">
                            <div class="settings-icon"><i class="fas fa-key"></i></div>
                            <div class="settings-text">
                                <h6>Ganti Password</h6>
                                <p>Amankan akunmu secara berkala</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted small"></i>
                    </li>
                </ul>

                <div class="mt-4 pt-3 border-top">
                    <button class="btn btn-logout">
                        <i class="fas fa-sign-out-alt me-2"></i> Keluar dari Akun
                    </button>
                </div>
            </div>

            <!-- Tab: Change Password (Hidden by default) -->
            <div id="change-password" class="tab-content fade-in d-none">
                <div class="text-center mb-4">
                    <h5 class="fw-bold" style="color: #666;">Ganti Password</h5>
                    <p class="small text-muted">Pastikan password baru kamu aman!</p>
                </div>
                
                <form id="passwordForm">
                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <div class="input-group">
                            <input type="password" class="form-control" style="border-right: none; border-radius: 15px 0 0 15px;" required>
                            <span class="input-group-text bg-white border-start-0" style="border-radius: 0 15px 15px 0; border: 2px solid #f0f0f0; border-left: none;">
                                <i class="far fa-eye-slash text-muted small"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-save">
                        Update Password
                    </button>
                    <button type="button" class="btn btn-link w-100 text-decoration-none mt-2 text-muted small" id="btnCancelPassword">
                        Batal
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Tab Switching Logic (Profile vs Settings)
            $('.nav-btn').click(function() {
                $('.nav-btn').removeClass('active');
                $(this).addClass('active');

                // Hide Password View if open
                $('#change-password').addClass('d-none');
                $('#mainNav').removeClass('d-none');

                // Show Content
                $('.tab-content').addClass('d-none').removeClass('fade-in');
                let target = $(this).data('target');
                $('#' + target).removeClass('d-none').addClass('fade-in');
            });

            // Go to Change Password View
            $('#btnToChangePassword').click(function() {
                $('#settings').addClass('d-none');
                $('#mainNav').addClass('d-none'); // Hide tabs to focus on password
                $('#change-password').removeClass('d-none').addClass('fade-in');
            });

            // Cancel Change Password
            $('#btnCancelPassword').click(function() {
                $('#change-password').addClass('d-none');
                $('#mainNav').removeClass('d-none'); // Show tabs again
                $('#settings').removeClass('d-none').addClass('fade-in');
                $('.nav-btn[data-target="settings"]').addClass('active');
            });

            // Save Profile Logic
            $('#profileForm').submit(function(e) {
                e.preventDefault();
                let btn = $(this).find('button[type="submit"]');
                let originalText = btn.html();

                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                setTimeout(() => {
                    btn.prop('disabled', false).html('<i class="fas fa-check me-2"></i> Tersimpan!');
                    setTimeout(() => {
                        btn.html(originalText);
                    }, 2000);
                }, 1000);
            });

            // Update Password Logic
            $('#passwordForm').submit(function(e) {
                e.preventDefault();
                let btn = $(this).find('button[type="submit"]');
                let originalText = btn.html();

                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

                setTimeout(() => {
                    btn.prop('disabled', false).html('<i class="fas fa-check me-2"></i> Password Diganti!');
                    setTimeout(() => {
                        // Reset and go back
                        $('#passwordForm')[0].reset();
                        btn.html(originalText);
                        $('#btnCancelPassword').click();
                    }, 1500);
                }, 1500);
            });

            // Logout Confirmation
            $('.btn-logout').click(function() {
                if(confirm('Apakah Anda yakin ingin keluar?')) {
                    alert('Anda telah keluar.');
                    window.location.href = 'login'; // Assuming this links back to login/main
                }
            });

            // Camera Button Effect
            $('.btn-camera').click(function() {
                alert('Membuka galeri untuk memilih foto...');
            });
        });
    </script>
</body>
</html>