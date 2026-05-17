<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\ResetPasswordController;
use App\Http\Controllers\Backend\PromotionsController;
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

        // Dashboard route
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Admin profile routes
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
        Route::patch('/destinations/{destination}/toggle-status', [App\Http\Controllers\Backend\DestinationsController::class, 'toggleStatus'])->name('destinations.toggle-status');

        // Categories routes
        Route::get('/categories', [App\Http\Controllers\Backend\CategoriesController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [App\Http\Controllers\Backend\CategoriesController::class, 'create'])->name('categories.create');
        Route::post('/categories', [App\Http\Controllers\Backend\CategoriesController::class, 'store'])->name('categories.store');
        Route::get('/categories/{categories}/edit', [App\Http\Controllers\Backend\CategoriesController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{categories}', [App\Http\Controllers\Backend\CategoriesController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{categories}', [App\Http\Controllers\Backend\CategoriesController::class, 'destroy'])->name('categories.destroy');
        Route::patch('/categories/{categories}/toggle-status', [App\Http\Controllers\Backend\CategoriesController::class, 'toggleStatus'])->name('categories.toggle-status');
        
        // Tours routes
        Route::get('/tours', [App\Http\Controllers\Backend\ToursController::class, 'index'])->name('tours.index');
        Route::get('/tours/create', [App\Http\Controllers\Backend\ToursController::class, 'create'])->name('tours.create');
        Route::post('/tours', [App\Http\Controllers\Backend\ToursController::class, 'store'])->name('tours.store');
        Route::get('/tours/{tour}/edit', [App\Http\Controllers\Backend\ToursController::class, 'edit'])->name('tours.edit');
        Route::put('/tours/{tour}', [App\Http\Controllers\Backend\ToursController::class, 'update'])->name('tours.update');
        Route::delete('/tours/{tour}', [App\Http\Controllers\Backend\ToursController::class, 'destroy'])->name('tours.destroy');
        Route::patch('/tours/{tour}/toggle-status', [App\Http\Controllers\Backend\ToursController::class, 'toggleStatus'])->name('tours.toggle-status');

        // Promotions routes
        Route::get('/promotions', [App\Http\Controllers\Backend\PromotionsController::class, 'index'])->name('promotions.index');
        Route::get('/promotions/create', [App\Http\Controllers\Backend\PromotionsController::class, 'create'])->name('promotions.create');
        Route::post('/promotions', [App\Http\Controllers\Backend\PromotionsController::class, 'store'])->name('promotions.store');
        Route::get('/promotions/{promotion}/edit', [App\Http\Controllers\Backend\PromotionsController::class, 'edit'])->name('promotions.edit');
        Route::put('/promotions/{promotion}', [App\Http\Controllers\Backend\PromotionsController::class, 'update'])->name('promotions.update');
        Route::delete('/promotions/{promotion}', [App\Http\Controllers\Backend\PromotionsController::class, 'destroy'])->name('promotions.destroy');
        Route::patch('/promotions/{promotion}/toggle-status', [App\Http\Controllers\Backend\PromotionsController::class, 'toggleStatus'])->name('promotions.toggle-status');
    });
});

// Reset password routes
Route::get('/admin/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('admin.password.reset');
Route::post('/admin/reset-password', [ResetPasswordController::class, 'resetPassword'])
    ->name('admin.password.update');
