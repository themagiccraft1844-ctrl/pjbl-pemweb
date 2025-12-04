<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TreeController;
use App\Http\Controllers\UserController;



Route::post('/pohon/store', [TreeController::class, 'store']);
Route::post('/pohon/delete', [TreeController::class, 'destroy']);
Route::post('/pohon/addLike', [TreeController::class,'addLike']);
Route::post('/pohon/rmLike', [TreeController::class,'rmLike']);
Route::post('/user/update', [UserController::class,'update']);
Route::post('/user/updatePassword', [UserController::class,'updatePassword']);


