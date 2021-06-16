<?php

use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Controllers\ItemController;
use Modules\Api\Http\Controllers\AuthController;

Route::prefix('auth')->group(function() {
    Route::post('registration', [AuthController::class,'register'])->name('auth.registration');
    Route::post('login', [AuthController::class,'login']);
    Route::get('logout', [AuthController::class,'logout'])->middleware('auth:sanctum');
});

Route::group(['prefix' =>'items', 'middleware' => 'auth:sanctum'], function() {
    Route::get('all', [ItemController::class,'all']);
    Route::get('{id}', [ItemController::class,'show']);
    Route::post('add', [ItemController::class,'add']);
    Route::post('update/{id}', [ItemController::class,'update']);
    Route::delete('drop/{id}', [ItemController::class,'drop']);
});
