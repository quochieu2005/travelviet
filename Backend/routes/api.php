<?php

use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\FacebookAuthController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\PricingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/home', [HomeApiController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/google-login', [GoogleAuthController::class, 'googleAuth']);
Route::post('/facebook-login',[FacebookAuthController::class, 'facebookLogin']);

Route::get('pricing-plans', [PricingController::class, 'index']);
Route::post('pricing-inquiries', [PricingController::class, 'store']);

Route::get('/hotels', [App\Http\Controllers\Api\HotelController::class, 'index']);
Route::get('/hotels/{slug}', [App\Http\Controllers\Api\HotelController::class, 'show']);

Route::prefix('blog')->group(function () {
    // Public routes
    Route::get('/posts', [BlogApiController::class, 'getPosts']);
    Route::get('/posts/featured', [BlogApiController::class, 'getFeaturedPosts']);
    Route::get('/posts/{slug}', [BlogApiController::class, 'getPostBySlug']);
    Route::get('/posts/{postId}/related', [BlogApiController::class, 'getRelatedPosts']);
    Route::get('/categories', [BlogApiController::class, 'getCategories']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
});
