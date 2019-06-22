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

Route::get('/_debugbar/assets/stylesheets', [
    'as' => 'debugbar-css',
    'uses' => '\Barryvdh\Debugbar\Controllers\AssetController@css'
]);

Route::get('/_debugbar/assets/javascript', [
    'as' => 'debugbar-js',
    'uses' => '\Barryvdh\Debugbar\Controllers\AssetController@js'
]);

Route::get('/_debugbar/open', [
    'as' => 'debugbar-open',
    'uses' => '\Barryvdh\Debugbar\Controllers\OpenController@handler'
]);

Route::get('logout', 'UserController@LogOut');
Route::group(['middleware' => 'guest'], function(){
	Route::get('login', 'UserController@GetLogin')->name('login');
	Route::post('login', 'UserController@PostLogin');
});

Route::group(['middleware' => ['auth', 'auth.active']], function(){

	Route::get('ajax/{ctrl?}/{job?}/{option?}', 'ContentController@AjaxLoadController');

	Route::get('{ctrl?}/{job?}/{option?}', 'ContentController@LoadController');
	Route::post('{ctrl?}/{job?}/{option?}', 'ContentController@PostLoadController');

});
