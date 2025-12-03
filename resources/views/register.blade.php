<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Wishnotes</title>
    
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

        .register-card {
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
        .register-card::before {
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
            margin-bottom: 10px;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }

        h2 { font-weight: 700; color: #4a4a4a; margin-bottom: 5px; }
        p.subtitle { color: #888; font-size: 0.9rem; margin-bottom: 30px; }

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
            margin-bottom: 1rem;
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
        
        /* Ubah warna icon saat input fokus */
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
        }
        .btn-wish:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 5px 15px rgba(161, 140, 209, 0.3);
            background: linear-gradient(to right, #967Ec7, #f0b2e0);
        }
        .btn-wish:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Tombol Link (Outline Style) */
        .btn-link-custom {
            background: #fff;
            border: 2px solid #a18cd1;
            color: #a18cd1;
            border-radius: 50px;
            padding: 10px;
            font-weight: 700;
            width: 100%;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }
        .btn-link-custom:hover {
            background: #f0ebfa;
            color: #8a6dc5;
            border-color: #8a6dc5;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
            color: #aaa;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #eee;
        }
        .divider:not(:empty)::before { margin-right: .5em; }
        .divider:not(:empty)::after { margin-left: .5em; }

        .input-group-text-toggle {
            background: transparent; border: none; position: absolute;
            right: 15px; top: 50%; transform: translateY(-50%);
            cursor: pointer; color: #aaa; z-index: 10;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }
    </style>
<script type="importmap">
</script>
</head>
<body>

    <div class="register-card text-center">
        
        <div class="brand-icon">
            <i class="fa-solid fa-gift"></i>
        </div>
        
        <h2>Daftar Akun</h2>
        <p class="subtitle">Bergabunglah dan bagikan harapanmu!</p>

        @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-600 text-red rounded-md">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                @endif

                
        <form id="registerForm" method="POST" action="{{ route('register.store')}}">
            @csrf
            <!-- Nama Lengkap -->
            <div class="input-group">
                <span class="input-icon"><i class="fa-regular fa-user"></i></span>
                <input type="text" class="form-control" id="name" placeholder="Nama Lengkap" name="name" required>
            </div>

            <!-- Email -->
            <div class="input-group">
                <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                <input type="email" class="form-control" id="email" placeholder="Alamat Email" name="email" required>
            </div>

            <!-- Password -->
            <div class="input-group position-relative">
                <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                <input type="password" class="form-control" id="password" placeholder="Buat Password" name="password" required>
                <span class="input-group-text-toggle" id="togglePassword">
                    <i class="fa-regular fa-eye-slash"></i>
                </span>
            </div>

            <button type="submit" class="btn btn-wish" id="btnRegister">
                Daftar Sekarang <i class="fa-solid fa-arrow-right ms-2"></i>
            </button>
        </form>

        <div class="divider">SUDAH PUNYA AKUN?</div>

        <!-- Tombol ke Login -->
        <a href="login" class="btn-link-custom" id="btnLogin">
            Masuk Disini
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!--    
    <script>
        $(document).ready(function() {
            // Show/Hide Password
            $('#togglePassword').click(function() {
                let input = $('#password');
                let icon = $(this).find('i');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });

            // Register Logic
            $('#registerForm').submit(function(e) {
                e.preventDefault();
                let btn = $('#btnRegister');
                let originalText = btn.html();
                
                // Animasi Loading
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
                
                // Simulasi API call
                setTimeout(() => {
                    let name = $('#fullname').val();
                    alert(`Selamat datang, ${name}! Akun Anda berhasil dibuat.`);
                    btn.prop('disabled', false).html(originalText);
                    // Reset form
                    $('#registerForm')[0].reset();
                }, 1500);
            });
        });
    </script> -->
</body>
</html>