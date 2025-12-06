@extends('layouts.app')

@section('title', 'Profil Saya - Wishnotes')

@push('styles')
    @vite(['resources/css/style.css'])
@endpush

@section('content')
<!-- Navbar dihapus karena sudah ada di layout utama -->

<div class="container profile-container pb-5">
    <div class="row g-4">
        <!-- SIDEBAR PROFIL (Kiri di Desktop, Atas di Mobile) -->
        <div class="col-lg-4">
            <div class="profile-card h-100 pb-4 text-center position-relative">
                <div class="profile-header-bg"></div>
                
                <div class="profile-avatar-wrapper">
                    @php
                        $user = auth()->user();
                        $isGuest = !$user;
                        $avatarUrl = ($user && $user->avatar) ? asset('storage/' . $user->avatar) : "https://ui-avatars.com/api/?name=" . urlencode($user ? $user->name : 'Tamu') . "&background=a18cd1&color=fff&size=128";
                    @endphp
                    <img src="{{ $avatarUrl }}" alt="Profile" class="profile-avatar">
                    
                    @auth
                        <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                            @csrf
                            <input type="file" name="avatar" id="avatarInput" accept="image/*" class="d-none" onchange="document.getElementById('avatarForm').submit()">
                        </form>
                        <button class="btn-camera" title="Ganti Foto" onclick="document.getElementById('avatarInput').click()">
                            <i class="fas fa-camera"></i>
                        </button>
                    @endauth
                </div>

                <div class="px-3 mt-3">
                    <h4 class="fw-bold mb-1">{{ $user->name ?? 'Tamu (Guest)' }}</h4>
                    <p class="text-muted small mb-3">{{ $user->email ?? 'Belum login' }}</p>
                    <span class="badge bg-bs-theme text-primary-custom border rounded-pill px-3 py-2 shadow-sm">
                        <i class="fas {{ $isGuest ? 'fa-user-secret' : 'fa-star' }} me-1"></i> 
                        {{ $isGuest ? 'Mode Tamu' : ($user->role === 'admin' ? 'Admin Wishnotes' : 'Member Setia') }}
                    </span>
                </div>

                <!-- Menu Navigasi (Tab) -->
                <div class="d-flex flex-column gap-2 px-4 mt-4 text-start">
                    <button class="btn btn-bs-theme text-start fw-bold py-3 px-3 rounded-4 shadow-sm text-primary-custom active-tab-btn" id="btn-tab-edit" onclick="switchTab('edit-profile')">
                        <i class="fas fa-user-edit me-3"></i> Edit Profil
                    </button>
                    <button class="btn btn-bs-theme text-start fw-bold py-3 px-3 rounded-4 shadow-sm text-muted" id="btn-tab-settings" onclick="switchTab('settings')">
                        <i class="fas fa-cog me-3"></i> Pengaturan
                    </button>
                    
                    @auth
                        <form action="{{ route('logout') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100 rounded-pill py-2" onclick="return confirm('Yakin ingin keluar?')">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>

        <!-- KONTEN UTAMA (Kanan di Desktop, Bawah di Mobile) -->
        <div class="col-lg-8">
            <div class="profile-card p-4 p-md-5 h-100">
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <!-- TAB: EDIT PROFILE -->
                <div id="edit-profile" class="tab-content fade-in">
                    <h5 class="fw-bold mb-4 text-primary-custom"><i class="fas fa-user-edit me-2"></i>Edit Informasi Pribadi</h5>
                    
                    <form action="{{ $isGuest ? '#' : route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $user->name ?? '') }}" {{ $isGuest ? 'disabled' : 'required' }}>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', $user->email ?? '') }}" {{ $isGuest ? 'disabled' : 'required' }}>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Bio Singkat</label>
                                <textarea class="form-control" name="bio" rows="4" placeholder="Ceritakan sedikit tentang dirimu..." {{ $isGuest ? 'disabled' : '' }}>{{ old('bio', $user->bio ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary-custom rounded-pill px-4 py-2 shadow" {{ $isGuest ? 'disabled' : '' }}>
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB: SETTINGS -->
                <div id="settings" class="tab-content fade-in d-none">
                    <h5 class="fw-bold mb-4 text-primary-custom"><i class="fas fa-cogs me-2"></i>Pengaturan Akun</h5>

                    <ul class="settings-list">
                        <li class="settings-item">
                            <div class="d-flex align-items-center">
                                <div class="settings-icon"><i class="far fa-bell"></i></div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Notifikasi Email</h6>
                                    <small class="text-muted">Terima kabar terbaru dari teman</small>
                                </div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" checked disabled>
                            </div>
                        </li>
                        
                        <li class="settings-item clickable" onclick="toggleDarkMode()">
                            <div class="d-flex align-items-center">
                                <div class="settings-icon"><i class="fas fa-moon"></i></div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Mode Gelap</h6>
                                    <small class="text-muted">Ganti tampilan aplikasi</small>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </li>

                        @if(!$isGuest)
                        <li class="settings-item clickable" onclick="document.getElementById('password-section').classList.toggle('d-none')">
                            <div class="d-flex align-items-center">
                                <div class="settings-icon"><i class="fas fa-lock"></i></div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Ganti Password</h6>
                                    <small class="text-muted">Perbarui kata sandi akunmu</small>
                                </div>
                            </div>
                            <i class="fas fa-chevron-down text-muted small"></i>
                        </li>
                        @endif
                    </ul>

                    <!-- Password Section (Hidden by default) -->
                    <div id="password-section" class="mt-4 p-4 rounded-4 bg-bs-theme d-none border">
                        <h6 class="fw-bold mb-3">Ubah Kata Sandi</h6>
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label small">Password Lama</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Password Baru</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-bs-theme btn-sm rounded-pill px-3">Update Password</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('d-none'));
        // Show target tab
        document.getElementById(tabName).classList.remove('d-none');
        
        // Update button states
        document.querySelectorAll('.active-tab-btn').forEach(btn => {
            btn.classList.remove('text-primary-custom', 'active-tab-btn');
            btn.classList.add('text-muted');
        });
        
        const activeBtn = document.getElementById('btn-tab-' + (tabName === 'edit-profile' ? 'edit' : 'settings'));
        activeBtn.classList.remove('text-muted');
        activeBtn.classList.add('text-primary-custom', 'active-tab-btn');
    }
</script>
@endsection