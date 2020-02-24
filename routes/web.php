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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/capturados', 'HomeController@capturados')->name('capturados');


Route::group(['prefix' => 'pokemon'], function () {



  Route::get('/', 'PokemonController@list')->name('list');


  Route::group(['prefix' => 'captured'], function () {

    Route::get('/', 'CapturedController@list')->name('list');
    Route::post('/', 'CapturedController@insert')->name('insert');
    Route::put('/', 'CapturedController@update')->name('update');
    Route::delete('/', 'CapturedController@delete')->name('delete');

  });


  Route::group(['prefix' => 'evolution-chain'], function () {

    Route::get('/{idPokemon}', 'CapturedController@listEvolution')->name('listEvolution');

  });


});
