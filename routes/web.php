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
use App\Models\User;
use App\Http\Controllers\HomeController;

// Admin Controllers...
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\Fb\ProdukController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\Fb\AdminFbController;

// Auth & Superadmin Controllers...
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Superadmin\SABerandaController;
use App\Http\Controllers\Superadmin\SAProfilController;
use App\Http\Controllers\Superadmin\KelolaUserController;
use App\Http\Controllers\Superadmin\SATinjauLaporanController;
use App\Http\Controllers\Superadmin\AnalisisPendapatanController;
use App\Http\Controllers\Superadmin\ProdukLayananController;
use App\Http\Controllers\Superadmin\ProdukFnbController;
use App\Http\Controllers\Superadmin\PenjualanKasirController;
use App\Http\Controllers\Superadmin\MetodePembayaranController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        return match (\Illuminate\Support\Facades\Auth::user()->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin'      => redirect()->route('admin.dashboard'),
            default      => redirect()->route('pelanggan.home'),
        };
    }
    return redirect()->route('pelanggan.home');
});

Route::get('/home', function () {
    $perangkats = \App\Models\MsRuangan::where('is_active', 1)
        ->whereNotNull('perangkat')
        ->distinct()
        ->pluck('perangkat');

    $permainans = \App\Models\MsPermainan::all(); 
    
    $banners = \App\Models\Galeri::where('kategori', 'banner')
        ->where('is_active', 1)
        ->orderBy('id_galeri', 'DESC') 
        ->get();

    $videos = \App\Models\Video::where('is_active', 1)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('pelanggan.home', compact('perangkats', 'permainans', 'banners', 'videos'));

})->name('pelanggan.home');


//Route untuk menampilkan halaman "Tentang Kami"
Route::get('/tentang-kami', [TentangKamiController::class, 'index'])->name('tentang-kami');

// Route Galeri
Route::get('/galeri', [GalleryController::class, 'index'])->name('galeri');

// Route Menu F&B
Route::get('/menu-fb', function () {
    $produks = \App\Models\MsProduk::with('subKategori')->where('is_active', 1)->get();
    return view('pelanggan.menu-fb', compact('produks'));
});

// Rute untuk Skenario 3: Pelanggan HANYA pesan Menu F&B
Route::post('/checkout-fb', [App\Http\Controllers\BookingController::class, 'checkoutFb']);
Route::get('/payment-fb/{id}', [App\Http\Controllers\BookingController::class, 'showPaymentFb'])->name('fb.payment.show');
Route::get('/payment-fb/confirm/{id}', [App\Http\Controllers\BookingController::class, 'confirmPaymentFb'])->name('fb.payment.confirm');

// Route Penawaran F&B
Route::get('/booking/penawaran-fb', function () {
    $produks = \App\Models\MsProduk::with('subKategori')->where('is_active', 1)->get();
    return view('pelanggan.penawaran-fb', compact('produks'));
});

Route::get('/aktivasi-akun', function () {
    return view('auth.verify-email');
})->name('aktivasi.notice');

Route::post('/aktivasi-akun/kirim-ulang', function (\Illuminate\Http\Request $request) {
    $email = session('pending_verification_email');

    if (!$email) {
        return back()->withErrors(['error' => 'Session expired. Silakan daftar ulang.']);
    }

    $user = \App\Models\User::where('email', $email)
                ->whereNull('email_verified_at')
                ->first();

    if (!$user) {
        return back()->withErrors(['error' => 'Email tidak ditemukan atau sudah diverifikasi.']);
    }

    \Illuminate\Support\Facades\Auth::login($user);
    $user->sendEmailVerificationNotification();
    \Illuminate\Support\Facades\Auth::logout();

    return back()->with('success', 'Email verifikasi sudah dikirim ulang!');
})->middleware('throttle:3,1')->name('aktivasi.kirim-ulang');

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

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Http\Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);

    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Link verifikasi tidak valid.');
    }

    if ($request->hasValidSignature() === false) {
        abort(403, 'Link verifikasi sudah expired.');
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect()->route('login')
        ->with('success', 'Akun berhasil diaktifkan! Silakan login.');
})->middleware('signed')->name('verification.verify');

Route::get('/check-verification', function () {
    $email = session('pending_verification_email');

    if (!$email) {
        return response()->json([
            'verified' => false
        ]);
    }

    $user = \App\Models\User::where('email', $email)->first();

    return response()->json([
        'verified' => $user && $user->hasVerifiedEmail()
    ]);
});

