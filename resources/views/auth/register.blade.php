@extends('layouts.app')

@section('title', 'Daftar - Wishnotes')

@push('styles')
<!-- Menggunakan style yang sama dengan Login -->
<style>
    body { background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); height: 100vh; display: flex; align-items: center; justify-content: center; }
    .login-card { background: #fff; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 40px 30px; width: 100%; max-width: 400px; position: relative; overflow: hidden; }
    .login-card::before { content: ''; position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: #fbc2eb; border-radius: 50%; opacity: 0.3; }
    .form-control { border-radius: 50px; padding: 12px 20px; border: 2px solid #eee; background: #f9f9f9; }
    .form-control:focus { background: #fff; border-color: #a18cd1; box-shadow: 0 0 0 4px rgba(161,140,209,0.1); }
</style>
@endpush

@section('content')
<div class="container d-flex justify-content-center">
    <div class="login-card text-center">
        
        <h2 class="fw-bold text-dark mb-4">Buat Akun</h2>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="mb-3 text-start">
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Panggilan" value="{{ old('name') }}" required>
                @error('name') <small class="text-danger ms-2">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3 text-start">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required>
                @error('email') <small class="text-danger ms-2">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3 text-start">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                @error('password') <small class="text-danger ms-2">{{ $message }}</small> @enderror
            </div>

            <div class="mb-4 text-start">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi Password" required>
            </div>

            <button type="submit" class="btn btn-wish w-100 rounded-pill py-2 fw-bold mb-3">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-2">
            <small>Sudah punya akun? <a href="{{ route('login') }}" style="color: #a18cd1; text-decoration: none; font-weight: 700;">Masuk di sini</a></small>
        </div>
    </div>
</div>
@endsection