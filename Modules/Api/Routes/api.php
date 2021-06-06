<?php

use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Controllers\ItemController;
use Modules\Api\Http\Controllers\AuthController;

Route::prefix('auth')->group(function() {
    Route::post('registration', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    Route::get('logout', [AuthController::class,'logout'])->middleware('auth:sanctum');
});

Route::group(['prefix' =>'items', 'middleware' => 'auth:sanctum'], function() {
    Route::get('all', [ItemController::class,'get']);
    Route::post('add', [ItemController::class,'add']);
});
