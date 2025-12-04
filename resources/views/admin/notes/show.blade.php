@extends('layouts.admin')

@section('title', 'Moderasi Pesan - Admin Wishnotes')

@section('content')
<div class="container-fluid">
    
    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 15px; background: #fff;">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="text-secondary small fw-bold">MODERASI WISHNOTE #{{ $note->id }}</h5>
                    <h2 class="fw-bold text-dark">{{ $note->judul }}</h2>
                    <p class="text-muted">{{ $note->deskripsi_singkat }}</p>
                    <span class="badge {{ $note->privasi == 'public' ? 'bg-primary' : 'bg-danger' }}">
                        {{ ucfirst($note->privasi) }}
                    </span>
                    <span class="badge bg-info text-dark ms-1">
                        Owner: {{ $note->user->name ?? 'Anonim' }}
                    </span>
                </div>
                <div>
                    <a href="{{ route('admin.notes.index') }}" class="btn btn-outline-secondary rounded-pill">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Daftar Pesan ({{ $messages->total() }})</h4>
        <form action="{{ route('admin.notes.show', $note->id) }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control rounded-pill me-2" placeholder="Cari kata kasar..." value="{{ request('search') }}">
            <button class="btn btn-danger rounded-pill px-4" type="submit">Filter</button>
        </form>
    </div>

    <div class="card table-card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Waktu</th>
                        <th>Pengirim</th>
                        <th width="50%">Isi Pesan</th>
                        <th class="text-end">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $msg)
                    <tr>
                        <td class="small text-muted">{{ $msg->created_at->format('d M H:i') }}</td>
                        <td>
                            @if($msg->sender)
                                <div class="fw-bold">{{ $msg->sender->name }}</div>
                                <small class="text-muted">{{ $msg->sender->email }}</small>
                            @else
                                <span class="text-muted fst-italic">Anonim / Guest</span>
                            @endif
                        </td>
                        <td>
                            <div class="p-2 rounded bg-light border">
                                {{ $msg->message }}
                            </div>
                        </td>
                        <td class="text-end">
                            <form action="{{ route('admin.messages.destroy', $msg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pesan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Pesan">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                            @if($msg->sender)
                            <button type="button" class="btn btn-sm btn-warning text-dark ms-1" 
                                    data-bs-toggle="modal" data-bs-target="#warnModal{{ $msg->id }}" title="Tegur User">
                                <i class="fas fa-exclamation-triangle"></i>
                            </button>

                            <div class="modal fade" id="warnModal{{ $msg->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.users.warn') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $msg->sender_id }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Tegur User: {{ $msg->sender->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Tingkat Teguran</label>
                                                    <select name="level" class="form-select" required>
                                                        <option value="1">1. Ringan (Mute 1 Hari)</option>
                                                        <option value="2">2. Menengah (Suspend Login 1 Minggu)</option>
                                                        <option value="3">3. BERAT (Hapus Akun & Ban Email)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Pesan Admin</label>
                                                    <textarea name="pesan_admin" class="form-control" rows="3" required placeholder="Contoh: Anda menggunakan kata kasar..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-danger">Kirim Sanksi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">Aman, tidak ada pesan ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection