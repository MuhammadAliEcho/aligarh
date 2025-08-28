<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Guardian\UserController as GuardianUserController;
use App\Http\Controllers\Api\Guardian\HomeController;
use App\Http\Controllers\Api\Guardian\StudentProfileController;
use App\Http\Controllers\Api\Guardian\StudentFeeController;
use App\Http\Controllers\Api\Guardian\ExamController;
use App\Http\Controllers\Api\Guardian\RoutineController;
use App\Http\Controllers\Api\Guardian\NoticeBoardController;
use App\Http\Controllers\Api\Guardian\QuizController;
use App\Http\Controllers\Api\Guardian\StudentController;
use App\Http\Controllers\Api\Guardian\AttendanceController as GuardianAttendanceController;

use App\Http\Controllers\Api\TMS\UserController as TMSUserController;
use App\Http\Controllers\Api\TMS\AttendanceController;

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
Route::prefix('guardian')->group(function () {

    Route::post('login', [GuardianUserController::class, 'Login'])->name('guardian.login');

    Route::middleware('auth:api')->group(function () {

        Route::middleware(['scope:guardian', 'auth.active'])->group(function () {

            Route::post('/students', [StudentController::class, 'getStudents']);
            Route::get('/attendance/{student_id}', [GuardianAttendanceController::class, 'getAttendance']);

            Route::get('home', [HomeController::class, 'Home']);
            Route::post('student-profile', [StudentProfileController::class, 'GetShortProfile']);
            Route::post('student-invoices', [StudentFeeController::class, 'GetFeeInvoices']);
            Route::post('student-exams', [ExamController::class, 'GetExams']);
            Route::get('routines', [RoutineController::class, 'GetRoutines']);
            Route::get('noticeboard', [NoticeBoardController::class, 'GetNotices']);
            Route::get('quiz/{student_id}', [QuizController::class, 'GetQuiz']);

            Route::get('user', function (Request $request) {
                return response()->json([
                    'User' => $request->user(),
                    'Profile' => \App\Guardian::find($request->user()->foreign_id),
                ]);
            });

            Route::get('students/image/{image}', [StudentProfileController::class, 'GetImage']);
        });

        Route::post('logout', [GuardianUserController::class, 'Logout']);
    });
});


Route::prefix('tms')->group(function () {

    Route::post('login', [TMSUserController::class, 'Login'])->name('tms.login');

    Route::middleware('auth:api')->group(function () {

        Route::middleware(['scope:tms', 'auth.active'])->group(function () {

            Route::get('user', function (Request $request) {
                return response()->json(['User' => $request->user()]);
            });

            Route::post('attendance', [AttendanceController::class, 'Attendance']);
            Route::post('cachedata', [AttendanceController::class, 'CacheData']);
        });

        Route::post('logout', [TMSUserController::class, 'Logout']);
    });
});
