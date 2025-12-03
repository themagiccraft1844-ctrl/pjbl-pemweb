<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Wishnotes')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }
        /* Style Global Custom */
        .bg-pastel-gradient {
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
        }
        .btn-wish {
            background: linear-gradient(to right, #a18cd1, #fbc2eb);
            border: none;
            color: white;
            transition: transform 0.2s;
        }
        .btn-wish:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 5px 15px rgba(161, 140, 209, 0.3);
        }
    </style>
    @stack('styles')
</head>
<body>

    @yield('content')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>