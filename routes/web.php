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
Route::get('/', 'HomeController@test');


Route::get('toflokzu/{id?}', 'HomeController@sendToFlokzu');

Route::post('todataejecution', 'HomeController@sendtoDataEjecucion');
Route::get('todatainvoice', 'HomeController@sendtoDataFacture');


Route::post('fromcolliers', 'HomeController@productComerccial');
Route::get('fromcaseres', 'HomeController@productNonComercial');



Route::group(['middleware' => 'cron'], function () {

});