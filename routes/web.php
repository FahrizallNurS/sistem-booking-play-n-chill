<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\DataUserController;


Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');

    // Register (dari branch temen)
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Superadmin
Route::middleware(['auth', RoleMiddleware::class.':superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', fn () => view('superadmin.dashboard'))->name('dashboard');
});
    //data user
    Route::get('/superadmin/datauser', [DataUserController::class, 'index'])->name('superadmin.datauser');

// Admin
Route::middleware(['auth', RoleMiddleware::class.':admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');

    // Paket
    Route::resource('paket', PaketController::class);
    Route::get('/kategori/{id}/ruangan', [PaketController::class, 'getRuanganByKategori'])->name('kategori.ruangan');

    // Layanan
    Route::resource('layanan', LayananController::class);
    Route::post('/layanan/{id}/pricing', [LayananController::class, 'storePricing'])->name('layanan.pricing.store');
    Route::delete('/pricing/{id}', [LayananController::class, 'destroyPricing'])->name('layanan.pricing.destroy');

    // Booking
    Route::resource('booking', BookingController::class);
    Route::patch('/booking/{id}/konfirmasi', [BookingController::class, 'konfirmasi'])->name('booking.konfirmasi');
    Route::patch('/booking/{id}/tolak', [BookingController::class, 'tolak'])->name('booking.tolak');

    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::get('/game', [GameController::class, 'index'])->name('game.index');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

// Pelanggan
Route::middleware(['auth', RoleMiddleware::class.':pelanggan'])->group(function () {
    Route::get('/home', fn () => view('pelanggan.home'))->name('pelanggan.home');
}); 