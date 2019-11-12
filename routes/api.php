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

Route::post('login', 'ApiController@login');
Route::post('register', 'ApiController@register');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'ApiController@logout');
    Route::get('user', 'ApiController@getAuthUser');

    Route::get('logs', 'LogController@index');
    Route::get('logs/{id}', 'LogController@show');
    Route::post('logs', 'LogController@store');
    Route::put('logs/{id}', 'LogController@update');
    Route::delete('logs/{id}', 'LogController@destroy');
    Route::get('logs/level/{level}', 'LogController@findLevel');        
    Route::get('logs/descricao/{descricao}', 'LogController@findDescricao');
    Route::get('logs/origem/{origem}', 'LogController@findOrigem');        
});
