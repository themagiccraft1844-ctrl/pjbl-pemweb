<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedang Dalam Perbaikan - Wishnotes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin: 0;
        }

        .construction-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
            position: relative;
            z-index: 10;
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: #f0e6ff;
            color: #8A2BE2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 3rem;
            animation: float 3s ease-in-out infinite;
        }

        h1 {
            color: #333;
            font-weight: 800;
            margin-bottom: 15px;
        }

        p {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn-home {
            background: linear-gradient(to right, #a18cd1, #fbc2eb);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.3s, box-shadow 0.3s;
            display: inline-block;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(161, 140, 209, 0.4);
            color: white;
        }

        /* Floating Animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        /* Background Decorations */
        .bg-shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 1;
        }
        .shape-1 { width: 300px; height: 300px; top: -100px; left: -100px; }
        .shape-2 { width: 200px; height: 200px; bottom: -50px; right: -50px; }
    </style>
</head>
<body>

    <!-- Background Shapes -->
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="construction-card">
        <div class="icon-wrapper">
            <i class="fas fa-tools"></i>
        </div>
        
        <h1>Sedang Dibangun</h1>
        <p>
            Halaman ini sedang dalam tahap pengembangan atau perbaikan. 
            Kami sedang bekerja keras untuk memberikan pengalaman terbaik bagi Anda.
            Silakan kembali lagi nanti!
        </p>

        <a href="{{ route('dashboard') }}" class="btn-home">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
        </a>
    </div>

</body>
</html>