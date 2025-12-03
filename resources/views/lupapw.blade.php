<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishnotes - Lupa Password</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Nunito -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .auth-card {
            background-color: #ffffff;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            position: relative;
            overflow: hidden;
        }

        /* Dekorasi bulat di pojok */
        .auth-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: #fbc2eb;
            border-radius: 50%;
            opacity: 0.3;
            pointer-events: none;
        }

        .brand-icon {
            font-size: 60px;
            color: #a18cd1;
            margin-bottom: 20px;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }

        h2 { font-weight: 700; color: #4a4a4a; margin-bottom: 10px; }
        p.subtitle { color: #888; font-size: 0.95rem; margin-bottom: 30px; line-height: 1.5; }

        .form-control {
            border-radius: 50px;
            padding: 12px 20px;
            padding-left: 45px; /* Space for icon */
            border: 2px solid #eee;
            background-color: #f9f9f9;
            transition: all 0.3s;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: #a18cd1;
            box-shadow: 0 0 0 4px rgba(161, 140, 209, 0.1);
        }

        /* Style khusus untuk icon di dalam input */
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            z-index: 10;
            font-size: 1.1rem;
            transition: color 0.3s;
        }
        
        .form-control:focus + .input-icon,
        .form-control:focus ~ .input-icon, 
        .input-group:focus-within .input-icon {
            color: #a18cd1;
        }

        /* Tombol Utama */
        .btn-wish {
            background: linear-gradient(to right, #a18cd1, #fbc2eb);
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-weight: 700;
            color: white;
            width: 100%;
            transition: transform 0.2s;
            margin-top: 10px;
            box-shadow: 0 4px 6px rgba(161, 140, 209, 0.2);
        }
        .btn-wish:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 6px 15px rgba(161, 140, 209, 0.3);
            background: linear-gradient(to right, #967Ec7, #f0b2e0);
        }
        .btn-wish:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            margin-top: 25px;
            color: #aaa;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #a18cd1;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }
    </style>

</head>
<body>

    <div class="auth-card text-center">
        
        <div class="brand-icon">
            <i class="fa-solid fa-unlock-keyhole"></i>
        </div>

        <h2>Lupa Password?</h2>
        <p class="subtitle">Jangan khawatir! Masukkan emailmu di bawah ini dan kami akan mengirimkan instruksi reset password.</p>

        <form id="forgotForm">
            <div class="input-group text-start">
                <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                <input type="email" class="form-control" id="forgotEmail" placeholder="Masukkan Email Anda" required>
            </div>

            <button type="submit" class="btn btn-wish">
                Kirim Link Reset <i class="fa-regular fa-paper-plane ms-2"></i>
            </button>
        </form>

        <a href="login" class="back-link" id="btnBack">
            <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Login
        </a>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Forgot Password Logic
            $('#forgotForm').submit(function(e) {
                e.preventDefault();
                let email = $('#forgotEmail').val();
                let btn = $(this).find('button[type="submit"]');
                let originalText = btn.html();
                
                // Animasi Loading
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengirim...');
                
                // Simulasi Proses Server
                setTimeout(() => {
                    alert(`Link reset password telah dikirim ke ${email}. Silakan cek kotak masuk Anda!`);
                    btn.prop('disabled', false).html(originalText);
                    $('#forgotEmail').val(''); // Reset input
                }, 1500);
            });
         });
    </script>
</body>
</html>