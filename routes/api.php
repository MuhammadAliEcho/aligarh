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
// FOR GUARdian APP Portal API
Route::group(['prefix' => 'guardian', 'namespace'	=>	'Api\Guardian'], function(){

	Route::post('login', 'UserController@Login')->name('guardian.login');

	Route::group(['middleware'  =>  'auth:api'], function(){

		Route::group(['middleware'  =>  ['scope:guardian', 'auth.active']], function(){

			Route::get('home', 'HomeController@Home');
			Route::post('student-profile', 'StudentProfileController@GetShortProfile');
			Route::post('student-invoices', 'StudentFeeController@GetFeeInvoices');
			Route::post('student-exams', 'ExamController@GetExams');
			Route::get('routines', 'RoutineController@GetRoutines');
			Route::get('noticeboard', 'NoticeBoardController@GetNotices');
			Route::get('quiz/{student_id}', 'QuizController@GetQuiz');

			Route::get('user', function(Request $request){
//                return $request->user()->token()->id;
				return response()->json(['User'	=>	$request->user(), 'Profile'	=>	App\Guardian::find($request->user()->foreign_id)]);
			});

			Route::get('students/image/{image}', 'StudentProfileController@GetImage');

		});

		Route::post('logout', 'UserController@Logout');
	});

//		Route::delete('logout/{token_id}', '\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@destroy');
});

// FOR TMS APP API
Route::group(['prefix'	=>	'tms', 'namespace'	=>	'Api\TMS'],	function(){

	Route::post('login', 'UserController@Login')->name('tms.login');

	Route::group(['middleware'  =>  'auth:api'], function(){

		Route::group(['middleware'  =>  ['scope:tms', 'auth.active']], function(){

			Route::get('user', function(Request $request){
				return response()->json(['User' => $request->user()]);
			});

			Route::post('attendance', 'AttendanceController@Attendance');

			Route::post('cachedata', 'AttendanceController@CacheData');

		});

		Route::post('logout', 'UserController@Logout');

	});

});
