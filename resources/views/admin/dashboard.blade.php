@extends('layouts.admin')

@section('title', 'Dashboard - Admin Wishnotes')

@section('styles')
<style>
    .admin-banner i { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); opacity: .2; font-size: 5rem; }
    .stat-card { border: none; border-radius: 15px; color: white; transition: .2s; overflow: hidden; position: relative; height: 100%; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .stat-card i.bg-icon { position: absolute; right: -10px; bottom: -10px; font-size: 5rem; opacity: .2; z-index: 1; transform: rotate(-20deg); }
    .bg-gradient-blue { background: linear-gradient(45deg, #4facfe, #00f2fe); }
    .bg-gradient-purple { background: linear-gradient(45deg, #8A2BE2, #FF69B4); }
    .bg-gradient-orange { background: linear-gradient(45deg, #ff9a9e, #fad0c4); }
    
    .activity-card { border-left: 5px solid #ddd; background: white; border-radius: 10px; margin-bottom: 15px; transition: transform 0.2s; }
    .activity-card:hover { transform: translateX(5px); }
    .activity-card.public { border-left-color: #4facfe; }
    .activity-card.private { border-left-color: #FF69B4; }
    
    /* Responsive Text */
    @media (max-width: 768px) {
        .admin-banner h2 { font-size: 1.5rem; }
        .stat-card h5 { font-size: 0.9rem; }
        .stat-card h2 { font-size: 1.8rem; }
    }
</style>
@endsection

@section('content')
    <!-- Banner -->
    <div class="admin-banner d-flex justify-content-between align-items-center mb-4 mt-2">
        <div class="position-relative z-2">
            <h2 class="fw-bold mb-1">Selamat Datang, Admin!</h2>
            <p class="mb-0 opacity-75 d-none d-md-block">Pantau seluruh aktivitas pengguna Wishnotes di sini dengan mudah.</p>
            <p class="mb-0 opacity-75 d-md-none">Panel Kontrol Wishnotes</p>
        </div>
        <i class="fas fa-cogs"></i>
    </div>

    {{-- STATISTIK KARTU --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="stat-card bg-gradient-blue shadow-sm">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="mb-1 opacity-75">Total Catatan</h5>
                        <h2 class="fw-bold mb-0">{{ number_format($totalCatatan) }}</h2>
                    </div>
                    <i class="fas fa-clipboard-list bg-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card bg-gradient-purple shadow-sm">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="mb-1 opacity-75">Total User</h5>
                        <h2 class="fw-bold mb-0">{{ number_format($activeUsers) }}</h2>
                    </div>
                    <i class="fas fa-users bg-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card bg-gradient-orange shadow-sm">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="mb-1 opacity-75">Catatan Private</h5>
                        <h2 class="fw-bold mb-0">{{ number_format($catatanPrivate) }}</h2>
                    </div>
                    <i class="fas fa-lock bg-icon"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART SECTION --}}
    <div class="row g-4 mb-4">
        <!-- Chart 1 -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-secondary"><i class="fas fa-chart-pie me-2"></i>Statistik Catatan</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="noteChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart 2 -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-secondary"><i class="fas fa-chart-line me-2"></i>Tren 30 Hari</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TOMBOL DOWNLOAD --}}
    <div class="card border-0 shadow-sm rounded-4 mb-5 bg-white">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3 p-4">
            <div>
                <h6 class="fw-bold mb-1">Export Laporan</h6>
                <p class="text-muted small mb-0">Unduh data statistik untuk keperluan arsip.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.export.excel') }}" class="btn btn-success btn-sm rounded-pill px-3 fw-bold">
                    <i class="fas fa-file-excel me-1"></i> Excel
                </a>
                <a href="{{ route('admin.export.pdf') }}" target="_blank" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                    <i class="fas fa-file-pdf me-1"></i> PDF
                </a>
            </div>
        </div>
    </div>

    {{-- AKTIVITAS TERBARU --}}
    <h5 class="fw-bold mb-3 text-secondary border-bottom pb-2">Aktivitas Terbaru</h5>
    <div class="row g-3">
        @forelse($recentActivities as $note)
        <div class="col-md-6 col-xl-4">
            <div class="card activity-card shadow-sm h-100 {{ strtolower($note->privasi) == 'private' ? 'private' : 'public' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge {{ strtolower($note->privasi) == 'private' ? 'bg-danger' : 'bg-primary' }} rounded-pill">
                            {{ ucfirst($note->privasi) }}
                        </span>
                        <small class="text-muted" style="font-size: 0.75rem;">
                            <i class="far fa-clock me-1"></i> {{ $note->created_at->diffForHumans() }}
                        </small>
                    </div>
                    
                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $note->judul }}">
                        <a href="{{ route('admin.notes.show', $note->id) }}" class="text-decoration-none text-dark stretched-link">
                            {{ $note->judul }}
                        </a>
                    </h6>
                    
                    <p class="small text-muted fst-italic mb-2" style="font-size: 0.85rem; height: 40px; overflow: hidden;">
                        "{{ Str::limit($note->deskripsi_singkat, 60) }}"
                    </p>
                    
                    <div class="d-flex align-items-center mt-2 border-top pt-2">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold me-2" style="width: 25px; height: 25px; font-size: 0.7rem;">
                            {{ substr($note->user->name ?? 'A', 0, 1) }}
                        </div>
                        <small class="text-muted fw-bold" style="font-size: 0.75rem;">{{ $note->user->name ?? 'Anonim' }}</small>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-light text-center border-0 shadow-sm py-4">
                <i class="fas fa-inbox fa-2x text-muted mb-2 opacity-50"></i>
                <p class="text-muted mb-0">Belum ada aktivitas terbaru hari ini.</p>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Spacer footer -->
    <div style="height: 50px;"></div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Config umum agar chart responsif
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false, // Penting agar chart mengikuti tinggi container
        plugins: {
            legend: { display: false } // Legend disembunyikan agar bersih di mobile
        }
    };

    // --- CHART 1: DATA STATIS ---
    const ctxNote = document.getElementById('noteChart');
    new Chart(ctxNote, {
        type: 'bar',
        data: {
            labels: ['Total Catatan', 'User', 'Private', 'Public'],
            datasets: [{
                label: 'Jumlah',
                data: [
                    {{ $totalCatatan }},
                    {{ $activeUsers }},
                    {{ $catatanPrivate }},
                    {{ $catatanPublic ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(79, 172, 254, 0.8)',
                    'rgba(138, 43, 226, 0.8)',
                    'rgba(255, 154, 158, 0.8)',
                    'rgba(0, 242, 254, 0.8)'
                ],
                borderRadius: 6,
                barThickness: 'flex', // Bar lebar fleksibel
                maxBarThickness: 40
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                x: { grid: { display: false } }
            }
        }
    });

    // --- CHART 2: DATA TREN ---
    const chartLabels = @json($chartLabels ?? []); 
    const chartData = @json($chartData ?? []);
    const finalLabels = chartLabels.length ? chartLabels : Array.from({length: 7}, (_, i) => `H-${6-i}`);
    const finalData = chartData.length ? chartData : [0, 0, 0, 0, 0, 0, 0];

    const ctxMonthly = document.getElementById('monthlyChart');
    new Chart(ctxMonthly, {
        type: 'line',
        data: {
            labels: finalLabels,
            datasets: [{
                label: 'Postingan',
                data: finalData,
                borderColor: '#4facfe',
                backgroundColor: (context) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(79, 172, 254, 0.4)');
                    gradient.addColorStop(1, 'rgba(79, 172, 254, 0)');
                    return gradient;
                },
                fill: true,
                tension: 0.4,
                pointRadius: 0, // Titik disembunyikan agar clean
                pointHoverRadius: 6
            }]
        },
        options: {
            ...commonOptions,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#333',
                    bodyColor: '#666',
                    borderColor: '#ddd',
                    borderWidth: 1
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 4] }, ticks: { stepSize: 1 } },
                x: { grid: { display: false }, ticks: { maxTicksLimit: 7 } } // Batasi label X di mobile
            }
        }
    });
</script>
@endsection