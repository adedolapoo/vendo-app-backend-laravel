<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', [ 'as' => 'login', 'uses' => 'AuthController@getLogin']);
    Route::post('login', ['as' => 'login.post', 'uses' => 'AuthController@postLogin']);
    # Register

    Route::post('register', ['as' => 'register.post', 'uses' => 'AuthController@postRegister']);

    # Account Activation
    Route::get('activate/{userId}/{activationCode}', 'AuthController@getActivate');
    # Reset password
    Route::get('reset', ['as' => 'reset', 'uses' => 'AuthController@getReset']);
    Route::post('reset', ['as' => 'reset.post', 'uses' => 'AuthController@postReset']);
    Route::get('reset/{id}/{code}', ['as' => 'reset.complete', 'uses' => 'AuthController@getResetComplete']);
    Route::post('reset/{id}/{code}', ['as' => 'reset.complete.post', 'uses' => 'AuthController@postResetComplete']);
    # Logout
    Route::get('logout', ['as' => 'logout', 'uses' => 'AuthController@getLogout']);
});


Route::get('admin/users/change-password', array('as' => 'admin.users.change-password', 'uses' => 'UsersController@changePassword'));
Route::post('admin/users/change-password', array('as' => 'admin.users.change-password.post', 'uses' => 'UsersController@postChangePassword'));


Route::get('admin/users', ['as' => 'admin.users.index', 'uses' => 'UsersController@index']);
Route::get('admin/users/datatable', ['as' => 'admin.users.datatable', 'uses' => 'UsersController@dataTable']);
Route::get('admin/users/create', ['as' => 'admin.users.create', 'uses' => 'UsersController@create']);
Route::get('admin/users/{user}/edit', ['as' => 'admin.users.edit', 'uses' => 'UsersController@edit']);
Route::post('admin/users', ['as' => 'admin.users.store', 'uses' => 'UsersController@store']);
Route::put('admin/users/{user}', ['as' => 'admin.users.update', 'uses' => 'UsersController@update']);
Route::post('admin/users/sort', ['as' => 'admin.users.sort', 'uses' => 'UsersController@sort']);

/*
 * Ajax routes
 */
Route::get('ajax/users', ['as' => 'ajax.users.index', 'uses' => 'UsersAjaxController@index']);
Route::put('ajax/users/{user}', ['as' => 'ajax.users.update', 'uses' => 'UsersAjaxController@update']);
Route::delete('ajax/users/{user}', ['as' => 'ajax.users.destroy', 'uses' => 'UsersAjaxController@destroy']);

//Roles
Route::get('admin/roles', ['as' => 'admin.users.roles.index', 'uses' => 'RolesController@index']);
Route::get('admin/roles/create', ['as' => 'admin.users.roles.create', 'uses' => 'RolesController@create']);
Route::post('admin/roles', ['as' => 'admin.users.roles.store', 'uses' => 'RolesController@store']);
Route::get('admin/roles/{roles}/edit', ['as' => 'admin.users.roles.edit', 'uses' => 'RolesController@edit']);
Route::put('admin/roles/{roles}/edit', ['as' => 'admin.users.roles.update', 'uses' => 'RolesController@update']);

Route::get('ajax/roles', ['as' => 'ajax.users.roles.index', 'uses' => 'RolesAjaxController@index']);
Route::put('ajax/roles/{role}', ['as' => 'ajax.users.roles.update', 'uses' => 'RolesAjaxController@update']);
Route::delete('ajax/roles/{role}', ['as' => 'ajax.users.roles.destroy', 'uses' => 'RolesAjaxController@destroy']);
