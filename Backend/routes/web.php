<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ==================== ADMIN ROUTES ====================
Route::prefix('admin')->name('admin.')->group(function () {

    // Public routes
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])->name('forgot.password');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

    // Protected routes
    Route::middleware('auth.admin')->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/my-profile', [AdminController::class, 'profile'])->name('my.profile');
        Route::post('/my-profile', [AdminController::class, 'updateProfile'])->name('my.profile.update');
        Route::get('/change-password', [AdminController::class, 'change_password'])->name('change.password');
        Route::post('/change-password', [AdminController::class, 'update_password'])->name('change.password.update');

        // Destinations routes
        Route::get('/destinations', [App\Http\Controllers\Backend\DestinationsController::class, 'index'])->name('destinations.index');
        Route::get('/destinations/create', [App\Http\Controllers\Backend\DestinationsController::class, 'create'])->name('destinations.create');
        Route::post('/destinations', [App\Http\Controllers\Backend\DestinationsController::class, 'store'])->name('destinations.store');
        Route::get('/destinations/{destination}/edit', [App\Http\Controllers\Backend\DestinationsController::class, 'edit'])->name('destinations.edit');
        Route::put('/destinations/{destination}', [App\Http\Controllers\Backend\DestinationsController::class, 'update'])->name('destinations.update');
        Route::delete('/destinations/{destination}', [App\Http\Controllers\Backend\DestinationsController::class, 'destroy'])->name('destinations.destroy');
        Route::patch('/destinations/{destination}/toggle-status',[App\Http\Controllers\Backend\DestinationsController::class, 'toggleStatus'])->name('destinations.toggle-status');

        // Categories routes
        Route::get('/categories', [App\Http\Controllers\Backend\CategoriesController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [App\Http\Controllers\Backend\CategoriesController::class, 'create'])->name('categories.create');
        Route::post('/categories', [App\Http\Controllers\Backend\CategoriesController::class, 'store'])->name('categories.store');
        Route::get('/categories/{categories}/edit', [App\Http\Controllers\Backend\CategoriesController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{categories}', [App\Http\Controllers\Backend\CategoriesController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{categories}', [App\Http\Controllers\Backend\CategoriesController::class, 'destroy'])->name('categories.destroy');
        Route::patch('/categories/{categories}/toggle-status',[App\Http\Controllers\Backend\CategoriesController::class, 'toggleStatus'])->name('categories.toggle-status');
    });
});

// Reset password routes
Route::get('/admin/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('admin.password.reset');

Route::post('/admin/reset-password', [ResetPasswordController::class, 'resetPassword'])
    ->name('admin.password.update');
