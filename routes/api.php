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

Route::group([
    'namespace' => 'API'
], function() {
    Route::get('category', 'CategoryController@index');
    Route::get('gender', 'GenderController@index');
    Route::get('age_group', 'AgeGroupController@index');
    Route::get('competition_level', 'CompetitionLevelController@index');
    Route::get('season', 'SeasonController@index');
    Route::get('season/getFromFPB', 'SeasonController@getFromFPB');
    Route::get('association', 'AssociationController@index');
    Route::get('association/getFromFPB', 'AssociationController@getFromFPB');
    Route::get('/competition', 'CompetitionController@index');
    Route::get('association/{association_fpb_id}/season/{season_fpb_id}/competition', 'CompetitionController@indexFromAssociationAndSeason');
    Route::get('association/{association_id}/season/{season_id}/competition/getFromFPB', 'CompetitionController@getFromFPB');
});
