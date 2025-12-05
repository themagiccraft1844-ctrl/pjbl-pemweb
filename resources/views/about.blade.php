<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Wishnotes</title>
    
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
            background-color: #fcfaff;
        }

        /* --- NAVBAR (Konsisten dengan Index) --- */
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
        .nav-link:hover, .nav-link.active {
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

        /* --- HEADER SECTION --- */
        .about-header {
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            padding: 120px 0 80px;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .about-header h1 {
            font-weight: 800;
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .about-header p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        /* --- CONTENT SECTION --- */
        .content-section {
            padding: 80px 0;
        }
        .story-img {
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            transform: rotate(-2deg);
            transition: transform 0.3s;
        }
        .story-img:hover {
            transform: rotate(0deg) scale(1.02);
        }

        /* --- TEAM SECTION --- */
        .team-section {
            background-color: white;
            padding: 80px 0;
        }
        .team-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            text-align: center;
            padding: 30px;
        }
        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .team-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 5px solid #f0f0f0;
        }
        .team-role {
            color: #8A2BE2;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        /* --- VALUES SECTION --- */
        .values-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            height: 100%;
            border-left: 5px solid #a18cd1;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        }
        .values-box h4 {
            color: #555;
            font-weight: 700;
        }

        /* --- FOOTER --- */
        footer {
            background-color: #f9f9f9;
            padding: 40px 0;
            text-align: center;
            color: #666;
            margin-top: auto;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('index') }}"> <!-- Ganti # dengan route index -->
                <i class="fas fa-star me-2 text-warning"></i>Wishnotes
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('index') }}#fitur">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Tentang</a>
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

    <!-- HEADER SECTION -->
    <header class="about-header">
        <div class="container">
            <h1>Tentang Wishnotes</h1>
            <p>Menciptakan ruang digital yang hangat untuk berbagi harapan, mimpi, dan pesan positif kepada orang-orang terkasih.</p>
        </div>
        
        <!-- Decorative Waves -->
        <div style="position: absolute; bottom: 0; left: 0; width: 100%; overflow: hidden; line-height: 0;">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" style="position: relative; display: block; width: calc(100% + 1.3px); height: 60px;">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#fcfaff"></path>
            </svg>
        </div>
    </header>

    <!-- STORY SECTION -->
    <section class="content-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Team working" class="img-fluid story-img">
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <h6 class="text-uppercase text-primary fw-bold letter-spacing-2 mb-3">Cerita Kami</h6>
                    <h2 class="fw-bold mb-4">Berawal dari Ide Sederhana</h2>
                    <p class="text-muted leading-loose mb-4">
                        Wishnotes lahir dari proyek PJBL (Pembelajaran Berbasis Proyek) di sekolah kami. Kami ingin membuat sesuatu yang lebih dari sekadar tugas—sebuah platform di mana siswa bisa saling menyemangati, mengirim surat rahasia, atau sekadar menempelkan catatan kecil di mading virtual.
                    </p>
                    <p class="text-muted leading-loose">
                        Kami percaya bahwa kata-kata memiliki kekuatan. Dengan Wishnotes, kami berharap dapat menyebarkan kebaikan dan mempererat hubungan antar teman melalui fitur-fitur interaktif yang menyenangkan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- VALUES SECTION -->
    <section class="content-section pt-0">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="values-box">
                        <h4><i class="fas fa-heart text-danger me-2"></i> Positivitas</h4>
                        <p class="text-muted mt-3 mb-0">Kami mendorong interaksi yang positif dan saling mendukung dalam setiap pesan yang dikirim.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="values-box" style="border-left-color: #fbc2eb;">
                        <h4><i class="fas fa-shield-alt text-success me-2"></i> Privasi</h4>
                        <p class="text-muted mt-3 mb-0">Keamanan dan privasi pengguna adalah prioritas. Surat rahasia kamu aman bersama kami.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="values-box" style="border-left-color: #8A2BE2;">
                        <h4><i class="fas fa-paint-brush text-primary me-2"></i> Kreativitas</h4>
                        <p class="text-muted mt-3 mb-0">Bebaskan ekspresimu dengan berbagai pilihan stiker, warna, dan tema yang menarik.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TEAM SECTION
    <section class="team-section">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-uppercase text-primary fw-bold letter-spacing-2">Di Balik Layar</h6>
                <h2 class="fw-bold">Tim Pengembang</h2>
            </div>

            <div class="row justify-content-center g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="team-card">
                        <img src="https://ui-avatars.com/api/?name=Developer+1&background=a18cd1&color=fff" alt="Dev 1" class="team-avatar">
                        <h5 class="fw-bold">Developer 1</h5>
                        <p class="team-role">Fullstack Developer</p>
                        <p class="small text-muted">Orang di balik logika dan database Wishnotes.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="team-card">
                        <img src="https://ui-avatars.com/api/?name=Developer+2&background=fbc2eb&color=fff" alt="Dev 2" class="team-avatar">
                        <h5 class="fw-bold">Developer 2</h5>
                        <p class="team-role">UI/UX Designer</p>
                        <p class="small text-muted">Menciptakan tampilan antarmuka yang manis dan ramah pengguna.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="team-card">
                        <img src="https://ui-avatars.com/api/?name=Developer+3&background=8A2BE2&color=fff" alt="Dev 3" class="team-avatar">
                        <h5 class="fw-bold">Developer 3</h5>
                        <p class="team-role">Frontend Engineer</p>
                        <p class="small text-muted">Memastikan animasi dan interaksi berjalan mulus.</p>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

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