@extends('layouts.admin')

@section('title', 'Dashboard - Admin Wishnotes')

@section('styles')
<style>
    /* CSS Khusus Dashboard saja */
    .admin-banner i { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); opacity: 0.2; font-size: 5rem; }
    .stat-card { border: none; border-radius: 15px; color: white; transition: transform 0.2s; overflow: hidden; position: relative; }
    .stat-card:hover { transform: translateY(-5px); }
    .stat-card i.bg-icon { position: absolute; right: -10px; bottom: -10px; font-size: 5rem; opacity: 0.2; z-index: 1; transform: rotate(-20deg); }
    .bg-gradient-blue { background: linear-gradient(45deg, #4facfe, #00f2fe); }
    .bg-gradient-purple { background: linear-gradient(45deg, #8A2BE2, #FF69B4); }
    .bg-gradient-orange { background: linear-gradient(45deg, #ff9a9e, #fad0c4); }
    .activity-card { border-left: 5px solid #ddd; background: white; border-radius: 10px; margin-bottom: 15px; transition: 0.2s; }
    .activity-card.public { border-left-color: #4facfe; }
    .activity-card.private { border-left-color: #FF69B4; }
</style>
@endsection

@section('content')
    <div class="admin-banner d-flex justify-content-between align-items-center m-4">
        <div>
            <h2 class="fw-bold">Selamat Datang di Admin Center!</h2>
            <p class="mb-0 opacity-75">Pantau seluruh aktivitas dan harapan pengguna Wishnotes di sini.</p>
        </div>
        <i class="fas fa-cogs"></i>
    </div>

    <div class="row g-4 m-3">
        <div class="col-md-4">
            <div class="stat-card bg-gradient-blue shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title fw-normal">Total Catatan</h5>
                    <h2 class="fw-bold my-2">{{ number_format($totalCatatan) }}</h2>
                    <small class="opacity-75">Lihat Semua Data</small>
                    <i class="fas fa-clipboard-list bg-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-gradient-purple shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title fw-normal">User Aktif</h5>
                    <h2 class="fw-bold my-2">{{ number_format($activeUsers) }}</h2>
                    <small class="opacity-75">Sedang Login</small>
                    <i class="fas fa-users bg-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-gradient-orange shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title fw-normal">Catatan Private</h5>
                    <h2 class="fw-bold my-2">{{ number_format($catatanPrivate) }}</h2>
                    <small class="opacity-75">Total Pribadi</small>
                    <i class="fas fa-lock bg-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <h4 class="fw-bold mb-3 text-secondary">Aktivitas Terbaru</h4>
    <div class="row">
        @forelse($recentActivities as $note)
        {{-- Logic untuk menentukan URL berdasarkan Skin --}}
       @php
        $wadah = strtolower($note->tipe_wadah ?? '');

        if ($wadah === 'mading') {
            $urlPrefix = 'mading';
        } elseif ($wadah === 'mailbox') {
            $urlPrefix = 'mailbox';
        } else {
            // fallback default
            $urlPrefix = 'pohon';
        }
    @endphp


        <div class="col-md-6 col-lg-4">
        <div class="card activity-card shadow-sm h-100 {{ strtolower($note->privasi) == 'private' ? 'private' : 'public' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge {{ strtolower($note->privasi) == 'private' ? 'bg-danger' : 'bg-primary' }} bg-opacity-10 text-{{ strtolower($note->privasi) == 'private' ? 'danger' : 'primary' }}">
                            {{ $note->privasi }}
                        </span>
                        <small class="text-muted"><i class="far fa-clock me-1"></i> {{ $note->created_at->diffForHumans() }}</small>
                    </div>
                    <h5 class="card-title fw-bold text-dark">{{ $note->judul }}</h5>
                    <p class="card-text text-muted small fst-italic">"{{ Str::limit($note->deskripsi_singkat, 60) }}"</p>
                    
                    <div class="d-flex align-items-center mt-3 pt-3 border-top">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($note->user->name ?? 'Anonim') }}&background=random" class="rounded-circle me-2" width="25">
                        <small class="text-muted fw-bold">{{ $note->user->name ?? 'Anonim' }}</small>
                        
                        <div class="ms-auto">
                            {{-- LOGIC BARU: Admin bisa lihat Private & Link sesuai Skin --}}
                            @if(strtolower($note->privasi) == 'private')
                                {{-- Tombol merah untuk private, tapi tetap bisa diklik --}}
                                <a href="{{ url('/' . $urlPrefix . '/' . $note->id) }}" class="btn btn-sm btn-outline-danger" style="font-size: 0.75rem;">
                                    <i class="fas fa-user-secret me-1"></i> Intip (Admin)
                                </a>
                            @else
                                <a href="{{ url('/' . $urlPrefix . '/' . $note->id) }}" class="btn btn-sm btn-outline-primary" style="font-size: 0.75rem;">Lihat</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">Belum ada aktivitas terbaru.</p>
        </div>
        @endforelse
    </div>
@endsection

