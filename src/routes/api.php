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

Route::post('/auth/login', 'API\LoginController@action');
Route::post('/auth/logout', 'API\LogoutController@action');
Route::get('/auth/me', 'API\MeController@action');



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
