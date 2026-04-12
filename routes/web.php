<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Superadmin\SABerandaController;
use App\Http\Controllers\Superadmin\SAProfilController;
use App\Http\Controllers\Superadmin\KelolaUserController;
use App\Http\Controllers\Superadmin\SATinjauLaporanController;

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

// ================= AUTH USER =================
Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');


    // Booking
    Route::get('/booking/paket',  [BookingController::class, 'paket'])->name('booking.paket');
    Route::get('/booking/form',   [BookingController::class, 'form'])->name('booking.form');

    Route::get('/booking/paket', [BookingController::class, 'paket'])->name('booking.paket');
    Route::get('/booking/form', [BookingController::class, 'form'])->name('booking.form');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/status', [BookingController::class, 'status'])->name('booking.status');

    Route::get('/laporan', [TinjauLaporanController::class, 'index'])->name('laporan.index');

    // ================= SUPERADMIN =================
    Route::middleware([RoleMiddleware::class.':superadmin'])
        ->prefix('superadmin')
        ->name('superadmin.')
        ->group(function () {
            // Halaman Utama Dashboard
        Route::get('/beranda', [SABerandaController::class, 'index'])->name('beranda');
        Route::get('/profil', [SAProfilController::class, 'index'])->name('profil.index');
        Route::get('/data-user', [KelolaUserController::class, 'index'])->name('users.index');
        Route::patch('/data-user/{id}', [KelolaUserController::class, 'update'])->name('users.update');
        // Tambahkan rute ini jika nanti ingin buat fitur tambah/edit user:
        // Route::get('/data-user/create', [KelolaUserController::class, 'create'])->name('users.create');
        // Route::post('/data-user/store', [KelolaUserController::class, 'store'])->name('users.store');
        Route::get('/tinjau-laporan', [SATinjauLaporanController::class, 'index'])->name('laporan.index');
    });

    // ================= ADMIN =================
    Route::middleware([RoleMiddleware::class.':admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');

            Route::resource('paket', PaketController::class);
            Route::get('/kategori/{id}/ruangan', [PaketController::class, 'getRuanganByKategori'])->name('kategori.ruangan');

            Route::resource('layanan', LayananController::class);
            Route::post('/layanan/{id}/pricing', [LayananController::class, 'storePricing'])->name('layanan.pricing.store');
            Route::delete('/pricing/{id}', [LayananController::class, 'destroyPricing'])->name('layanan.pricing.destroy');

            Route::resource('booking', AdminBookingController::class);
            Route::patch('/booking/{id}/konfirmasi', [AdminBookingController::class, 'konfirmasi'])->name('booking.konfirmasi');
            Route::patch('/booking/{id}/tolak', [AdminBookingController::class, 'tolak'])->name('booking.tolak');

            Route::get('/gallery', [AdminGalleryController::class, 'index'])->name('gallery.index');
            Route::get('/game', [GameController::class, 'index'])->name('game.index');
            Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

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