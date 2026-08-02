<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgenController;
use App\Http\Controllers\JenisGasController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\AuthController;

// ============================================
// ROUTES LOGIN (TANPA MIDDLEWARE)
// ============================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');

// Lupa Password
Route::get('/lupa-password', [AuthController::class, 'showForgotPasswordForm'])->name('lupa-password');
Route::post('/lupa-password', [AuthController::class, 'resetPassword'])->name('lupa-password.proses');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset-password');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('reset-password.update');

// Google Login
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================
// ROUTES PROTECTED (PAKAI MIDDLEWARE)
// ============================================
Route::middleware(['auth.admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('agen', AgenController::class);
    Route::resource('jenis-gas', JenisGasController::class);
    
    // Transaksi
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::post('/pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('/pembelian/{pembelian}/edit', [PembelianController::class, 'edit'])->name('pembelian.edit');
    Route::put('/pembelian/{pembelian}', [PembelianController::class, 'update'])->name('pembelian.update');
    Route::get('/pembelian/{pembelian}', [PembelianController::class, 'show'])->name('pembelian.show');
    Route::delete('/pembelian/{pembelian}', [PembelianController::class, 'destroy'])->name('pembelian.destroy');
    Route::get('/get-harga-agen', [PembelianController::class, 'getHargaAgen'])->name('get.harga.agen');

    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/get-harga-jual', [PenjualanController::class, 'getHargaJual'])->name('get.harga.jual');

    // Piutang
    Route::get('/piutang', [PiutangController::class, 'index'])->name('piutang.index');
    Route::get('/piutang/bayar/{id}', [PiutangController::class, 'bayar'])->name('piutang.bayar');
    Route::post('/piutang/proses-bayar/{id}', [PiutangController::class, 'prosesBayar'])->name('piutang.proses-bayar');
    Route::get('/piutang/detail/{id}', [PiutangController::class, 'getDetail'])->name('piutang.detail');

    // Laporan
    Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
    Route::get('/laporan/laba', [LaporanController::class, 'laba'])->name('laporan.laba');
    Route::get('/laporan/piutang', [LaporanController::class, 'piutang'])->name('laporan.piutang');
    Route::get('/laporan/cetak-stok-pdf', [LaporanController::class, 'cetakStokPDF'])->name('laporan.cetak-stok-pdf');
    Route::get('/laporan/cetak-laba-pdf', [LaporanController::class, 'cetakLabaPDF'])->name('laporan.cetak-laba-pdf');
});