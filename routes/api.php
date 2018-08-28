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

// index and show used only for making it easier
// for you to check how the app works.
// Should be removed if we do not want to expose
// subscribers' and fields' data

Route::resource('subscribers', 'SubscriberController')->only([
    'index', 'show', 'store', 'update'
]);


Route::resource('fields', 'FieldController')->only([
    'index', 'show', 'store', 'update'
]);

