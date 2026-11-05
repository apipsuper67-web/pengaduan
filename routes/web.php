<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
    Route::get('/warga/dashboard', [DashboardController::class, 'citizen'])
        ->middleware('can:warga')->name('warga.dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('can:admin')->name('admin.dashboard');

    Route::middleware('can:warga')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/buat', [LaporanController::class, 'create'])->name('laporan.create');
        Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
        Route::get('/laporan/{laporan}', [LaporanController::class, 'show'])->name('laporan.show');
        Route::get('/laporan/{laporan}/edit', [LaporanController::class, 'edit'])->name('laporan.edit');
        Route::put('/laporan/{laporan}', [LaporanController::class, 'update'])->name('laporan.update');
        Route::delete('/laporan/{laporan}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
    });

    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'adminIndex'])->name('laporan.index');
        Route::get('/laporan/{laporan}', [LaporanController::class, 'adminShow'])->name('laporan.show');
        Route::patch('/laporan/{laporan}/status', [LaporanController::class, 'updateStatus'])->name('laporan.status');
    });

    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
});
