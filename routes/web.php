<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentsController;
use App\Http\Controllers\Admin\IdcardController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\GuardianController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\RoutineController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\NoticeboardController;
use App\Http\Controllers\Admin\LibraryController;
use App\Http\Controllers\Admin\FeeController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\FeeScenarioController;
use App\Http\Controllers\Admin\ExamGradeController;
use App\Http\Controllers\Admin\StudentAttendanceController;
use App\Http\Controllers\Admin\TeacherAttendanceController;
use App\Http\Controllers\Admin\EmployeeAttendanceController;
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

Route::get('logout', [UserController::class,'LogOut'])->name('logout');

Route::group(['middleware' => 'guest'], function(){
	Route::get('login', [UserController::class, 'GetLogin'])->name('login');
	Route::post('login', [UserController::class, 'PostLogin'])->name('login.post');
});

Route::group(['middleware' => ['auth', 'auth.active']], function(){

    Route::get('id-card/student', "IdcardController@StudentIdcard");

    Route::get('/', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'GetDashboard'])->name('dashboard');


    Route::prefix('students')->name('students')->group(function(){
        Route::get('/', [StudentsController::class, 'Index'])->name('.index');
        Route::get('/image/{id}', [StudentsController::class, 'GetImage'])->name('.image');
        Route::get('/profile/{id}', [StudentsController::class, 'GetProfile'])->name('.profile');
        Route::get('/edit/{id}', [StudentsController::class, 'EditStudent'])->name('.edit');
        Route::get('/interview/{id}', [StudentsController::class, 'GetInterview'])->name('.interview.get');
        Route::get('/certificate/{action}', [StudentsController::class, 'GetCertificate'])->where('action', 'new|update')->name('.certificate.get');
        Route::post('/interview/{id}', [StudentsController::class, 'UpdateOrCreateInterview'])->name('.interview.update.create');
        Route::post('/certificate', [StudentsController::class, 'PostCertificate'])->name('.certificate.create');
        Route::post('/add', [StudentsController::class, 'AddStudent'])->name('.add');
        Route::post('/leave/{id}', [StudentsController::class, 'PostLeaveStudent'])->name('.leave');
        Route::post('/edit/{id}', [StudentsController::class, 'PostEditStudent'])->name('.edit.post');
    });

    Route::prefix('teacher')->name('teacher')->group(function(){
        Route::get('/', [TeacherController::class, 'GetTeacher'])->name('getteacher');
        Route::get('/image/{id}', [TeacherController::class, 'GetImage'])->name('.image');
        Route::get('/profile/{id}', [TeacherController::class, 'GetProfile'])->name('.profile');
        Route::get('/edit/{id}', [TeacherController::class, 'EditTeacher'])->name('.edit');
        Route::post('/add', [TeacherController::class, 'AddTeacher'])->name('.add');
        Route::post('/edit/{id}', [TeacherController::class, 'PostEditTeacher'])->name('.edit.post');

    });
    
    // Route::get('/teacher', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/employee', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/guardian', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/student-attendance', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/manage-classes', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/manage-sections', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/vendors', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/items', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/vouchers', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/routines', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/student-attendance', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/teacher-attendance', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/employee-attendance', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/manage-subjects', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/exam', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/manage-result', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/noticeboard', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/library', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/fee', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/expense', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/users', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/system-setting', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/fee-scenario', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    Route::get('/exam-grades', [DashboardController::class, 'GetDashboard'])->name('dashboard');

});
