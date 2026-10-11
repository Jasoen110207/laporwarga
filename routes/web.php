<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OtherCategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\EnsureRole;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lacak', [HomeController::class, 'track'])->name('track');

Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login']);
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
});

Route::post('/keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/baru', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/laporan/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/akun/kata-sandi', [\App\Http\Controllers\AccountController::class, 'edit'])->name('account.password');
    Route::put('/akun/kata-sandi', [\App\Http\Controllers\AccountController::class, 'update'])->name('account.password.update');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', EnsureRole::class . ':admin,petugas'])->group(function () {
    Route::get('/laporan', [Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/{report}', [Admin\ReportController::class, 'show'])->name('reports.show');
    Route::put('/laporan/{report}', [Admin\ReportController::class, 'update'])->name('reports.update');

    Route::middleware(EnsureRole::class . ':admin')->group(function () {
        Route::get('/kategori', [Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::post('/kategori', [Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::delete('/kategori/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/kategori-lainnya', [OtherCategoryController::class, 'index'])->name('other-categories');
        Route::post('/kategori-lainnya/promosi', [OtherCategoryController::class, 'promote'])->name('other-categories.promote');
    });
});