<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\PromotionsController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Auth
 Route::post('register', [AuthController::class, 'register']);
 Route::post('login', [AuthController::class, 'login']);
 Route::post('logout', [AuthController::class, 'logOut'])->middleware('auth:sanctum');
// Auth

Route::post('/contacto/enviar-mensaje', [ContactoController::class, 'enviarMensaje']);

Route::resource('gallery', GalleryController::class);

Route::resource('business', BusinessController::class);

Route::resource('banner', BannerController::class);
Route::resource('promotions', PromotionsController::class);
Route::resource('cards', CardsController::class);
Route::resource('socials', SocialController::class);
Route::resource('users', UserController::class);


