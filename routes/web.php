<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\wishNoteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SkinController;


Route::get('/pegawai',[PegawaiController::class,'index']);
Route::post('/pegawai/store',[PegawaiController::class,'store']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/show', [DashboardController::class, 'show'])->name('dashboard');

// Route::get('/dashboard',function () {
//     return view('dashboard');
// })->name('dashboard');
Route::get('/login',function () {
    return view('login');
})->name('login')->middleware('guest');
Route::get('/register',function () {
    return view('register');
})->middleware('guest');
Route::get('/lupapw',function () {
    return view('lupapw');
});
Route::get('/profil',function () {
    return view('profil');
});
Route::get('/detail',function () {
    return view('detail');
});
Route::get('/friendlist',function () {
    return view('friendlist');
});
// Route::get('/pohon',function () {
//     return view('pohon');
// });
// Route::get('/mading',function () {
//     return view('mading');
// });
// Route::get('/mailbox',function () {
//     return view('mailbox');
// });


Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::post('/logout', [LogoutController::class, 'index'])->name('logout')->middleware('auth');
Route::post('/wishnote', [WishNoteController::class, 'store'])->name('wishnote.store');

Route::delete('/wishnotes/{id}', [WishNoteController::class, 'destroy'])->name('wishnotes.destroy');

Route::get('/pohon/{id}', [SkinController::class, 'showTree']);
Route::get('/mading/{id}', [SkinController::class, 'showMading']);
Route::get('/mailbox/{id}', [SkinController::class, 'showMailbox']);


