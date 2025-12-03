<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishnotes - Daftar Teman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #f8f9fa; }
        .navbar { background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .navbar-brand { font-weight: 700; color: #a18cd1 !important; font-size: 1.5rem; }
        .nav-profile-img { width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 2px solid #fbc2eb; }
        
        .friend-card { border: none; border-radius: 15px; background: white; transition: 0.2s; }
        .friend-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .btn-add { background-color: #e3f2fd; color: #2196f3; border: none; font-weight: bold; }
        .btn-add:hover { background-color: #bbdefb; color: #1976d2; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="dashboard"><i class="fa-solid fa-gift me-2"></i>Wishnotes</a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item me-3"><a class="nav-link" href="dashboard">Kembali ke Dashboard</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <span class="me-2 fw-bold text-dark">{{ auth()->user()->name ?? 'Guest' }}</span>
                            <img src="https://ui-avatars.com/api/?name=Guest+User&background=random" class="nav-profile-img">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow p-2" style="border-radius: 15px;">
                            <li><a class="dropdown-item rounded" href="profil"><i class="fas fa-user-circle me-2 text-primary"></i> Profile</a></li>
                            <li><a class="dropdown-item rounded" href="friendlist"><i class="fas fa-user-plus me-2 text-success"></i> Teman</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item rounded text-danger" href="login"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <h3 class="fw-bold mb-4"><i class="fas fa-users me-2 text-muted"></i>Manajemen Teman</h3>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm p-3" style="border-radius: 20px;">
                    <h5 class="fw-bold mb-3">Cari Teman Baru</h5>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control rounded-start-pill border-end-0" placeholder="Username...">
                        <button class="btn btn-outline-secondary rounded-end-pill border-start-0" type="button"><i class="fas fa-search"></i></button>
                    </div>
                    
                    <h6 class="text-muted small fw-bold mt-2">SARAN TEMAN</h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=ffecb3" class="rounded-circle me-2" width="40">
                                <div>
                                    <div class="fw-bold small">Budi Santoso</div>
                                    <div class="text-muted x-small" style="font-size: 0.75rem;">2 Teman yang sama</div>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-add rounded-pill"><i class="fas fa-user-plus"></i></button>
                        </div>
                        <div class="list-group-item border-0 px-0 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Citra+Dewi&background=c8e6c9" class="rounded-circle me-2" width="40">
                                <div>
                                    <div class="fw-bold small">Citra Dewi</div>
                                    <div class="text-muted x-small" style="font-size: 0.75rem;">Baru bergabung</div>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-add rounded-pill"><i class="fas fa-user-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold">Teman Saya (2)</h5>
                    <button class="btn btn-sm btn-light text-muted rounded-pill"><i class="fas fa-filter me-1"></i> Urutkan</button>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="friend-card p-3 d-flex justify-content-between align-items-center shadow-sm">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Kevin&background=ef5350&color=fff" class="rounded-circle me-3" width="50">
                                <div>
                                    <h6 class="fw-bold mb-0">Kevin</h6>
                                    <small class="text-muted">Aktif 5 menit lalu</small>
                                </div>
                            </div>
                            <button class="btn btn-light rounded-circle text-muted"><i class="fas fa-comment-dots"></i></button>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="friend-card p-3 d-flex justify-content-between align-items-center shadow-sm">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Sarah&background=ab47bc&color=fff" class="rounded-circle me-3" width="50">
                                <div>
                                    <h6 class="fw-bold mb-0">Sarah</h6>
                                    <small class="text-muted">Aktif 1 jam lalu</small>
                                </div>
                            </div>
                            <button class="btn btn-light rounded-circle text-muted"><i class="fas fa-comment-dots"></i></button>
                        </div>
                    </div>
                </div>
                
                </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>