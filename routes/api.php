<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Landlord\Api\NinjaClientWebHookController;

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
Route::get('/', function(){
	return "welcome Landlord API";
});

// need middleware for validation Peronsal Access 
Route::post('client/create', [NinjaClientWebHookController::class, 'create']);
Route::post('client/update', [NinjaClientWebHookController::class, 'update']);