<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('registration', ['uses' => 'AuthController@register', 'as' => 'auth.registration']);
    Route::post('login', ['uses' => 'AuthController@login', 'as' => 'auth.login']);
    Route::get('logout', [
        'uses'       => 'AuthController@logout',
        'as'         => 'auth.logout',
        'middleware' => 'auth:sanctum'
    ]);
});

Route::group(['prefix' => 'items', 'middleware' => 'auth:sanctum'], function () {
    Route::get('all', ['uses' => 'ItemController@all', 'as' => 'items.all']);
    Route::get('{id}', ['uses' => 'ItemController@show', 'as' => 'items.show']);
    Route::post('add', ['uses' => 'ItemController@add', 'as' => 'items.add']);
    Route::post('update/{id}', ['uses' => 'ItemController@update', 'as' => 'items.update']);
    Route::delete('drop/{id}', ['uses' => 'ItemController@drop', 'as' => 'items.drop']);
});
