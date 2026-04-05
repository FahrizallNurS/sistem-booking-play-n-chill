<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Middleware\RoleMiddleware;

// Redirect root ke /home
Route::get('/', function () {
    return redirect('/home');
});

// Beranda utama (bisa diakses siapa saja, login atau tidak)
Route::get('/', fn () => view('pelanggan.home'))->name('home');
Route::get('/home', fn () => view('pelanggan.home'))->name('pelanggan.home');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Routes yang butuh login
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Public routes
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

// Auth routes (hanya untuk guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Pelanggan routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', fn () => view('pelanggan.home'))->name('pelanggan.home');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin routes
Route::middleware(['auth', RoleMiddleware::class.':admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
});

// Superadmin routes
Route::middleware(['auth', RoleMiddleware::class.':superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', fn () => view('superadmin.dashboard'))->name('dashboard');
});

// Cek role (hapus setelah selesai development)
Route::get('/tesrole', function () {
    dd(Auth::user()?->role, Auth::check());
});

Route::get('/booking', [BookingController::class, 'index'])->name('booking');

Route::middleware(['auth'])->group(function () {
    Route::get('/booking/form',   [BookingController::class, 'form'])->name('booking.form');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/status', [BookingController::class, 'status'])->name('booking.status');
});