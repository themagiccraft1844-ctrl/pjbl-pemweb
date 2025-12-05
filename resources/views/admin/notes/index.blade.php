@extends('layouts.admin')

@section('title', 'Kelola Catatan - Wishnotes Admin')

@section('styles')
<style>
    /* CSS Khusus Halaman Notes */
    .table thead th { background-color: #f8f9fa; border-bottom: 2px solid #eee; color: #666; font-weight: 700; text-transform: uppercase; font-size: 0.85rem; padding: 15px; }
    .table tbody td { padding: 15px; vertical-align: middle; color: #444; }
    .table-hover tbody tr:hover { background-color: #fbfbfb; }
    
    /* Badge Styling */
    .badge-type { font-size: 0.75rem; padding: 5px 10px; border-radius: 50px; }
    .type-tree { background-color: #e0f2f1; color: #00695c; }
    .type-mading { background-color: #fce4ec; color: #880e4f; }
    .type-mailbox { background-color: #fff8e1; color: #ff8f00; }

    /* Mobile Card Styling */
    .mobile-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        margin-bottom: 15px;
        border: 1px solid #f0f0f0;
        transition: transform 0.2s;
    }
    .mobile-card:active { transform: scale(0.98); }
    .mobile-card-header { padding: 15px; border-bottom: 1px solid #f8f9fa; }
    .mobile-card-body { padding: 15px; }
    .mobile-card-footer { padding: 12px 15px; background: #fcfcfc; border-top: 1px solid #f0f0f0; border-radius: 0 0 12px 12px; }
</style>
@endsection

@section('content')
    <!-- Header & Search (Responsive) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h3 class="fw-bold text-dark mb-0">Kelola Catatan</h3>
        
        <form action="{{ route('admin.notes.index') }}" method="GET" class="d-flex w-100 w-md-auto">
            <input type="text" name="search" class="form-control rounded-pill me-2" placeholder="Cari judul..." value="{{ request('search') }}">
            <button class="btn btn-primary rounded-pill px-4" type="submit">Cari</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- TAMPILAN DESKTOP (TABEL) - Hanya muncul di layar md ke atas -->
    <div class="d-none d-md-block">
        <div class="card table-card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th width="25%">Judul & Deskripsi</th>
                            <th>Penulis</th>
                            <th>Tipe</th>
                            <th>Privasi</th>
                            <th>Stats</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notes as $note)
                        <tr>
                            <td>#{{ $note->id }}</td>
                            <td>
                                <div class="fw-bold">{{ $note->judul }}</div>
                                <small class="text-muted text-truncate d-block" style="max-width: 200px;">
                                    {{ $note->deskripsi_singkat }}
                                </small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold small">{{ $note->user->name ?? 'Anonim' }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-type {{ $note->tipe_wadah == 'tree' ? 'type-tree' : ($note->tipe_wadah == 'mading' ? 'type-mading' : 'type-mailbox') }}">
                                    <i class="fas {{ $note->tipe_wadah == 'tree' ? 'fa-tree' : ($note->tipe_wadah == 'mading' ? 'fa-note-sticky' : 'fa-envelope') }} me-1"></i>
                                    {{ ucfirst($note->tipe_wadah) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $note->privasi == 'public' ? 'bg-primary' : 'bg-danger' }} bg-opacity-75">
                                    {{ ucfirst($note->privasi) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted"><i class="fas fa-comment me-1"></i> {{ $note->messages_count }}</small>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.notes.show', $note->id) }}" class="btn btn-sm btn-outline-info me-1" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.notes.destroy', $note->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus konten ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Konten">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Tidak ada catatan ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAMPILAN MOBILE (CARD LIST) - Hanya muncul di layar kecil (hp) -->
    <div class="d-block d-md-none">
        @forelse($notes as $note)
        <div class="mobile-card">
            <!-- Header: Judul & ID -->
            <div class="mobile-card-header d-flex justify-content-between align-items-start">
                <div>
                    <span class="text-muted small fw-bold">#{{ $note->id }}</span>
                    <h5 class="fw-bold text-dark mb-0 mt-1">{{ $note->judul }}</h5>
                </div>
                <span class="badge {{ $note->privasi == 'public' ? 'bg-primary' : 'bg-danger' }}">
                    {{ ucfirst($note->privasi) }}
                </span>
            </div>

            <!-- Body: Deskripsi & Info Penulis -->
            <div class="mobile-card-body pt-2 pb-2">
                <p class="text-muted small mb-3">{{ Str::limit($note->deskripsi_singkat, 60) }}</p>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-circle text-secondary me-2 fs-5"></i>
                        <span class="fw-bold small text-dark">{{ $note->user->name ?? 'Anonim' }}</span>
                    </div>
                    
                    <span class="badge-type {{ $note->tipe_wadah == 'tree' ? 'type-tree' : ($note->tipe_wadah == 'mading' ? 'type-mading' : 'type-mailbox') }}">
                        <i class="fas {{ $note->tipe_wadah == 'tree' ? 'fa-tree' : ($note->tipe_wadah == 'mading' ? 'fa-note-sticky' : 'fa-envelope') }}"></i>
                        {{ ucfirst($note->tipe_wadah) }}
                    </span>
                </div>
            </div>

            <!-- Footer: Aksi Tombol -->
            <div class="mobile-card-footer d-flex justify-content-between align-items-center">
                <small class="text-muted fw-bold">
                    <i class="fas fa-comment text-info me-1"></i> {{ $note->messages_count }} Pesan
                </small>

                <div class="d-flex gap-2">
                    <!-- Tombol Hapus -->
                    <form action="{{ route('admin.notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('Hapus konten ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger px-3 rounded-pill">
                            <i class="fas fa-trash-alt me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted opacity-25 mb-3"></i>
            <p class="text-muted">Tidak ada catatan.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $notes->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

@endsection