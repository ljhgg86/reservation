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
//Route::post('login','PassportController@login');
Route::prefix('v2')->group(function(){
    Route::post('login','PassportController@login');
    Route::resource('ordertype','OrdertypeController')->middleware('auth:api');
    Route::get('ordertype/orderobject','OrderobjectController@typeObjects')->middleware('auth:api');
    Route::get('ordertype/{type_id}/orderobject','OrderobjectController@typeObject')->middleware('auth:api');
    Route::get('ordertype/oederobject/{object_id}','OrderobjectController@object')->middleware('auth:api');
});
