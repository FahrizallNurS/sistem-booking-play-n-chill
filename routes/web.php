<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\JadwalController;
use App\Http\Middleware\RoleMiddleware;

// Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ProfilController;

// Auth
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Superadmin
use App\Http\Controllers\Superadmin\SABerandaController;
use App\Http\Controllers\Superadmin\SAProfilController;
use App\Http\Controllers\Superadmin\KelolaUserController;
use App\Http\Controllers\Superadmin\SATinjauLaporanController;


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('pelanggan.home'));

Route::get('/home', fn () => view('pelanggan.home'))->name('pelanggan.home');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/booking', [BookingController::class, 'index'])->name('booking');


/*
|--------------------------------------------------------------------------
| AUTH (GUEST)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    Route::get('/lupa-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/lupa-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');


    /*
    |--------------------------------------------------------------------------
    | BOOKING (PELANGGAN)
    |--------------------------------------------------------------------------
    */

    Route::get('/booking/paket', [BookingController::class, 'paket'])->name('booking.paket');
    Route::get('/booking/form', [BookingController::class, 'form'])->name('booking.form');

    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

    Route::get('/jadwal', [JadwalController::class, 'index'])->name('pelanggan.jadwal');
    Route::post('/booking/checkout', [JadwalController::class, 'checkout'])->name('booking.checkout');

    Route::post('/booking/payment/process', [BookingController::class, 'processToPayment'])->name('booking.payment.process');
    Route::get('/booking/payment', [BookingController::class, 'showPayment'])->name('booking.payment.show');

    Route::get('/booking/status', [BookingController::class, 'status'])->name('booking.status');

});
    /*
    |--------------------------------------------------------------------------
    | SUPERADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware([RoleMiddleware::class . ':superadmin'])
        ->prefix('superadmin')
        ->name('superadmin.')
        ->group(function () {

            Route::get('/dashboard', [SABerandaController::class, 'index'])->name('dashboard');

            Route::get('/profil', [SAProfilController::class, 'index'])->name('profil.index');

            Route::get('/data-user', [KelolaUserController::class, 'index'])->name('users.index');
            Route::patch('/data-user/{id}', [KelolaUserController::class, 'update'])->name('users.update');

            Route::get('/tinjau-laporan', [SATinjauLaporanController::class, 'index'])->name('laporan.index'); // FIXED


            Route::get('/data-user', [KelolaUserController::class, 'index'])->name('users.index');
            Route::get('/data-user/create', [KelolaUserController::class, 'create'])->name('users.create');
            Route::post('/data-user', [KelolaUserController::class, 'store'])->name('users.store');
            Route::get('/data-user/{id}/edit', [KelolaUserController::class, 'edit'])->name('users.edit');
            Route::patch('/data-user/{id}', [KelolaUserController::class, 'update'])->name('users.update');
            Route::delete('/data-user/{id}', [KelolaUserController::class, 'destroy'])->name('users.destroy');
            Route::patch('/data-user/{id}/password', [KelolaUserController::class, 'gantiPassword'])->name('users.password');

            Route::get('/profil', [SAProfilController::class, 'index'])->name('profil.index');
            Route::patch('/profil', [SAProfilController::class, 'update'])->name('profil.update');
            Route::patch('/profil/password', [SAProfilController::class, 'gantiPassword'])->name('profil.password');
        });


    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware([RoleMiddleware::class . ':admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');

            // Paket
            Route::resource('paket', PaketController::class);
            Route::get('/kategori/{kategori}/ruangan', [PaketController::class, 'getRuanganByKategori'])->name('kategori.ruangan');

            // Layanan
            Route::resource('layanan', LayananController::class);
            Route::post('/layanan/{id}/penetapan-harga', [LayananController::class, 'storePenetapanHarga'])->name('layanan.penetapan.store');
            Route::delete('/penetapan-harga/{id}', [LayananController::class, 'destroyPenetapanHarga'])->name('layanan.penetapan.destroy');

            //booking
            Route::resource('booking', AdminBookingController::class);
            Route::patch('/booking/{id}/konfirmasi', [AdminBookingController::class, 'konfirmasi'])->name('booking.konfirmasi');
            Route::patch('/booking/{id}/tolak', [AdminBookingController::class, 'tolak'])->name('booking.tolak');
            Route::patch('/booking/{id}/pembayaran', [AdminBookingController::class, 'pembayaran'])->name('booking.pembayaran');
            Route::patch('/booking/{id}/selesai', [AdminBookingController::class, 'selesai'])->name('booking.selesai');

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


    /*
    |--------------------------------------------------------------------------
    | PELANGGAN ONLY
    |--------------------------------------------------------------------------
    */

    Route::middleware([RoleMiddleware::class . ':pelanggan'])->group(function () {
        Route::view('/info-payment', 'pelanggan.payment')->name('payment.info');
        Route::view('/status-booking', 'pelanggan.status-booking')->name('pelanggan.status');
    });