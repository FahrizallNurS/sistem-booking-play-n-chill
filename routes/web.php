<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', function () {
    return view('welcome');
});

// --- Auth Routes ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --- Test Role ---
Route::get('/tesrole', function () {
    dd(auth()->user()?->role, auth()->check());
});

// --- Dashboard Superadmin ---
Route::middleware(['auth', RoleMiddleware::class.':superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', fn () => view('superadmin.dashboard'))->name('dashboard');
});

// --- Dashboard Admin ---
Route::middleware(['auth', RoleMiddleware::class.':admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
});

// --- Fitur Pelanggan (SEMUA DI SINI) ---
Route::middleware(['auth', RoleMiddleware::class.':pelanggan'])->group(function () {});
    
    // Halaman Utama Pelanggan
    Route::get('/home', fn () => view('pelanggan.home'))->name('pelanggan.home');

    // Halaman Informasi Pembayaran (Yang dipanggil tombol 'Lanjutkan')
    Route::view('/info-payment', 'pelanggan.payment')->name('payment.info');

    // Halaman Status Booking
    Route::view('/status-booking', 'pelanggan.status-booking')->name('pelanggan.status');

    // Contoh rute booking jika nanti dibutuhkan
    // Route::get('/booking', fn () => view('pelanggan.booking'))->name('booking.form');
