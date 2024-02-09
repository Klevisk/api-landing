<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\PromotionsController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\UserController;
use App\Models\Gallery;
use App\Models\Social;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// Route::post('gallery', [GalleryController::class, 'store']);
// Route::put('gallery/{id}', [GalleryController::class, 'update']);
// Route::delete('gallery/{id}', [GalleryController::class, 'destroy']);
// Route::get('gallery/{id}', [GalleryController::class, 'show']);
// Route::get('gallery', [GalleryController::class, 'index']);


Route::resource('gallery', GalleryController::class);

Route::resource('business', BusinessController::class);

Route::resource('banner', BannerController::class);
Route::resource('promotions', PromotionsController::class);
Route::resource('cards', CardsController::class);
Route::resource('socials', SocialController::class);
Route::resource('users', UserController::class);


