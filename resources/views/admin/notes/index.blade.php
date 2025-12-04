@extends('layouts.admin')

@section('title', 'Kelola Catatan - Wishnotes Admin')

@section('styles')
<style>
    /* CSS Khusus Halaman Notes */
    .table thead th { background-color: #f8f9fa; border-bottom: 2px solid #eee; color: #666; font-weight: 700; text-transform: uppercase; font-size: 0.85rem; padding: 15px; }
    .table tbody td { padding: 15px; vertical-align: middle; color: #444; }
    .table-hover tbody tr:hover { background-color: #fbfbfb; }
    .badge-type { font-size: 0.75rem; padding: 5px 10px; border-radius: 50px; }
    .type-tree { background-color: #e0f2f1; color: #00695c; }
    .type-mading { background-color: #fce4ec; color: #880e4f; }
    .type-mailbox { background-color: #fff8e1; color: #ff8f00; }
</style>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">Kelola Catatan</h3>
        
        <form action="{{ route('admin.notes.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control rounded-pill me-2" placeholder="Cari judul..." value="{{ request('search') }}">
            <button class="btn btn-primary rounded-pill px-4" type="submit">Cari</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card table-card">
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
                            <span class="badge-type 
                                {{ $note->tipe_wadah == 'tree' ? 'type-tree' : ($note->tipe_wadah == 'mading' ? 'type-mading' : 'type-mailbox') }}">
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
                            <a href="{{ route('admin.notes.show', $note->id) }}" target="_blank" class="btn btn-sm btn-outline-info me-1" title="Lihat">
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
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-2x mb-3 opacity-25"></i>
                            <p>Tidak ada catatan ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-3">
            {{ $notes->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection