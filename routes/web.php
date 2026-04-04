<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// dashboard superadmin
Route::middleware(['auth', RoleMiddleware::class.':superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', fn () => view('superadmin.dashboard'))->name('dashboard');
});

Route::get('/tesrole', function () {
    dd(auth()->user()?->role, auth()->check());
});

// dashboard admin
Route::middleware(['auth', RoleMiddleware::class.':admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
});

// dashboard pelanggan
Route::middleware(['auth', RoleMiddleware::class.':pelanggan'])->group(function () {
    Route::get('/home', fn () => view('pelanggan.home'))->name('pelanggan.home');
});

Route::get('/jadwal', function () {return view('pelanggan.jadwal');});