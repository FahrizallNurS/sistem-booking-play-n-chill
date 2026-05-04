<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\JadwalController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\TentangKamiController;
use App\Models\MsRuangan;
use App\Models\MsPermainan;

// Admin Controllers...
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ProfilController;

// Auth & Superadmin Controllers...
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Superadmin\SABerandaController;
use App\Http\Controllers\Superadmin\SAProfilController;
use App\Http\Controllers\Superadmin\KelolaUserController;
use App\Http\Controllers\Superadmin\SATinjauLaporanController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('pelanggan.home'));

// Perbaikan Rute Home (Hanya satu rute dengan pengiriman data)
Route::get('/home', function () {
    // 1. Ambil data perangkat unik (PS3, PS4, PS5)
    $perangkats = MsRuangan::where('is_active', 1)
        ->whereNotNull('perangkat')
        ->distinct()
        ->pluck('perangkat');

    // 2. Ambil semua data permainan untuk grid
    $permainans = MsPermainan::all(); 

    return view('pelanggan.home', compact('perangkats', 'permainans'));
})->name('pelanggan.home');

Route::get('/tentang-kami', [TentangKamiController::class, 'index'])->name('tentang-kami');
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

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/home');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi sudah dikirim!');
    })->middleware('throttle:6,1')->name('verification.send');
});
/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Profile & Booking Pelanggan
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Booking
    Route::get('/booking/paket', [BookingController::class, 'paket'])->name('booking.paket');
    Route::get('/booking/form', [BookingController::class, 'form'])->name('booking.form');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::post('/booking/payment/process', [BookingController::class, 'processToPayment'])->name('booking.payment.process');
    Route::get('/booking/status', [BookingController::class, 'status'])->name('booking.status');
    Route::get('/booking/jam-terpakai', [BookingController::class, 'getJamTerpakai']);
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('pelanggan.jadwal');
    Route::post('/booking/checkout', [JadwalController::class, 'checkout'])->name('booking.checkout');

    // Grouping Superadmin
    Route::middleware([RoleMiddleware::class . ':superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SABerandaController::class, 'index'])->name('dashboard');
        Route::resource('data-user', KelolaUserController::class)->names('users');
        Route::patch('/data-user/{id}/password', [KelolaUserController::class, 'gantiPassword'])->name('users.password');
        Route::get('/tinjau-laporan', [SATinjauLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/profil', [SAProfilController::class, 'index'])->name('profil.index');
        Route::patch('/profil', [SAProfilController::class, 'update'])->name('profil.update');
        Route::patch('/profil/password', [SAProfilController::class, 'gantiPassword'])->name('profil.password');
    });

    // Grouping Admin
    Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
        Route::resource('paket', PaketController::class);
        Route::get('/kategori/{kategori}/ruangan', [PaketController::class, 'getRuanganByKategori'])->name('kategori.ruangan');
        Route::resource('layanan', LayananController::class);
        Route::post('/layanan/{id}/penetapan-harga', [LayananController::class, 'storePenetapanHarga'])->name('layanan.penetapan.store');
        Route::delete('/penetapan-harga/{id}', [LayananController::class, 'destroyPenetapanHarga'])->name('layanan.penetapan.destroy');
        Route::resource('booking', AdminBookingController::class);
        Route::patch('/booking/{id}/konfirmasi', [AdminBookingController::class, 'konfirmasi'])->name('booking.konfirmasi');
        Route::patch('/booking/{id}/tolak', [AdminBookingController::class, 'tolak'])->name('booking.tolak');
        Route::patch('/booking/{id}/pembayaran', [AdminBookingController::class, 'pembayaran'])->name('booking.pembayaran');
        Route::patch('/booking/{id}/selesai', [AdminBookingController::class, 'selesai'])->name('booking.selesai');
        Route::resource('game', GameController::class);
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
        Route::patch('/profil', [ProfilController::class, 'update'])->name('profil.update');
        Route::patch('/profil/password', [ProfilController::class, 'gantiPassword'])->name('profil.password');
    });

    // Pelanggan Khusus
    Route::middleware([RoleMiddleware::class . ':pelanggan'])->group(function () {
        Route::get('/booking/payment/{id}', [BookingController::class, 'showPayment'])->name('booking.payment.show');
    });
});