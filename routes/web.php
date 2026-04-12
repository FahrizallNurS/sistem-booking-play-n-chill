<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\DataUserController;
use App\Http\Controllers\TinjauLaporanController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\ProfilController;

Route::get('/', function () {
    return redirect()->route('pelanggan.home');
});

Route::get('/home', fn () => view('pelanggan.home'))->name('pelanggan.home');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/booking', [BookingController::class, 'index'])->name('booking');

// ================= AUTH =================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');

    Route::get('/lupa-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/lupa-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

// ================= SUPERADMIN (luar auth group) =================
Route::middleware(['auth', RoleMiddleware::class.':superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', fn () => view('superadmin.dashboard'))->name('dashboard');
    Route::get('/data-pengguna', fn () => view('superadmin.datauser'))->name('users');
    Route::get('/laporan', fn () => view('superadmin.tinjau-laporan'))->name('laporan');
    Route::get('/datauser', [DataUserController::class, 'index'])->name('datauser');
});

// ================= AUTH GROUP =================
Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Booking pelanggan
    Route::get('/booking/paket', [BookingController::class, 'paket'])->name('booking.paket');
    Route::get('/booking/form', [BookingController::class, 'form'])->name('booking.form');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/status', [BookingController::class, 'status'])->name('booking.status');
    Route::post('/booking/payment/process', [BookingController::class, 'processToPayment'])->name('booking.payment.process');
    Route::get('/booking/payment', [BookingController::class, 'showPayment'])->name('booking.payment.show');

    // ================= ADMIN =================
    Route::middleware([RoleMiddleware::class.':admin'])->prefix('admin')->name('admin.')->group(function () {
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
        Route::resource('booking', AdminBookingController::class);
        Route::patch('/booking/{id}/konfirmasi', [AdminBookingController::class, 'konfirmasi'])->name('booking.konfirmasi');
        Route::patch('/booking/{id}/tolak', [AdminBookingController::class, 'tolak'])->name('booking.tolak');

        // Konten
        Route::get('/gallery', [AdminGalleryController::class, 'index'])->name('gallery.index');
        Route::get('/game', [GameController::class, 'index'])->name('game.index');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

        // Profil
        Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
        Route::patch('/profil', [ProfilController::class, 'update'])->name('profil.update');
        Route::patch('/profil/password', [ProfilController::class, 'gantiPassword'])->name('profil.password');
    });

    // ================= PELANGGAN =================
    Route::middleware([RoleMiddleware::class.':pelanggan'])->group(function () {
        Route::view('/info-payment', 'pelanggan.payment')->name('payment.info');
        Route::view('/status-booking', 'pelanggan.status-booking')->name('pelanggan.status');
        Route::get('/jadwal', fn () => view('pelanggan.jadwal'))->name('pelanggan.jadwal');
    });

    Route::get('/laporan', [TinjauLaporanController::class, 'index'])->name('laporan.index');
});