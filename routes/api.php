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
    Route::get('agegroup', 'AgegroupController@index');
    Route::get('competitionlevel', 'CompetitionlevelController@index');

    Route::get('season', 'SeasonController@index');
    Route::post('season/getFromFPB', 'SeasonController@getFromFPB')->name('getSeasonsFromFPB');

    Route::get('association', 'AssociationController@index');
    Route::post('association/getFromFPB', 'AssociationController@getFromFPB')->name('getAssociationsFromFPB');
    Route::get('association/{association}/season/{season}/getCompetitions', 'AssociationController@getCompetitions');
    Route::post('association/{association}/season/{season}/getCompetitionsFromFPB', 'AssociationController@getCompetitionsFromFPB')->name('getCompetitionsFromFPB');
    Route::get('association/{association}/getClubs', 'AssociationController@getClubs');
    Route::post('association/{association}/getClubsFromFPB', 'AssociationController@getClubsFromFPB')->name('getClubsFromFPB');

    Route::get('competition', 'CompetitionController@index');
    Route::get('competition/{competition}/getPhases', 'CompetitionController@getPhases');
    Route::post('competition/{competition}/getPhasesFromFPB', 'CompetitionController@getPhasesFromFPB');

    Route::get('phase', 'PhaseController@index');
    Route::get('phase/{phase}/getRounds', 'PhaseController@getRounds');
    Route::post('phase/{phase}/getRoundsFromFPB', 'PhaseController@getRoundsFromFPB')->name('getRoundsFromFPB');

    Route::get('round', 'RoundController@index');
    Route::get('round/{round}/getGames', 'RoundController@getGames');
    Route::post('round/{round}/getGamesFromFPB', 'RoundController@getGamesFromFPB');

    Route::get('club', 'ClubController@index');
    Route::get('club/{club}/getTeams', 'ClubController@getTeams');
    Route::post('club/{club}/getTeamsFromFPB', 'ClubController@getTeamsFromFPB')->name('getTeamsFromFPB');

    Route::get('team', 'TeamController@index');
    Route::get('team/{team}/getCompetitions', 'TeamController@getCompetitionsAndPhases')->name('getTeamCompetitions');
    Route::post('team/{team}/getCompetitionsFromFPB', 'TeamController@getCompetitionsAndPhasesFromFPB')->name('getTeamCompetitionsFromFPB');

    Route::get('game', 'GameController@index');

    /*
        http://fpb.app/api/season/getFromFPB
        http://fpb.app/api/association/getFromFPB
        http://fpb.app/api/association/50/season/55/getCompetitionsFromFPB
        http://fpb.app/api/association/3/season/55/getCompetitionsFromFPB
        http://fpb.app/api/club/16/getTeamsFromFPB
        http://fpb.app/api/team/27451/getCompetitionsFromFPB
        http://fpb.app/api/round/88236/getGamesFromFPB

        http://fpb.app/api/competition/6171/getPhasesFromFPB
        http://fpb.app/api/phase/15045/getRoundsFromFPB
        http://fpb.app/api/round/88236/getGamesFromFPB
        http://fpb.app/api/association/3/getClubsFromFPB
    */

});
