<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentsController;
use App\Http\Controllers\Admin\ManageClasses;
use App\Http\Controllers\Admin\ManageSections;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\GuardiansController;
use App\Http\Controllers\Admin\VendorsController;
use App\Http\Controllers\Admin\ItemsController;
use App\Http\Controllers\Admin\VouchersController;
use App\Http\Controllers\Admin\ManageRoutine;
use App\Http\Controllers\Admin\StudentAttendanceCtrl;
use App\Http\Controllers\Admin\TeacherAttendanceCtrl;
use App\Http\Controllers\Admin\EmployeeAttendanceCtrl;
use App\Http\Controllers\Admin\ManageSubjects;
use App\Http\Controllers\Admin\StudentMigrationsController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\NoticeboardController;
use App\Http\Controllers\Admin\LibraryController;
use App\Http\Controllers\Admin\FeeController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\FeeScenarioController;
use App\Http\Controllers\Admin\ExamGradeController;
use App\Http\Controllers\IdcardController;
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
        Route::get('/', [TeacherController::class, 'GetTeacher'])->name('.index');
        Route::get('/image/{id}', [TeacherController::class, 'GetImage'])->name('.image');
        Route::get('/profile/{id}', [TeacherController::class, 'GetProfile'])->name('.profile');
        Route::get('/edit/{id}', [TeacherController::class, 'EditTeacher'])->name('.edit');
        Route::post('/add', [TeacherController::class, 'AddTeacher'])->name('.add');
        Route::post('/edit/{id}', [TeacherController::class, 'PostEditTeacher'])->name('.edit.post');

    });

    Route::prefix('employee')->name('employee')->group(function(){
        Route::get('/', [EmployeeController::class, 'GetEmployee'])->name('.index');
        Route::get('/image/{id}', [EmployeeController::class, 'GetImage'])->name('.image');
        Route::get('/profile/{id}', [EmployeeController::class, 'GetProfile'])->name('.profile');
        Route::get('/edit/{id}', [EmployeeController::class, 'EditEmployee'])->name('.edit');
        Route::post('/add', [EmployeeController::class, 'AddEmployee'])->name('.add');#store should be used instead of add
        Route::post('/edit/{id}', [EmployeeController::class, 'PostEditEmployee'])->name('.edit.post');#put
    });

    Route::prefix('guardians')->name('guardian')->group(function(){
        Route::get('/', [GuardiansController::class, 'GetGuardian'])->name('.index');
        Route::get('/profile/{id}', [GuardiansController::class, 'GetProfile'])->name('.profile');
        Route::get('/edit/{id}', [GuardiansController::class, 'EditGuardian'])->name('.edit');
        Route::post('/add', [GuardiansController::class, 'AddGuardian'])->name('.add');
        Route::post('/edit/{id}', [GuardiansController::class, 'PostEditGuardian'])->name('.edit.post');
    });
    
    Route::prefix('manage-classes')->name('manage-classes')->group(function(){
        Route::get('/', [ManageClasses::class, 'GetClasses'])->name('.index');
        Route::get('/edit/{id}', [ManageClasses::class, 'EditClass'])->name('.edit');
        Route::post('/add', [ManageClasses::class, 'AddClass'])->name('.add');
        Route::post('/edit/{id}', [ManageClasses::class, 'PostEditClass'])->name('.edit.post');
    });

    Route::prefix('manage-sections')->name('manage-sections')->group(function(){
        Route::get('/', [ManageSections::class, 'GetSections'])->name('.index');
        Route::get('/edit/{id}', [ManageSections::class, 'EditSection'])->name('.edit');
        Route::post('/add', [ManageSections::class, 'AddSection'])->name('.add');
        Route::post('/edit/{id}', [ManageSections::class, 'PostEditSection'])->name('.edit.post');
    });
    
    Route::prefix('vendors')->name('vendors')->group(function(){
        Route::get('/', [VendorsController::class, 'GetVendor'])->name('.index');
        Route::get('/edit/{id}', [VendorsController::class, 'EditVendor'])->name('.edit');
        Route::post('/add', [VendorsController::class, 'AddVendor'])->name('.add');
        Route::post('/edit/{id}', [VendorsController::class, 'PostEditVendor'])->name('.edit.post');

    });

    Route::prefix('items')->name('items')->group(function(){
        Route::get('/', [ItemsController::class, 'GetItem'])->name('.index');
        Route::get('/edit/{id}', [ItemsController::class, 'EditItem'])->name('.edit');
        Route::post('/add', [ItemsController::class, 'AddItem'])->name('.add');
        Route::post('/edit/{id}', [ItemsController::class, 'PostEditItem'])->name('.edit.post');

    });

    Route::prefix('vouchers')->name('vouchers')->group(function(){
        Route::get('/', [VouchersController::class, 'GetVoucher'])->name('.index');
        Route::get('/edit/{id}', [VouchersController::class, 'EditVoucher'])->name('.edit');
        Route::get('/details/{id}', [VouchersController::class, 'GetDetails'])->name('.detail');
        Route::post('/add', [VouchersController::class, 'AddVoucher'])->name('.add');
        Route::post('/edit/{id}', [VouchersController::class, 'PostEditVoucher'])->name('.edit.post');
    });

    Route::prefix('routines')->name('routines')->group(function(){
        Route::get('/', [ManageRoutine::class, 'GetRoutine'])->name('.index');
        Route::get('/edit/{id}', [ManageRoutine::class, 'EditRoutine'])->name('.edit');
        Route::post('/delete', [ManageRoutine::class, 'DeleteRoutine'])->name('.delete');
        Route::post('/add', [ManageRoutine::class, 'AddRoutine'])->name('.add');
        Route::post('/edit/{id}', [ManageRoutine::class, 'PostEditRoutine'])->name('.edit.post');
    });

    Route::prefix('student-attendance')->name('student-attendance')->group(function(){
        Route::get('/', [StudentAttendanceCtrl::class, 'Index'])->name('.index');
        Route::get('/make', [StudentAttendanceCtrl::class, 'MakeAttendance'])->name('.make');
        Route::get('/report', [StudentAttendanceCtrl::class, 'AttendanceReport'])->name('.report');
        Route::post('/make', [StudentAttendanceCtrl::class, 'UpdateAttendance'])->name('.make.post');
    });

    Route::prefix('teacher-attendance')->name('teacher-attendance')->group(function(){
        Route::get('/', [TeacherAttendanceCtrl::class, 'Index'])->name('.index');
        Route::get('/make', [TeacherAttendanceCtrl::class, 'MakeAttendance'])->name('.make');
        Route::get('/report', [TeacherAttendanceCtrl::class, 'AttendanceReport'])->name('.report');
        Route::post('/make', [TeacherAttendanceCtrl::class, 'UpdateAttendance'])->name('.make.post');
    });

    Route::prefix('employee-attendance')->name('employee-attendance')->group(function(){
        Route::get('/', [EmployeeAttendanceCtrl::class, 'Index'])->name('.index');
        Route::get('/make', [EmployeeAttendanceCtrl::class, 'MakeAttendance'])->name('.make');
        Route::get('/report', [EmployeeAttendanceCtrl::class, 'AttendanceReport'])->name('.report');
        Route::post('/make', [EmployeeAttendanceCtrl::class, 'UpdateAttendance'])->name('.make.post');
    });

    Route::prefix('manage-subjects')->name('manage-subjects')->group(function(){
        Route::get('/', [ManageSubjects::class, 'GetSubject'])->name('.index');
        Route::get('/edit/{id}', [ManageSubjects::class, 'EditSubject'])->name('.edit');
        Route::post('/add', [ManageSubjects::class, 'AddSubject'])->name('.add');
        Route::post('/edit/{id}', [ManageSubjects::class, 'PostEditSubject'])->name('.edit.post');
    });

    Route::prefix('student-migrations')->name('student-migrations')->group(function(){
        Route::get('/', [StudentMigrationsController::class, 'Index'])->name('.index');
        Route::get('/students', [StudentMigrationsController::class, 'GetStudents'])->name('.create');
        Route::post('/create', [StudentMigrationsController::class, 'PostMigration'])->name('.create.post');
    });
    // Route::get('/exam', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    // Route::get('/manage-result', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    // Route::get('/noticeboard', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    // Route::get('/library', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    // Route::get('/fee', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    // Route::get('/expense', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    // Route::get('/users', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    // Route::get('/system-setting', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    // Route::get('/fee-scenario', [DashboardController::class, 'GetDashboard'])->name('dashboard');
    // Route::get('/exam-grades', [DashboardController::class, 'GetDashboard'])->name('dashboard');

});
