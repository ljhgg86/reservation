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
Route::prefix('v2')->group(function(){
    Route::post('login','PassportController@login');
    Route::apiResource('ordertype','OrdertypeController')->middleware('auth:api');
    //Route::get('typesobjects','OrderobjectController@typesObjects')->middleware('auth:api');
    Route::get('typeobjects/{type_id}','OrderobjectController@typeObjects')->middleware('auth:api');
    //Route::get('typeobject/{object_id}','OrderobjectController@object')->middleware('auth:api');
    Route::get('objectdate/{object_id}/{date}','OrderobjectController@objectDateTimes')->middleware('auth:api');
    Route::get('objectmonth/{object_id}/{date}','OrderobjectController@objectMonth')->middleware('auth:api');
    Route::apiResource('orderobject','OrderobjectController')->middleware('auth:api');
});
