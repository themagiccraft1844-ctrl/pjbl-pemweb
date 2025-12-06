<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WishNoteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SkinController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminNoteController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\TreeController;
use App\Http\Controllers\AdminExportController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. Halaman Guest (Hanya bisa diakses jika BELUM login) ---
Route::middleware('guest')->group(function () {
    // Login & Register
    Route::view('/', 'index')->name('index');
    Route::view('/about', 'about')->name('about');
    Route::view('/login', 'login')->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::view('/register', 'register')->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::view('/lupapw', 'lupapw');
});

// --- 2. Halaman Auth (Hanya bisa diakses jika SUDAH login) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'index'])->name('logout');
    
    // WishNote Actions (Membuat & Menghapus Catatan)
    Route::post('/wishnote', [WishNoteController::class, 'store'])->name('wishnote.store');
    Route::delete('/wishnotes/{id}', [WishNoteController::class, 'destroy'])->name('wishnotes.destroy');
});

// --- 3. Dashboard & Tampilan ---
Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'index')->name('dashboard');
    Route::get('/show', 'show')->name('dashboard.show'); 
});

// --- 4. Halaman Statis / Umum ---
Route::view('/profil', 'profil');
Route::view('/detail', 'detail');
Route::view('/friendlist', 'friendlist');
Route::view('/maintenance', 'maintenance')->name('maintenance');

// --- 5. Skin Routes (Pohon, Mading, Mailbox) ---
Route::controller(SkinController::class)->group(function () {
    Route::get('/tree/{id}', 'showTree');
    Route::get('/mading/{id}', 'showMading');
    Route::get('/mailbox/{id}', 'showMailbox');
});


Route::middleware('auth')->group(function () {
    Route::get('/friendlist', [FriendController::class, 'index'])->name('friendlist');
    Route::post('/friend/add/{id}', [FriendController::class, 'addFriend'])->name('friend.add');
    Route::delete('/friend/remove/{id}', [FriendController::class, 'removeFriend'])->name('friend.remove');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [UserController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [UserController::class, 'updateAvatar'])->name('profile.avatar');
    // Route settings jika diperlukan, atau digabung ke update profile
});




// --- ROUTE KHUSUS ADMIN ---
// Middleware 'auth' = harus login
// Middleware 'admin' = harus punya role admin (yang baru kita buat)
Route::middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');


    // Export Routes
    Route::get('/admin/export/excel', [AdminExportController::class, 'exportExcel'])->name('admin.export.excel');
    Route::get('/admin/export/pdf', [AdminExportController::class, 'exportPdf'])->name('admin.export.pdf');

    // kelola catetan yang ini rutenya
    Route::get('/admin/notes', [AdminNoteController::class, 'index'])->name('admin.notes.index');
    Route::delete('/admin/notes/{id}', [AdminNoteController::class, 'destroy'])->name('admin.notes.destroy');

    // kalo  ini kelola user
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // TAMBAHAN: UPDATE ROLE
    Route::patch('/admin/users/{id}/promote', [AdminUserController::class, 'makeAdmin'])->name('admin.users.promote');
    Route::patch('/admin/users/{id}/demote', [AdminUserController::class, 'removeAdmin'])->name('admin.users.demote');

    // VIEW MESSAGES & DELETE
    Route::get('/admin/notes/{id}/messages', [AdminNoteController::class, 'show'])->name('admin.notes.show');
    Route::delete('/admin/messages/{id}', [AdminNoteController::class, 'deleteMessage'])->name('admin.messages.destroy');
    
    // WARN USER
    Route::post('/admin/users/warn', [AdminNoteController::class, 'warnUser'])->name('admin.users.warn');

    //apdet tema
    Route::post('/user/update-theme', [UserController::class, 'updateTheme'])->name('user.update-theme');
});


Route::post('/pohon/store', [TreeController::class, 'store'])->name('pohon.store');

// Hapus pesan
Route::post('/pohon/delete', [TreeController::class, 'destroy'])->name('pohon.delete');

// Like pohon
Route::post('/pohon/like', [TreeController::class, 'toggleLike'])->name('pohon.like');