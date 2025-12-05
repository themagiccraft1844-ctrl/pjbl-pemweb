<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishnotes - Bagikan Harapanmu</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&family=Patrick+Hand&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
        }

        /* --- NAVBAR --- */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        .navbar-brand {
            font-family: 'Patrick Hand', cursive;
            font-size: 1.8rem;
            color: #8A2BE2 !important;
        }
        .nav-link {
            font-weight: 700;
            color: #555 !important;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: #8A2BE2 !important;
        }
        .btn-nav-login {
            border: 2px solid #8A2BE2;
            color: #8A2BE2;
            font-weight: 700;
            border-radius: 50px;
            padding: 8px 25px;
            transition: all 0.3s;
        }
        .btn-nav-login:hover {
            background-color: #8A2BE2;
            color: white;
        }

        /* --- HERO SECTION --- */
        .hero-section {
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding-top: 80px; /* Space for fixed navbar */
        }
        
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .hero-content p {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 30px;
        }
        
        .btn-hero {
            background-color: white;
            color: #8A2BE2;
            font-weight: 800;
            padding: 15px 40px;
            border-radius: 50px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
        }
        .btn-hero:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            color: #8A2BE2;
        }

        /* Floating Elements Animation */
        .floating-item {
            position: absolute;
            animation: float 6s ease-in-out infinite;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));
        }
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        /* --- FEATURES SECTION --- */
        .features-section {
            padding: 80px 0;
            background-color: white;
        }
        .feature-card {
            text-align: center;
            padding: 40px 20px;
            border-radius: 20px;
            transition: transform 0.3s;
            height: 100%;
            background: #fff;
            border: 1px solid #f0f0f0;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.05);
            border-color: transparent;
        }
        .icon-box {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }
        
        /* --- FOOTER --- */
        footer {
            background-color: #f9f9f9;
            padding: 40px 0;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-star me-2 text-warning"></i>Wishnotes
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link" href="#fitur">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">Tentang</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="btn btn-nav-login">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-nav-login px-4">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-primary rounded-pill fw-bold px-4" style="background-color: #8A2BE2; border: none;">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero-section">
        
        <!-- Dekorasi Floating (Emoji/Icon) -->
        <div class="floating-item" style="top: 20%; left: 10%; font-size: 4rem;">🎄</div>
        <div class="floating-item" style="bottom: 20%; right: 10%; font-size: 4rem; animation-delay: 1s;">💌</div>
        <div class="floating-item" style="top: 15%; right: 20%; font-size: 3rem; animation-delay: 2s;">📌</div>
        <div class="floating-item" style="bottom: 15%; left: 20%; font-size: 3rem; animation-delay: 3s;">📬</div>

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content text-center text-lg-start mb-5 mb-lg-0">
                    <h1>Bagikan Harapan,<br>Sebarkan Kebahagiaan!</h1>
                    <p class="mt-3">
                        Wishnotes adalah tempat ajaib untuk menulis harapan di Pohon Natal, menempel pesan di Mading Sekolah, atau mengirim surat rahasia lewat Kotak Pos.
                    </p>
                    <div class="mt-4 d-flex gap-3 justify-content-center justify-content-lg-start">
                        <a href="{{ route('register') }}" class="btn btn-hero">
                            Mulai Sekarang <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <!-- <a href="#fitur" class="btn btn-outline-light rounded-pill px-4 py-3 fw-bold border-2">Pelajari Lebih Lanjut</a> -->
                    </div>
                </div>
                <!-- <div class="col-lg-6 text-center">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/christmas-tree-4190868-3467635.png" alt="Hero Illustration" class="img-fluid floating-item position-relative" style="max-width: 80%; animation: float 4s ease-in-out infinite;">
                </div> -->
            </div>
        </div>
        
        <!-- Wave Divider Bottom -->
        <div style="position: absolute; bottom: 0; left: 0; width: 100%; overflow: hidden; line-height: 0;">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" style="position: relative; display: block; width: calc(100% + 1.3px); height: 60px;">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#ffffff"></path>
            </svg>
        </div>
    </section>

    <!-- FITUR SECTION -->
    <section id="fitur" class="features-section">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-uppercase text-primary fw-bold letter-spacing-2">Fitur Unggulan</h6>
                <h2 class="fw-bold display-6">Ekspresikan Diri Dengan Cara Unik</h2>
            </div>

            <div class="row g-4">
                <!-- Fitur 1: Pohon Natal -->
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box bg-success shadow-sm">
                            <i class="fas fa-tree"></i>
                        </div>
                        <h4 class="fw-bold mt-3">Pohon Harapan</h4>
                        <p class="text-muted">Gantungkan bola-bola harapanmu di pohon natal virtual. Hiasi dengan warna-warni ceria.</p>
                    </div>
                </div>

                <!-- Fitur 2: Mading -->
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box bg-warning shadow-sm">
                            <i class="fas fa-thumbtack"></i>
                        </div>
                        <h4 class="fw-bold mt-3">Mading Sekolah</h4>
                        <p class="text-muted">Tempel pesan sticky notes di papan gabus digital. Serasa kembali ke masa sekolah!</p>
                    </div>
                </div>

                <!-- Fitur 3: Mailbox -->
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box bg-danger shadow-sm">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <h4 class="fw-bold mt-3">Kotak Surat Rahasia</h4>
                        <p class="text-muted">Kirim surat pribadi atau anonim ke temanmu lewat kotak pos yang estetik.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="py-5" style="background-color: #fcfaff;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="card border-0 shadow-lg rounded-4 p-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <h2 class="fw-bold mb-3">Siap Membuat Kenangan?</h2>
                        <p class="opacity-75 mb-4">Bergabunglah dengan ribuan pengguna lain dan mulailah berbagi cerita seru hari ini. Gratis!</p>
                        <div>
                            <a href="{{ route('register') }}" class="btn btn-light text-primary fw-bold rounded-pill px-5 py-3 shadow">
                                Buat Akun Gratis
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <p class="mb-0 fw-bold">&copy; {{ date('Y') }} Wishnotes. Dibuat dengan <i class="fas fa-heart text-danger"></i> dan Semangat.</p>
            <small class="text-muted">Project PJBL Pemrograman Web</small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>