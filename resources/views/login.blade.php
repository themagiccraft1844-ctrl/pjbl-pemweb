<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Wishnotes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Nunito -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            /* Background gradasi pastel */
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background-color: #ffffff;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            position: relative;
            overflow: hidden;
        }

        /* Dekorasi bulat di pojok */
        .login-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: #fbc2eb;
            border-radius: 50%;
            opacity: 0.3;
        }

        .brand-icon {
            font-size: 60px;
            color: #a18cd1;
            margin-bottom: 10px;
            animation: float 3s ease-in-out infinite;
        }

        h2 { font-weight: 700; color: #4a4a4a; }
        p.subtitle { color: #888; font-size: 0.9rem; margin-bottom: 25px; }

        .form-control {
            border-radius: 50px;
            padding: 12px 20px;
            border: 2px solid #eee;
            background-color: #f9f9f9;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: #a18cd1;
            box-shadow: 0 0 0 4px rgba(161, 140, 209, 0.1);
        }

        /* Tombol Login Utama */
        .btn-wish {
            background: linear-gradient(to right, #a18cd1, #fbc2eb);
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-weight: 700;
            color: white;
            width: 100%;
            transition: transform 0.2s;
        }
        .btn-wish:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 5px 15px rgba(161, 140, 209, 0.3);
        }

        /* Tombol Guest */
        .btn-guest {
            background: #fff;
            border: 2px solid #a18cd1;
            color: #a18cd1;
            border-radius: 50px;
            padding: 10px;
            font-weight: 700;
            width: 100%;
            margin-top: 10px;
            transition: all 0.2s;
        }
        .btn-guest:hover {
            background: #f0ebfa;
            color: #8a6dc5;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 15px 0;
            color: #aaa;
            font-size: 0.8rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #eee;
        }
        .divider:not(:empty)::before { margin-right: .5em; }
        .divider:not(:empty)::after { margin-left: .5em; }

        .input-group-text {
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
</head>
<body>

    <div class="container d-flex justify-content-center">
        <div class="login-card text-center">

            <div class="brand-icon">
                <i class="fa-solid fa-gift"></i>
            </div>
            
            <h2>Wishnotes</h2>
            <p class="subtitle">Kirim doa & harapanmu di sini!</p>

            <!-- error -->
             @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-600 text-red rounded-md">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                @endif

            <form id="loginForm" method="POST" action="{{ route('login.store')}}">
                @csrf
                <div class="mb-3 text-start">
                    <input type="email" class="form-control" id="email" placeholder="Email"  name="email" required>
                </div>

                <div class="mb-3 text-start position-relative">
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
                    <span class="input-group-text" id="togglePassword">
                        <i class="fa-regular fa-eye-slash"></i>
                    </span>
                </div>

                <button type="submit" class="btn btn-wish" id="btnLogin">
                    Masuk Sekarang
                </button>
            </form>

            <div class="divider">ATAU</div>

            <!-- Tombol Guest -->
            <a href="dashboard" class="btn btn-guest" id="btnGuest">
                <i class="fa-solid fa-user-astronaut me-2"></i> Masuk sebagai Tamu
            </a>

            <a href="register" class="btn btn-guest" id="btnGuest">
                <i class="fa-solid fa-user-astronaut me-2"></i> Buat Akun Baru
            </a>

            <div class="mt-4">
                <a href="lupapw" class="text-muted text-decoration-none small">Lupa Password?</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <script>
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

            // Login Logic
            $('#loginForm').submit(function(e) {
            e.preventDefault();
                let btn = $('#btnLogin');
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
                setTimeout(() => window.location.href = "dashboard", 1500);
            });
        });
    </script> -->
</body>
</html>
