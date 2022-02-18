<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\AuthController;
use Modules\Users\Http\Controllers\DepositController;
use Modules\Users\Http\Controllers\UsersController;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => config('core.auth_middleware')], function () {

    Route::get('auth/me', [AuthController::class, 'currentUser']);
    Route::get('auth/logout/all', [AuthController::class, 'logoutAllDevices']);

    //Only users can deposit and reset deposit
    Route::group(['middleware'=>'can:isBuyer'], function () {
        Route::post('deposit', [DepositController::class, 'deposit']);
        Route::post('deposit/reset', [DepositController::class, 'reset']);
    });

    //Only Sellers can perform CRUD on users
    Route::group(['prefix' => 'users','middleware'=>'can:isSeller'], function () {
        Route::get('/', [UsersController::class, 'index']);
        Route::post('/', [UsersController::class, 'store']);
        Route::get('/{id}', [UsersController::class, 'show']);
        Route::patch('/{id}', [UsersController::class, 'update']);
        Route::delete('/{id}', [UsersController::class, 'destroy']);
    });
});
