<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ==================== ADMIN ROUTES ====================
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('login.submit');
    

    Route::middleware('auth.admin')->group(function () {   
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        Route::post('/logout', [App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/my-profile', [App\Http\Controllers\Admin\AdminController::class, 'profile'])->name('my.profile');
        Route::post('/my-profile', [App\Http\Controllers\Admin\AdminController::class, 'updateProfile'])->name('my.profile.update');
        Route::get('/reset-password', [App\Http\Controllers\Admin\AdminController::class, 'reset_password'])->name('reset.password');
        Route::post('/reset-password', [App\Http\Controllers\Admin\AdminController::class, 'update_password'])->name('reset.password.update');
    });
});