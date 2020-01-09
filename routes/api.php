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

Route::post('/login', 'AuthController@login');

Route::group(['middleware' => ['auth:api']], function () {

    Route::post('/register', 'AuthController@register');
    Route::post('/logout', 'AuthController@logout');
    Route::get('/user', 'AuthController@user')->name('user');
    Route::get('/export/download', 'UserController@download')->name('export.download');
    Route::get('/export/mail', 'UserController@mail')->name('export.mail');

});