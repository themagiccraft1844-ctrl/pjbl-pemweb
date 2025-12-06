@forelse ($notes as $note)
    <div class="col-12 col-sm-6 col-lg-4">
        <article class="wish-card shadow-sm h-100" onclick="openDetail({{ $note->id }}, '{{ $note->tipe_wadah }}')">
            
            <div class="skin-badge {{ $note->tipe_wadah == 'tree' ? 'skin-tree' : ($note->tipe_wadah == 'mading' ? 'skin-mading' : 'skin-mailbox') }}">
                <i class="fa-solid {{ $note->tipe_wadah == 'tree' ? 'fa-tree' : ($note->tipe_wadah == 'mading' ? 'fa-note-sticky' : 'fa-envelope-open-text') }}"></i>
            </div>

            <div class="card-body mt-4 d-flex flex-column h-100">
                
                @if(isset($isMine) && $isMine)
                    <div class="position-absolute top-0 start-0 m-3 z-3">
                        <form action="{{ route('wishnotes.destroy', $note->id) }}" method="POST" onsubmit="event.stopPropagation(); return confirm('Hapus wishnote ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3 py-1" onclick="event.stopPropagation();">
                                <i class="fas fa-trash-alt me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                @elseif($note->like_count > 5)
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i> Populer</span>
                    </div>
                @endif

                <h5 class="card-title mt-2 text-truncate">{{ $note->judul }}</h5>
                <p class="card-text text-truncate">{{ $note->deskripsi_singkat }}</p>

                <div class="mt-auto">
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="status-pill {{ $note->privasi == 'public' ? 'status-public' : 'status-private' }}">
                            {{ ucfirst($note->privasi) }}
                        </span>
                        <small class="text-muted"><i class="fas fa-comment-dots me-1"></i> {{ $note->messages_count ?? 0 }} Pesan</small>
                    </div>

                    <hr class="my-3 text-muted opacity-25">

                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($note->user->name ?? 'Unknown') }}&background=random" class="rounded-circle me-2" width="25">
                            <small class="text-muted text-truncate" style="max-width: 100px;">{{ $note->user->name ?? 'Unknown' }}</small>
                        </div>
                        <small class="text-muted" style="font-size: 0.7rem;">{{ $note->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        </article>
    </div>
@empty
    {{-- Empty state handled in parent if needed, or just blank --}}
@endforelse