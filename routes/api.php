<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth',

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});
Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::group([
        'prefix' => 'users',
    ], function () {
        Route::get('', 'UserController@index')->middleware('permission:user.read');
        Route::get('/{id}', 'UserController@show')->middleware('permission:user.read');
        Route::post('', 'UserController@store')->middleware('permission:user.create');
        Route::put('/{id}', 'UserController@update')->middleware('permission:user.update');
        Route::delete('/{id}', 'UserController@destroy')->middleware('permission:user.delete');
    });

    Route::group([
        'prefix' => 'roles',
    ], function () {
        Route::get('', 'RoleController@index')->middleware('permission:role.read');
        Route::get('/{id}', 'RoleController@show')->middleware('permission:role.read');
        Route::post('', 'RoleController@store')->middleware('permission:role.create');
        Route::put('/{id}', 'RoleController@update')->middleware('permission:role.update');
        Route::delete('{id}', 'RoleController@destroy')->middleware('permission:role.delete');
    });

    Route::group([
        'prefix' => 'lookup'
    ], function () {
        Route::get('/permissions', 'LookupController@getUserPermissions');
    });
});

Route::get('/constants', 'ConstantController');
