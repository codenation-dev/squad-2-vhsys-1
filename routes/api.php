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

Route::post('login', 'ApiController@login')->name('login');
Route::post('register', 'ApiController@register');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'ApiController@logout');
    Route::get('user', 'ApiController@getAuthUser');

    Route::get('logs', 'LogController@filter');
    Route::get('logs/{id}', 'LogController@show');
    Route::post('logs', 'LogController@store')->name('store');
    Route::put('logs/{id}', 'LogController@update')->name('update');
    Route::delete('logs/{id}', 'LogController@destroy')->name('delete');
});
