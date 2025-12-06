@extends('layouts.app')

@section('title', 'Daftar Teman - Wishnotes')

@push('styles')
    @vite(['resources/css/style.css'])
@endpush

@section('content')
    <!-- Navbar dihapus karena sudah ada di layout utama -->

    <div class="container mb-5">
        <div class="row g-4">
            
            <!-- KOLOM KIRI: PENCARIAN & SARAN -->
            <div class="col-lg-4 order-lg-last">
                <div class="profile-card p-4">
                    <h5 class="fw-bold mb-3 text-primary-custom"><i class="fas fa-search me-2"></i>Cari Teman</h5>
                    
                    <form action="{{ route('friendlist') }}" method="GET" class="mb-4">
                        <div class="input-group search-box">
                            <input type="text" name="search" class="form-control" placeholder="Ketik nama teman..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                    
                    <h6 class="text-muted small fw-bold text-uppercase mb-3">Saran Teman</h6>
                    <div class="list-group list-group-flush">
                        @forelse($suggestions as $suggest)
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($suggest->name) }}&background=random" class="rounded-circle me-3 shadow-sm" width="40" height="40">
                                <div>
                                    <div class="fw-bold small text-bs-theme">{{ $suggest->name }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Pengguna Baru</div>
                                </div>
                            </div>
                            <form action="{{ route('friend.add', $suggest->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary-custom rounded-pill px-3" title="Tambah Teman">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                            </form>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted small bg-bs-theme rounded-3">
                            <i class="fas fa-search mb-2 opacity-50"></i><br>
                            Tidak ada saran saat ini.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: DAFTAR TEMAN -->
            <div class="col-lg-8 order-lg-first">
                <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                    <h4 class="fw-bold m-0"><i class="fas fa-user-friends me-2 text-warning"></i>Teman Saya <span class="badge bg-secondary rounded-pill ms-2 fs-6 align-middle">{{ $friends->count() }}</span></h4>
                </div>

                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="row g-3">
                    @forelse($friends as $friend)
                    <div class="col-md-6">
                        <div class="friend-card p-3 d-flex justify-content-between align-items-center h-100">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($friend->name) }}&background=random" class="rounded-circle me-3 border border-2 border-white shadow-sm" width="50" height="50">
                                <div>
                                    <h6 class="fw-bold mb-0 text-bs-theme">{{ $friend->name }}</h6>
                                    <small class="text-muted" style="font-size: 0.8rem;">{{ $friend->email }}</small>
                                </div>
                            </div>
                            
                            <div class="dropdown">
                                <button class="btn btn-bs-theme btn-sm rounded-circle text-muted" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 p-2">
                                    <li><a class="dropdown-item rounded py-2" href="#"><i class="fas fa-gift me-2 text-primary"></i> Lihat Harapan</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('friend.remove', $friend->id) }}" method="POST" onsubmit="return confirm('Hapus pertemanan dengan {{ $friend->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item rounded py-2 text-danger"><i class="fas fa-user-times me-2"></i> Hapus Teman</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="profile-card p-5 text-center">
                            <div class="mb-3 text-muted opacity-25">
                                <i class="fas fa-user-group fa-4x"></i>
                            </div>
                            <h5 class="fw-bold text-muted">Belum Punya Teman</h5>
                            <p class="text-muted small">Cari teman baru di kolom pencarian atau undang mereka!</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection