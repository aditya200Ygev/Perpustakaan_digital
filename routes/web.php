<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dashboard\AnggotaController;
use App\Http\Controllers\Dashboard\BukuController;
use App\Http\Controllers\BukuPublicController;
 use App\Http\Controllers\PeminjamanController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('abouts');
});

/* ✅ LIST BUKU */
Route::get('/buku', [BukuPublicController::class, 'index'])->name('buku.public');

/* ✅ DETAIL BUKU */
Route::get('/buku/{id}', [BukuPublicController::class, 'show'])
    ->middleware('auth')
    ->name('buku.detail');

Route::get('/contact', function () {
    return view('contact');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard/anggota', [DashboardController::class, 'anggota'])
        ->middleware('role:anggota')
        ->name('dashboard.anggota');

    Route::get('/dashboard/petugas', [DashboardController::class, 'petugas'])
        ->middleware('role:petugas')
        ->name('dashboard.petugas');

    Route::get('/dashboard/kepala', [DashboardController::class, 'kepala'])
        ->middleware('role:kep_perpustakaan')
        ->name('dashboard.kepala');

    Route::prefix('dashboard/petugas')->middleware('role:petugas')->group(function () {

        Route::get('/anggota', [AnggotaController::class, 'index'])->name('petugas.anggota');
        Route::get('/anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('petugas.anggota.edit');
        Route::put('/anggota/{id}', [AnggotaController::class, 'update'])->name('petugas.anggota.update');

        Route::get('/buku', [BukuController::class, 'index'])->name('petugas.buku');
        Route::get('/buku/create', [BukuController::class, 'create'])->name('petugas.buku.create');
        Route::post('/buku', [BukuController::class, 'store'])->name('petugas.buku.store');
        Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('petugas.buku.edit');
        Route::put('/buku/{id}', [BukuController::class, 'update'])->name('petugas.buku.update');
        Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('petugas.buku.delete');
    });
});

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::get('/profile/edit', [ProfileController::class, 'edit'])
    ->middleware('auth')
    ->name('profile.edit');

Route::post('/profile/update', [ProfileController::class, 'update'])
    ->middleware('auth')
    ->name('profile.update');


Route::middleware('auth')->group(function () {
Route::get('/pinjam/{id}/confirm', [PeminjamanController::class, 'showPinjam'])
    ->name('pinjam.confirm');

Route::get('/pinjam/{id}', [PeminjamanController::class, 'create'])
    ->name('pinjam.create');

    Route::post('/pinjam', [PeminjamanController::class, 'store'])
        ->name('pinjam.store');
});




// tampilkan data peminjaman (halaman admin)
Route::get('/dashboard/peminjaman', [PeminjamanController::class, 'index'])
    ->name('petugas.peminjaman.index');

// approve (setujui peminjaman)
Route::post('/pinjam/{id}/approve', [PeminjamanController::class, 'approve'])
    ->name('pinjam.approve');

// kembalikan buku
Route::post('/pinjam/{id}/kembali', [PeminjamanController::class, 'kembalikan'])
    ->name('pinjam.kembali');
Route::post('/pinjam/{id}/tolak', [PeminjamanController::class, 'tolak'])
    ->name('pinjam.tolak');
