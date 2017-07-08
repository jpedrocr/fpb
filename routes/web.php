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
    CRUD::resource('age_group', 'AgeGroupCrudController');
    CRUD::resource('season', 'SeasonCrudController');
    CRUD::resource('association', 'AssociationCrudController');
});

Route::get('/', function () {
    return view('welcome');
});
