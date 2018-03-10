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

Route::get('logout', 'UserController@LogOut');
Route::group(['middleware' => 'guest'], function(){
	Route::get('login', 'UserController@GetLogin')->name('login');
	Route::post('login', 'UserController@PostLogin');
});

Route::group(['middleware' => 'auth'], function(){

	Route::get('ajax/{ctrl?}/{job?}/{option?}', 'ContentController@AjaxLoadController');

	Route::get('{ctrl?}/{job?}/{option?}', 'ContentController@LoadController');
	Route::post('{ctrl?}/{job?}/{option?}', 'ContentController@PostLoadController');

});
