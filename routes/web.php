<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ItemPenjualanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PercabanganController;
use App\Http\Controllers\PerulanganController;
use App\Http\Controllers\variabelController;

// route yang bisa di akses ketika user belum login 
Route::middleware('guest')->group(function() {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/auth', [AuthController::class, 'auth'])->name('auth');
});

// route yang bisa di akses ketika user sudah login 
Route::middleware('auth')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/laporan/bulanan', [LaporanController::class, 'bulanan'])
    ->name('laporan.bulanan')
    ->middleware('auth');

    // Khusus admin — users
    Route::middleware('role:admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/update/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/destroy/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Admin & kasir — produk, DIPINDAH keluar, sejajar dengan grup admin di atas
    Route::middleware('role:admin,kasir')->group(function() {
        Route::resource('/produk', ProdukController::class);
        Route::resource('/penjualan', PenjualanController::class);
        Route::resource('jenis', JenisController::class)->parameters(['jenis' => 'jenis']);
        Route::resource('/itempenjualan', ItemPenjualanController::class);
    });
});