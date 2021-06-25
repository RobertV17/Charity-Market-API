<?php

use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Controllers\ItemController;
use Modules\Api\Http\Controllers\AuthController;

Route::prefix('auth')->group(function() {
    Route::post('registration', [AuthController::class,'register'])->name('auth.registration');
    Route::post('login', [AuthController::class,'login'])->name('auth.login');
    Route::get('logout', [AuthController::class,'logout'])->middleware('auth:sanctum')
        ->name('auth.logout');
});

Route::group(['prefix' =>'items', 'middleware' => 'auth:sanctum'], function() {
    Route::get('all', [ItemController::class,'all'])->name('items.all');
    Route::get('{id}', [ItemController::class,'show'])->name('items.show');
    Route::post('add', [ItemController::class,'add']);
    Route::post('update/{id}', [ItemController::class,'update']);
    Route::delete('drop/{id}', [ItemController::class,'drop']);
});
