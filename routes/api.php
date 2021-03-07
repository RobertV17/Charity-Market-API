<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::prefix('items')->group(function() {
    Route::get('all', [ItemController::class,'getAllItems']);
});

