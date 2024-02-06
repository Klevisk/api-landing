<?php

use App\Http\Controllers\GalleryController;
use App\Http\Controllers\BusinessController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('gallery', [GalleryController::class, 'store']);
Route::put('gallery/{id}', [GalleryController::class, 'update']);
Route::delete('gallery/{id}', [GalleryController::class, 'destroy']);
Route::get('gallery/{id}', [GalleryController::class, 'show']);
Route::get('gallery', [GalleryController::class, 'index']);

Route::get('/business', [BusinessController::class, 'index']);
Route::get('/business/{id}', [BusinessController::class, 'show']);
Route::post('/business', [BusinessController::class, 'store']);
Route::put('/business/{id}', [BusinessController::class, 'update']);
Route::delete('/business/{id}', [BusinessController::class, 'destroy']);
