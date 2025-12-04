@extends('layouts.app')

@section('title', 'Masuk - Wishnotes')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-card {
        background: #fff;
        border-radius: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 40px 30px;
        width: 100%;
        max-width: 400px;
        position: relative;
        overflow: hidden;
    }
    .login-card::before {
        content: ''; position: absolute; top: -50px; right: -50px;
        width: 150px; height: 150px; background: #fbc2eb;
        border-radius: 50%; opacity: 0.3;
    }
    .form-control {
        border-radius: 50px; padding: 12px 20px; border: 2px solid #eee; background: #f9f9f9;
    }
    .form-control:focus {
        background: #fff; border-color: #a18cd1; box-shadow: 0 0 0 4px rgba(161,140,209,0.1);
    }
    .brand-icon {
        font-size: 60px; color: #a18cd1; margin-bottom: 10px;
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
</style>
@endpush

@section('content')
<div class="container d-flex justify-content-center">
    <div class="login-card text-center">
        
        <div class="brand-icon"><i class="fa-solid fa-gift"></i></div>
        <h2 class="fw-bold text-dark">Wishnotes</h2>
        <p class="text-muted mb-4">Kirim doa & harapanmu di sini!</p>

        <!-- Alert Error -->
        @if ($errors->any())
            <div class="alert alert-danger py-2 small rounded-pill">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" id="loginForm">
            @csrf
            <div class="mb-3 text-start">
                <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3 text-start position-relative">
                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted" style="cursor:pointer" id="togglePassword">
                    <i class="fa-regular fa-eye-slash"></i>
                </span>
            </div>

            <button type="submit" class="btn btn-wish w-100 rounded-pill py-2 fw-bold mb-3">
                Masuk Sekarang
            </button>
        </form>

        <div class="d-flex align-items-center mb-3">
            <hr class="flex-grow-1"> <span class="mx-2 text-muted small">ATAU</span> <hr class="flex-grow-1">
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary w-100 rounded-pill py-2 fw-bold mb-3">
            <i class="fa-solid fa-user-astronaut me-2"></i> Masuk sebagai Tamu
        </a>

        <div class="mt-2">
            <small>Belum punya akun? <a href="{{ route('register') }}" style="color: #a18cd1; text-decoration: none; font-weight: 700;">Daftar dulu yuk</a></small>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
</script>
@endpush