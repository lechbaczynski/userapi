<?php

use Illuminate\Http\Request;
Use App\Subscriber;

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


Route::get('subscribers', 'SubscriberController@index');
Route::get('subscribers/{id}', 'SubscriberController@show');
Route::post('subscribers', 'SubscriberController@store');
Route::put('subscribers/{id}', 'SubscriberController@update');
Route::delete('subscribers/{id}', 'SubscriberController@delete');



