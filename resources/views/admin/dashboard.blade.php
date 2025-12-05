@extends('layouts.admin')

@section('title', 'Dashboard - Admin Wishnotes')

@section('styles')
<style>
    .admin-banner i { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); opacity: .2; font-size: 5rem; }
    .stat-card { border: none; border-radius: 15px; color: white; transition: .2s; overflow: hidden; position: relative; }
    .stat-card:hover { transform: translateY(-5px); }
    .stat-card i.bg-icon { position: absolute; right: -10px; bottom: -10px; font-size: 5rem; opacity: .2; z-index: 1; transform: rotate(-20deg); }
    .bg-gradient-blue { background: linear-gradient(45deg, #4facfe, #00f2fe); }
    .bg-gradient-purple { background: linear-gradient(45deg, #8A2BE2, #FF69B4); }
    .bg-gradient-orange { background: linear-gradient(45deg, #ff9a9e, #fad0c4); }
    .activity-card { border-left: 5px solid #ddd; background: white; border-radius: 10px; margin-bottom: 15px; }
    .activity-card.public { border-left-color: #4facfe; }
    .activity-card.private { border-left-color: #FF69B4; }
</style>
@endsection

@section('content')
    <div class="admin-banner d-flex justify-content-between align-items-center m-4">
        <div>
            <h2 class="fw-bold">Selamat Datang di Admin Center!</h2>
            <p class="mb-0 opacity-75">Pantau seluruh aktivitas pengguna Wishnotes di sini.</p>
        </div>
        <i class="fas fa-cogs"></i>
    </div>

    {{-- STATISTIK KARTU --}}
    <div class="row g-4 m-3">
        <div class="col-md-4">
            <div class="stat-card bg-gradient-blue shadow-sm">
                <div class="card-body p-4">
                    <h5>Total Catatan</h5>
                    <h2 class="fw-bold">{{ number_format($totalCatatan) }}</h2>
                    <i class="fas fa-clipboard-list bg-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-gradient-purple shadow-sm">
                <div class="card-body p-4">
                    <h5>User Aktif</h5>
                    <h2 class="fw-bold">{{ number_format($activeUsers) }}</h2>
                    <i class="fas fa-users bg-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-gradient-orange shadow-sm">
                <div class="card-body p-4">
                    <h5>Catatan Private</h5>
                    <h2 class="fw-bold">{{ number_format($catatanPrivate) }}</h2>
                    <i class="fas fa-lock bg-icon"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART 1: Komposisi Catatan --}}
    <h4 class="fw-bold mb-3 mt-5">Statistik Catatan</h4>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <canvas id="noteChart" style="max-height: 400px;"></canvas>
        </div>
    </div>

    {{-- CHART 2: Tren Harian (Data Real) --}}
    <h4 class="fw-bold mb-3 mt-5">Postingan 30 Hari Terakhir</h4>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <canvas id="monthlyChart" style="max-height: 400px;"></canvas>
        </div>
    </div>

    {{-- TOMBOL DOWNLOAD (SUDAH DIPERBAIKI) --}}
    <div class="text-end mb-5 d-flex justify-content-end gap-2">
        <a href="{{ route('admin.export.excel') }}" class="btn btn-success btn-sm"><i class="fas fa-file-excel me-1"></i> Download Excel</a>
        <a href="{{ route('admin.export.pdf') }}" target="_blank" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf me-1"></i> Download PDF</a>
    </div>

    {{-- AKTIVITAS TERBARU --}}
    <h4 class="fw-bold mb-3 text-secondary">Aktivitas Terbaru</h4>
    <div class="row">
        @forelse($recentActivities as $note)
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card activity-card shadow-sm h-100 {{ strtolower($note->privasi) == 'private' ? 'private' : 'public' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge {{ strtolower($note->privasi) == 'private' ? 'bg-danger' : 'bg-primary' }}">
                            {{ ucfirst($note->privasi) }}
                        </span>
                        <small class="text-muted" style="font-size: 0.75rem;">
                            {{ $note->created_at->diffForHumans() }}
                        </small>
                    </div>
                    <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $note->judul }}</h6>
                    <p class="small text-muted fst-italic mb-0" style="font-size: 0.85rem;">
                        "{{ Str::limit($note->deskripsi_singkat, 50) }}"
                    </p>
                    <div class="mt-2 text-end">
                        <small class="text-muted" style="font-size: 0.7rem;">by {{ $note->user->name ?? 'Anonim' }}</small>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p class="text-center text-muted py-4 bg-light rounded">Belum ada aktivitas terbaru.</p>
        </div>
        @endforelse
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- CHART 1: DATA STATIS (Dari Controller) ---
    const ctxNote = document.getElementById('noteChart');
    new Chart(ctxNote, {
        type: 'bar',
        data: {
            // Label statis
            labels: ['Total Catatan', 'User Aktif', 'Catatan Private', 'Catatan Public'],
            datasets: [{
                label: 'Jumlah',
                // Data diambil dari variabel Blade
                data: [
                    {{ $totalCatatan }},
                    {{ $activeUsers }},
                    {{ $catatanPrivate }},
                    {{ $catatanPublic ?? 0 }} // Fallback jika variabel null
                ],
                backgroundColor: [
                    'rgba(79, 172, 254, 0.7)', // Biru
                    'rgba(138, 43, 226, 0.7)', // Ungu
                    'rgba(255, 154, 158, 0.7)', // Merah Muda
                    'rgba(0, 242, 254, 0.7)'   // Cyan
                ],
                borderColor: [
                    '#4facfe',
                    '#8A2BE2',
                    '#ff9a9e',
                    '#00f2fe'
                ],
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f0f0f0' } }
            }
        }
    });

    // --- CHART 2: DATA TREN (Dari Controller) ---
    const chartLabels = @json($chartLabels ?? []); 
    const chartData = @json($chartData ?? []);

    // Fallback data dummy jika controller belum mengirim data
    const finalLabels = chartLabels.length ? chartLabels : Array.from({length: 7}, (_, i) => `H-${6-i}`);
    const finalData = chartData.length ? chartData : [0, 0, 0, 0, 0, 0, 0];

    const ctxMonthly = document.getElementById('monthlyChart');
    new Chart(ctxMonthly, {
        type: 'line',
        data: {
            labels: finalLabels,
            datasets: [{
                label: 'Postingan Harian',
                data: finalData,
                borderColor: '#4facfe',
                backgroundColor: 'rgba(79, 172, 254, 0.2)',
                tension: 0.4, // Kurva halus
                fill: true,
                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: { stepSize: 1 } // Agar sumbu Y bulat (tidak pecahan)
                }
            }
        }
    });
</script>
@endsection