/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION
|--------------------------------------------------------------------------
*/
    Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

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
        Route::patch('/data-user/{id}/toggle-status', [KelolaUserController::class, 'toggleStatus'])->name('users.toggle-status'); 

        //Laporan Superadmin
        Route::get('/tinjau-laporan', [SATinjauLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/tinjau-laporan/export-pdf', [SATinjauLaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
        Route::get('/laporan/export-excel', [\App\Http\Controllers\Superadmin\SATinjauLaporanController::class, 'exportExcel'])
        ->name('laporan.export-excel');
        Route::get('/profil', [SAProfilController::class, 'index'])->name('profil.index');
        Route::patch('/profil', [SAProfilController::class, 'update'])->name('profil.update');
        Route::patch('/profil/password', [SAProfilController::class, 'gantiPassword'])->name('profil.password');

        // Analisis Pendapatan
        Route::get('/analisis-pendapatan', [AnalisisPendapatanController::class, 'pendapatan'])
            ->name('analisis-pendapatan');
        
        // Halaman Produk Layanan
        Route::get('/produk-layanan', [ProdukLayananController::class, 'index'])
        ->name('produk-layanan');

        // Halaman Produk F&B
        Route::get('/produk-fnb', [ProdukFnbController::class, 'index'])
        ->name('produk-fnb');

        //halaman Penjualan per Kasir
        Route::get('/penjualan-kasir', [PenjualanKasirController::class, 'index'])
        ->name('penjualan-kasir');

        // Metode Pembayaran
        Route::get('/metode-pembayaran', [MetodePembayaranController::class, 'index'])
        ->name('metode-pembayaran');

        Route::get('/{slug}', function ($slug) {
            $judulMap = [
                'analisis-pendapatan' => 'Analisis Pendapatan',
                'produk-layanan'      => 'Produk Layanan',
                'produk-fb'           => 'Produk F&B',
                'penjualan-kasir'     => 'Penjualan per Kasir',
                'metode-pembayaran'   => 'Metode Pembayaran',
            ];

            abort_unless(array_key_exists($slug, $judulMap), 404);

            return view('superadmin.analitik.segera-hadir', [
                'judul' => $judulMap[$slug],
            ]);
            })->name('segera-hadir');
        });

        

   // Grouping Admin
        Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
        
        // Pengaturan (Wifi, dll)
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::patch('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

        // Strukk
       Route::get('/fb/transaksi/cetak-struk/{id}', [AdminFbController::class, 'cetakStruk'])->name('fb.transaksi.cetak-struk');

        
        Route::delete('/paket/penetapan/{id}', [PaketController::class, 'destroyPenetapan'])
            ->name('paket.penetapan.destroy');
        Route::patch('/paket/penetapan/{id}', [PaketController::class, 'updatePenetapanInline'])
            ->name('paket.penetapan.update');
        Route::post('/paket/{id}/penetapan-harga', [PaketController::class, 'storePenetapanHarga'])
            ->name('paket.penetapan.store');
        Route::patch('/paket/{id}/toggle-aktif', [PaketController::class, 'toggleAktif'])
            ->name('paket.toggle-aktif');
        Route::post('/paket/{id}/penetapan-modal', [PaketController::class, 'storePenetapanModal'])
             ->name('paket.penetapan.storeModal');
                
        Route::resource('paket', PaketController::class);
                
        Route::resource('layanan', LayananController::class)->except(['show']);
        Route::patch('/layanan/{id}/toggle-aktif', [LayananController::class, 'toggleAktif'])
            ->name('layanan.toggle-aktif');    
        Route::get('/booking/paket-by-ruangan', [AdminBookingController::class, 'getPaketByRuangan'])
            ->name('booking.paket-by-ruangan');

        Route::get('/booking', [AdminBookingController::class, 'index'])->name('booking.index');
 
        Route::get('/booking/create', [AdminBookingController::class, 'create'])->name('booking.create');
        Route::get('/booking/penetapan-harga', [AdminBookingController::class, 'getPenetapanHarga'])
            ->name('booking.penetapan-harga');
        Route::post('/booking/manual', [AdminBookingController::class, 'storeManual'])
            ->name('booking.manual.store');
        Route::get('/booking/cek-jadwal', [AdminBookingController::class, 'cekJadwal'])->name('booking.cek-jadwal');

        Route::get('/booking/{id}', [AdminBookingController::class, 'show'])->name('booking.show');
        Route::delete('/booking/{id}', [AdminBookingController::class, 'destroy'])->name('booking.destroy');
        Route::patch('/booking/{id}/konfirmasi', [AdminBookingController::class, 'konfirmasi'])->name('booking.konfirmasi');
        Route::patch('/booking/{id}/tolak', [AdminBookingController::class, 'tolak'])->name('booking.tolak');
        Route::patch('/booking/{id}/pembayaran', [AdminBookingController::class, 'pembayaran'])->name('booking.pembayaran');
        Route::patch('/booking/{id}/selesai', [AdminBookingController::class, 'selesai'])->name('booking.selesai');
        Route::patch('/booking/{id}/batalkan', [AdminBookingController::class, 'batalkan'])->name('booking.batalkan');
        Route::patch('/booking/{id}/ubah-jadwal', [AdminBookingController::class, 'ubahJadwal'])->name('booking.ubah-jadwal');
        Route::post('/booking/{id}/cetak-struk', [AdminBookingController::class, 'cetakStruk'])->name('booking.cetak-struk');
        Route::post('/booking/{id}/cetak-struk', [AdminBookingController::class, 'cetakStruk'])->name('booking.cetak-struk');

        Route::resource('game', GameController::class);
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf'); 
        Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
        Route::patch('/profil', [ProfilController::class, 'update'])->name('profil.update');
        Route::patch('/profil/password', [ProfilController::class, 'gantiPassword'])->name('profil.password');

        // HALAMAN KELOLA GALERI PANEL ADMIN
        Route::resource('galeri', GaleriController::class)->names([
            'index'   => 'galeri.index',
            'create'  => 'galeri.create',
            'store'   => 'galeri.store',
            'edit'    => 'galeri.edit',
            'update'  => 'galeri.update',
            'destroy' => 'galeri.destroy',
        ]);

        Route::patch('/galeri/{id}/toggle-status', [GaleriController::class, 'toggleStatus'])->name('galeri.toggle-status');

        // Halaman Kelola Produk F&B

        Route::get('/fb/produk', [ProdukController::class, 'index'])->name('fb.produk.index');
        Route::patch('/fb/produk/{id}/toggle-status', [ProdukController::class, 'toggleStatus'])->name('fb.produk.toggle-status');

        // Rute untuk Tambah Produk F&B
        Route::get('/fb/produk/create', [ProdukController::class, 'create'])->name('fb.produk.create');
        Route::post('/fb/produk/store', [ProdukController::class, 'store'])->name('fb.produk.store');

        // Halaman Khusus Tambah Stok
        Route::get('/fb/produk/tambah-stok', [\App\Http\Controllers\Admin\Fb\ProdukController::class, 'halamanTambahStok'])->name('fb.produk.halaman-tambah-stok');
        
        // Proses Menyimpan Stoknya
        Route::post('/fb/produk/{id}/simpan-stok', [\App\Http\Controllers\Admin\Fb\ProdukController::class, 'simpanStok'])->name('fb.produk.simpan-stok');
        // Route untuk Edit Produk F&B
        Route::get('/fb/produk/edit/{id}', [ProdukController::class, 'edit'])->name('fb.produk.edit');
        
        Route::put('/fb/produk/update/{id}', [ProdukController::class, 'update'])->name('fb.produk.update');

        // Tangkapan sementara untuk tombol simpan (POST/PUT) saat Edit
        Route::post('/fb/produk/update', function () {
            return redirect('/admin/fb/produk');
        });

        // Halaman Kelola Transaksi F&B
        Route::get('/fb/transaksi', [\App\Http\Controllers\Admin\Fb\AdminFbController::class, 'index'])->name('fb.transaksi.index');
        
        // 🔹 PASTIKAN RUTE INI SUDAH ADA
        Route::put('/fb/transaksi/{id}/status', [\App\Http\Controllers\Admin\Fb\AdminFbController::class, 'updateStatus'])->name('fb.transaksi.update-status');

        // Halaman Tambah Pesanan F&B (Tampilan)
        Route::get('/fb/transaksi/create', [\App\Http\Controllers\Admin\Fb\AdminFbController::class, 'create'])->name('fb.transaksi.create');

        // 🔹 Rute untuk memproses form (Arahkan ke fungsi store di atas)
        Route::post('/fb/transaksi/store', [\App\Http\Controllers\Admin\Fb\AdminFbController::class, 'store'])->name('fb.transaksi.store');

        Route::get('/logout', function() {
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect('/login');
        })->name('logout.get')->middleware('auth');
        
        //banner route (tidak perlu prefix('admin') lagi, group luar sudah prefix 'admin')
        // Route untuk menampilkan halaman (GET)
        Route::get('/banner', [BannerController::class, 'index'])->name('banners.index');

        // Route untuk menyimpan data baru (POST)
        Route::post('/banner', [BannerController::class, 'store'])->name('banners.store');

        // Route untuk update data (PUT/PATCH)
        Route::put('/banner/{id}', [BannerController::class, 'update'])->name('banners.update');

        // Route untuk hapus data (DELETE)
        Route::delete('/banner/{id}', [BannerController::class, 'destroy'])->name('banners.destroy');
        
        // Route Kelola Video
        Route::resource('video', VideoController::class);
        Route::patch('/video/{id}/toggle-status', [VideoController::class, 'toggleStatus'])->name('video.toggle-status');
    });

    // Pelanggan Khusus
    Route::middleware([RoleMiddleware::class . ':pelanggan', 'verified'])->group(function () {
    Route::get('/booking/payment/{id}', [BookingController::class, 'showPayment'])->name('booking.payment.show');
    });

});