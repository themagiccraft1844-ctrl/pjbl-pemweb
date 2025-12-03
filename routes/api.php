<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TreeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/pohon/store', [TreeController::class, 'store']);
Route::post('/pohon/delete', [TreeController::class, 'destroy']);
Route::post('/pohon/addLike', [TreeController::class,'addLike']);
Route::post('/pohon/rmLike', [TreeController::class,'rmLike']);