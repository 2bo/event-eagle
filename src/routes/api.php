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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/events/search', 'EventController@search');
Route::get('/events/{id}', 'EventController@show');
Route::get('/events/tag/{tagUrlName}', 'EventController@tag');
Route::get('/condition/place', 'SearchConditionController@getPlaceConditions');
Route::get('/condition/type', 'SearchConditionController@getEventTypeConditions');

