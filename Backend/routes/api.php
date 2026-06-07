<?php

use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\FacebookAuthController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\TransportApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RestaurantApiController;

Route::get('/home', [HomeApiController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/google-login', [GoogleAuthController::class, 'googleAuth']);
Route::post('/facebook-login',[FacebookAuthController::class, 'facebookLogin']);

Route::prefix('blog')->group(function () {
    // Public routes
    Route::get('/posts', [BlogApiController::class, 'getPosts']);
    Route::get('/posts/featured', [BlogApiController::class, 'getFeaturedPosts']);
    Route::get('/posts/{slug}', [BlogApiController::class, 'getPostBySlug']);
    Route::get('/posts/{postId}/related', [BlogApiController::class, 'getRelatedPosts']);
    Route::get('/categories', [BlogApiController::class, 'getCategories']);
});

Route::prefix('transports')->group(function () {
    Route::get('/', [TransportApiController::class, 'getTransports']);                        
    Route::get('/destinations', [TransportApiController::class, 'getTransportDestinations']); 
    Route::get('/detail/{slug}', [TransportApiController::class, 'getTransportBySlug']);     
    Route::get('/related/{id}', [TransportApiController::class, 'getRelatedTransports']);     
});

Route::prefix('restaurants')->group(function () {
    Route::get('/', [RestaurantApiController::class, 'getRestaurants']);            
    Route::get('/detail/{slug}', [RestaurantApiController::class, 'getRestaurantBySlug']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
});
