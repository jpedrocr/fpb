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

    Route::get('season', 'SeasonController@index');
    Route::get('season/getFromFPB', 'SeasonController@getFromFPB');

    Route::get('association', 'AssociationController@index');
    Route::get('association/getFromFPB', 'AssociationController@getFromFPB');
    Route::get('association/{association_fpb_id}/season/{season_fpb_id}/getCompetitions', 'AssociationController@getCompetitions');
    Route::get('association/{association_fpb_id}/season/{season_fpb_id}/getCompetitionsFromFPB', 'AssociationController@getCompetitionsFromFPB');
    Route::get('association/{association_fpb_id}/season/{season_fpb_id}/getClubs', 'AssociationController@getClubs');
    Route::get('association/{association_fpb_id}/getClubsFromFPB', 'AssociationController@getClubsFromFPB');

    Route::get('agegroup', 'AgegroupController@index');
    Route::get('competitionlevel', 'CompetitionlevelController@index');

    Route::get('competition', 'CompetitionController@index');
    Route::get('competition/{competition_fpb_id}/getPhases', 'CompetitionController@getPhases');
    Route::get('competition/{competition_fpb_id}/getPhasesFromFPB', 'CompetitionController@getPhasesFromFPB');

    Route::get('phase', 'PhaseController@index');
    Route::get('phase/{phase_fpb_id}/getRounds', 'PhaseController@getRounds');
    Route::get('phase/{phase_fpb_id}/getRoundsFromFPB', 'PhaseController@getRoundsFromFPB');

    Route::get('round', 'RoundController@index');
    Route::get('round/{round_fpb_id}/getGames', 'RoundController@getGames');
    Route::get('round/{round_fpb_id}/getGamesFromFPB', 'RoundController@getGamesFromFPB');

    Route::get('club', 'ClubController@index');
    Route::get('club/{club_fpb_id}/getTeams', 'ClubController@getTeams');
    Route::get('club/{club_fpb_id}/getTeamsFromFPB', 'ClubController@getTeamsFromFPB');
    Route::get('club/{club_fpb_id}/season/{season_fpb_id}/getTeams', 'ClubController@getSeasonTeams');

    Route::get('team', 'TeamController@index');
    Route::get('team/{team_fpb_id}/getCompetitionsFromFPB', 'TeamController@getCompetitionsAndPhasesFromFPB');

    Route::get('game', 'GameController@index');

    /*
        http://fpb.app/api/season/getFromFPB
        http://fpb.app/api/association/getFromFPB
        http://fpb.app/api/association/50/season/55/getCompetitionsFromFPB
        http://fpb.app/api/association/3/season/55/getCompetitionsFromFPB
        http://fpb.app/api/competition/6171/getPhasesFromFPB
        http://fpb.app/api/phase/15045/getRoundsFromFPB
        http://fpb.app/api/round/88236/getGamesFromFPB
        http://fpb.app/api/association/3/getClubsFromFPB
        http://fpb.app/api/club/16/getTeamsFromFPB
        http://fpb.app/api/team/27451/getCompetitionsFromFPB
        http://fpb.app/api/round/88236/getGamesFromFPB
    */

});
