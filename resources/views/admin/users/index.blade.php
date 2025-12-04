@extends('layouts.admin')

@section('title', 'Kelola Pengguna - Wishnotes Admin')

@section('styles')
<style>
    /* CSS Khusus Halaman Users */
    .table thead th { background-color: #f8f9fa; border-bottom: 2px solid #eee; color: #666; font-weight: 700; text-transform: uppercase; font-size: 0.85rem; padding: 15px; }
    .table tbody td { padding: 15px; vertical-align: middle; color: #444; }
    .role-badge { font-size: 0.75rem; padding: 5px 12px; border-radius: 50px; font-weight: 700; }
    .role-admin { background-color: #e3f2fd; color: #1565c0; }
    .role-user { background-color: #f3e5f5; color: #7b1fa2; }
    .role-guest { background-color: #eeeeee; color: #616161; }
</style>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">Kelola Pengguna</h3>
        
        <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control rounded-pill me-2" placeholder="Cari user..." value="{{ request('search') }}">
            <button class="btn btn-primary rounded-pill px-4" type="submit">Cari</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            {{ session('error') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card table-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Total Wishnotes</th>
                        <th>Bergabung</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" class="rounded-circle me-3" width="35">
                                <div class="fw-bold">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="role-badge {{ $user->role == 'admin' ? 'role-admin' : ($user->role == 'guest' ? 'role-guest' : 'role-user') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-secondary rounded-pill px-3">{{ $user->wishnotes_count }}</span>
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                
                                {{-- TOMBOL UBAH ROLE --}}
                                @if($user->role == 'user' || $user->role == 'guest')
                                    <form action="{{ route('admin.users.promote', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-2" title="Jadikan Admin" onclick="return confirm('Jadikan user ini sebagai Admin?')">
                                            <i class="fas fa-user-shield"></i>
                                        </button>
                                    </form>
                                @elseif($user->role == 'admin')
                                    {{-- Cek agar tidak bisa demote diri sendiri --}}
                                    @if($user->id != auth()->id())
                                        <form action="{{ route('admin.users.demote', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill px-2" title="Cabut Admin" onclick="return confirm('Cabut akses Admin user ini?')">
                                                <i class="fas fa-user-minus"></i>
                                            </button>
                                        </form>
                                    @else
                                        {{-- Indikator diri sendiri --}}
                                        <span class="badge bg-light text-muted border">You</span>
                                    @endif
                                @endif

                                {{-- TOMBOL HAPUS --}}
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2" title="Hapus User">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-users-slash fa-2x mb-3 opacity-25"></i>
                            <p>Tidak ada pengguna ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-3">
            {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection