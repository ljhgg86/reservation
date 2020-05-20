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

    Route::get('typeobjects/{type_id}','OrderobjectController@typeObjects')->middleware('auth:api');
    Route::get('objectdate/{object_id}/{date}','OrderobjectController@objectDateTimes')->middleware('auth:api');
    Route::get('objectmonth/{object_id}/{date}','OrderobjectController@objectMonth')->middleware('auth:api');
    Route::apiResource('orderobject','OrderobjectController')->middleware('auth:api');

    Route::get('objecttimerules','OrdertimeruleController@objectTimerules')->middleware('auth:api');
    Route::apiResource('ordertimerule','OrdertimeruleController',['except' => ['index']])->middleware('auth:api');

    Route::get('typeinfos/{type_id}','OrderinfoController@typeInfos')->middleware('auth:api');
    Route::get('objectinfos/{object_id}','OrderinfoController@objectInfos')->middleware('auth:api');
    Route::get('userinfos','OrderinfoController@userInfos')->middleware('auth:api');
    Route::get('usertypeinfos/{type_id}','OrderinfoController@userTypeInfos')->middleware('auth:api');
    Route::get('userobjectinfos/{object_id}','OrderinfoController@userObjectInfos')->middleware('auth:api');
    Route::patch('orderinfo/verify/{info_id}','OrderinfoController@verifyInfos')->middleware('auth:api');
    Route::apiResource('orderinfo','OrderinfoController')->middleware('auth:api');

    Route::get('users/search','UserController@searchUser')->middleware('auth:api');
    Route::get('users/info','UserController@userInfo')->middleware('auth:api');
    Route::get('users/hastypepower/{type_id}','UserController@hasTypePower')->middleware('auth:api');
    Route::apiResource('users','UserController')->middleware('auth:api');

    Route::get('orderfeedback/infofeedbacks/{info_id}','OrderfeedbackController@infoFeedbacks')->middleware('auth:api');
    Route::apiResource('orderfeedback','OrderfeedbackController')->middleware('auth:api');

    Route::get('authority/relatedInfos','AuthorityController@relatedInfos')->middleware('auth:api');
    Route::post('authority/addRelatedAuthority/{id}','AuthorityController@addRelatedAuthority')->middleware('auth:api');
    Route::patch('authority/updateRelatedAuthority/{id}','AuthorityController@updateRelatedAuthority')->middleware('auth:api');
    Route::delete('authority/deleteRelatedAuthority/{id}','AuthorityController@deleteRelatedAuthority')->middleware('auth:api');
    Route::apiResource('authority','AuthorityController')->middleware('auth:api');
});
