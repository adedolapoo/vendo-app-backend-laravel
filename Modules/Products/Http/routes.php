<?php

use Illuminate\Support\Facades\Route;
use Modules\Products\Http\Controllers\BuyProductsController;
use Modules\Products\Http\Controllers\ProductsController;

Route::group(['prefix' => 'products'], function () {
    //anybody

    Route::group(['middleware' => [config('core.auth_middleware')]], function () {

        Route::get('/', [ProductsController::class, 'index']);
        Route::get('/{id}', [ProductsController::class, 'show']);

        //Only seller can access this routes
        Route::group(['middleware'=>'can:isSeller'], function () {
            Route::post('/', [ProductsController::class, 'store']);
            Route::patch('/{id}', [ProductsController::class, 'update']);
            Route::delete('/{id}', [ProductsController::class, 'destroy']);
        });
    });

});

Route::group(['middleware' => [config('core.auth_middleware')]], function () {

    //only a buyer can access this route
    Route::post('/buy', [BuyProductsController::class, 'buy'])->middleware('can:isBuyer');
});
