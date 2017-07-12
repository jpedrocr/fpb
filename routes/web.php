<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['admin'],
    'namespace' => 'Admin'
], function() {
    // your CRUD resources and other admin routes here
    CRUD::resource('category', 'CategoryCrudController');
    CRUD::resource('gender', 'GenderCrudController');
    CRUD::resource('agegroup', 'AgegroupCrudController');
    CRUD::resource('competitionlevel', 'CompetitionlevelCrudController');
    CRUD::resource('season', 'SeasonCrudController');
    CRUD::resource('association', 'AssociationCrudController');
    CRUD::resource('competition', 'CompetitionCrudController');
    CRUD::resource('phase', 'PhaseCrudController');
    CRUD::resource('round', 'RoundCrudController');
    CRUD::resource('club', 'ClubCrudController');
    CRUD::resource('team', 'TeamCrudController');
    CRUD::resource('game', 'GameCrudController');
});

Route::get('/', function () {
    return view('welcome');
});
