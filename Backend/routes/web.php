<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::prefix('admin')->group(function () {
//     Route::get('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('admin.login');
//     // Route::post('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('admin.login.submit');
//     // Route::post('/logout', [App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('admin.logout');

//     Route::middleware('auth')->group(function () {
//         Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');
//     });
// });

Route::get('/admin/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::get('/admin/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');