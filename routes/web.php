<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OtherCategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Support\Facades\Route;

Route::middleware(SecurityHeaders::class)->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/lacak', [HomeController::class, 'track'])->middleware('throttle:20,1')->name('track');

    Route::middleware('guest')->group(function () {
        Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/masuk', [AuthController::class, 'login'])->middleware('throttle:20,1');
        Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/daftar', [AuthController::class, 'register'])->middleware('throttle:10,1');
    });

    Route::post('/keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

    Route::middleware('auth')->group(function () {
        // Laporan milik warga
        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/laporan/baru', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');
        Route::get('/laporan/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/laporan/{report}/ubah', [ReportController::class, 'edit'])->name('reports.edit');
        Route::put('/laporan/{report}', [ReportController::class, 'update'])->name('reports.update');
        Route::delete('/laporan/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

        // Akun sendiri
        Route::get('/akun', [AccountController::class, 'edit'])->name('account.edit');
        Route::put('/akun/profil', [AccountController::class, 'updateProfile'])->name('account.profile');
        Route::put('/akun/kata-sandi', [AccountController::class, 'updatePassword'])->name('account.password.update');
    });

    // Panel petugas dan admin
    Route::prefix('admin')->name('admin.')->middleware(['auth', EnsureRole::class . ':admin,petugas'])->group(function () {
        Route::get('/laporan', [Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('/laporan/{report}', [Admin\ReportController::class, 'show'])->name('reports.show');
        Route::put('/laporan/{report}', [Admin\ReportController::class, 'update'])->name('reports.update');

        // Khusus admin
        Route::middleware(EnsureRole::class . ':admin')->group(function () {
            Route::delete('/laporan/{report}', [Admin\ReportController::class, 'destroy'])->name('reports.destroy');

            Route::get('/kategori', [Admin\CategoryController::class, 'index'])->name('categories.index');
            Route::post('/kategori', [Admin\CategoryController::class, 'store'])->name('categories.store');
            Route::get('/kategori/{category}/ubah', [Admin\CategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/kategori/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
            Route::delete('/kategori/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

            // Pantau kategori "Lainnya"
            Route::get('/kategori-lainnya', [OtherCategoryController::class, 'index'])->name('other-categories');
            Route::post('/kategori-lainnya/promosi', [OtherCategoryController::class, 'promote'])->name('other-categories.promote');

            Route::get('/pengguna', [Admin\UserController::class, 'index'])->name('users.index');
            Route::post('/pengguna', [Admin\UserController::class, 'store'])->name('users.store');
            Route::get('/pengguna/{user}/ubah', [Admin\UserController::class, 'edit'])->name('users.edit');
            Route::put('/pengguna/{user}', [Admin\UserController::class, 'update'])->name('users.update');
            Route::put('/pengguna/{user}/kata-sandi', [Admin\UserController::class, 'resetPassword'])->name('users.password');
            Route::delete('/pengguna/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

            Route::get('/log-aktivitas', [Admin\AuditLogController::class, 'index'])->name('audit.index');
        });
    });
});