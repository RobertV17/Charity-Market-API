<?php

use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Controllers\ItemController;

Route::prefix('items')->group(function() {
    Route::get('all', [ItemController::class,'get']);
    Route::post('add', [ItemController::class,'add']);
});
