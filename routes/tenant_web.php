<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IdcardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\UserSettingController;
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
use App\Http\Controllers\Admin\ManageStudentResultCtrl;
use App\Http\Controllers\Admin\LibraryController;
use App\Http\Controllers\Admin\NoticeBoardCtrl;
use App\Http\Controllers\Admin\FeesController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\SmsController;
use App\Http\Controllers\Admin\SeatsReportController;
use App\Http\Controllers\Admin\FeeCollectionReportController;
use App\Http\Controllers\Admin\ExamReportController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\FeeScenarioController;
use App\Http\Controllers\Admin\ExamGradesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AcademicSessionController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\AttendanceLeaveController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\QuizResultController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\VistorStudentController;
/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::get('/inactive-tenant', function () {
    return view('tenant.inactive');
})->name('tenant.inactive');

Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('logout', [UserController::class,'LogOut'])->name('logout');

Route::group(['middleware' => 'guest'], function(){
	Route::get('login', [UserController::class, 'GetLogin'])->name('login');
	Route::post('login', [UserController::class, 'PostLogin'])->name('login.post');
});

// Swagger/OpenAPI Documentation
Route::get('/api/documentation', function() {
    return view('swagger');
})->middleware(['auth', 'auth.active', 'role:Developer'])->name('tenant.api.documentation');

Route::group(['middleware' => ['auth', 'auth.active', 'route_has_permission']], function(){

    Route::get('/cmd', function () {
        
        // $output = '';
        // when run cmd on landloard end the tenancy
        // tenancy()->end();

        // Artisan::call('migrate', ['--force' => true]);
        // Get the output of the artisan command
        // $output .= Artisan::output();
        // Artisan::call('tenants:migrate', ['--force' => true]);
        // $output .= Artisan::output();
        // Artisan::call('db:seed', ['--class' => 'UserSeeder', '--force' => true]);
        // $output .= Artisan::output();
        // Artisan::call('db:seed', ['--class' => 'PermissionsSeeder', '--force' => true]);
        // $output .= Artisan::output();
        // Artisan::call('db:seed', ['--class' => 'PermissionsUpdateSeeder', '--force' => true, ]);
        // $output .= Artisan::output();
        // Artisan::call('tenants:seed', ['--class' => 'PermissionsUpdateSeeder', '--force' => true, ]);
        // $output .= Artisan::output();
        // Artisan::call('db:seed', ['--class' => 'NotificationsSettingsSeeder', '--force' => true, ]);
        // $output .= Artisan::output();
        // return response("<pre>$output</pre><h2>✅ Done: Migrations ran successfully.</h2>");
    });

    // Route::get('id-card/student', [IdcardController::class, 'StudentIdcard'])->name('student.card');
    Route::get('/', [DashboardController::class, 'GetDashboard']);
    Route::get('/dashboard', [DashboardController::class, 'GetDashboard'])->name('dashboard');

    Route::prefix('user-settings')->name('user-settings')->group(function(){
        Route::get('/', [UserSettingController::class, 'GetUserSetting'])->name('.index');
        Route::post('/changepwd', [UserSettingController::class, 'UpdatePwd'])->name('.password.update');
        Route::post('/changesession', [UserSettingController::class, 'ChangeSession'])->name('.change.session');
    });

    Route::prefix('students')->name('students')->group(function(){
        Route::get('/', [StudentsController::class, 'Index'])->name('.index');
        Route::get('/{id}/visitor', [StudentsController::class, 'ShowVistor'])->name('.show.visitor');
        Route::post('/{id}/visitor', [StudentsController::class, 'CreateVistor'])->name('.create.visitor');
        Route::get('/guardians/list', [StudentsController::class, 'getGuardians'])->name('.guardians.list');
        Route::get('/grid', [StudentsController::class, 'Grid'])->name('.grid');
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
        Route::get('id-card/{id}', [IdcardController::class, 'StudentIdcard'])->name('.card');
    });

    Route::prefix('teacher')->name('teacher')->group(function(){
        Route::get('/', [TeacherController::class, 'GetTeacher'])->name('.index');
        Route::get('/grid', [TeacherController::class, 'Grid'])->name('.grid');
        Route::get('/image/{id}', [TeacherController::class, 'GetImage'])->name('.image');
        Route::get('/find', [TeacherController::class, 'FindTeacher'])->name('.find');
        Route::get('/profile/{id}', [TeacherController::class, 'GetProfile'])->name('.profile');
        Route::get('/edit/{id}', [TeacherController::class, 'EditTeacher'])->name('.edit');
        Route::post('/add', [TeacherController::class, 'AddTeacher'])->name('.add');
        Route::post('/edit/{id}', [TeacherController::class, 'PostEditTeacher'])->name('.edit.post');

    });

    Route::prefix('employee')->name('employee')->group(function(){
        Route::get('/', [EmployeeController::class, 'GetEmployee'])->name('.index');
        Route::get('/grid', [EmployeeController::class, 'Grid'])->name('.grid');
        Route::get('/image/{id}', [EmployeeController::class, 'GetImage'])->name('.image');
        Route::get('/find', [EmployeeController::class, 'FindEmployee'])->name('.find');
        Route::get('/profile/{id}', [EmployeeController::class, 'GetProfile'])->name('.profile');
        Route::get('/edit/{id}', [EmployeeController::class, 'EditEmployee'])->name('.edit');
        Route::post('/add', [EmployeeController::class, 'AddEmployee'])->name('.add');#store should be used instead of add
        Route::post('/edit/{id}', [EmployeeController::class, 'PostEditEmployee'])->name('.edit.post');#put
    });

    Route::prefix('guardians')->name('guardian')->group(function(){
        Route::get('/', [GuardiansController::class, 'GetGuardian'])->name('.index');
        Route::get('/grid', [GuardiansController::class, 'Grid'])->name('.grid');
        Route::get('/profile/{id}', [GuardiansController::class, 'GetProfile'])->name('.profile');
        Route::get('/edit/{id}', [GuardiansController::class, 'EditGuardian'])->name('.edit');
        Route::post('/add', [GuardiansController::class, 'AddGuardian'])->name('.add');
        Route::post('/edit/{id}', [GuardiansController::class, 'PostEditGuardian'])->name('.edit.post');
    });

    Route::prefix('visitors')->name('visitors')->group(function(){
        Route::get('/', [VistorStudentController::class, 'index'])->name('.index');
        Route::get('/grid', [VistorStudentController::class, 'grid'])->name('.grid');
        Route::get('/profile/{id}', [VistorStudentController::class, 'GetProfile'])->name('.profile');
        Route::post('/create', [VistorStudentController::class, 'create'])->name('.create');
        Route::get('/edit/{id}', [VistorStudentController::class, 'edit'])->name('.edit');
        Route::post('/update/{id}', [VistorStudentController::class, 'update'])->name('.update');
        Route::post('/delete', [VistorStudentController::class, 'delete'])->name('.delete');
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

    Route::prefix('attendance-leave')->name('attendance-leave')->group(function(){
        Route::get('/', [AttendanceLeaveController::class, 'Index'])->name('.index');
        Route::get('/getData', [AttendanceLeaveController::class, 'GetData'])->name('.get.data');
        Route::post('/make', [AttendanceLeaveController::class, 'MakeLeave'])->name('.make');
        Route::get('/edit/{id}', [AttendanceLeaveController::class, 'Edit'])->name('.edit');
        Route::post('/update/{id}', [AttendanceLeaveController::class, 'Update'])->name('.update');
        Route::post('/delete', [AttendanceLeaveController::class, 'Delete'])->name('.delete');
    });

    Route::prefix('manage-subjects')->name('manage-subjects')->group(function(){
        Route::get('/', [ManageSubjects::class, 'GetSubject'])->name('.index');
        Route::get('/edit/{id}', [ManageSubjects::class, 'EditSubject'])->name('.edit');
        Route::post('/add', [ManageSubjects::class, 'AddSubject'])->name('.add');
        Route::post('/edit/{id}', [ManageSubjects::class, 'PostEditSubject'])->name('.edit.post');
    });

    Route::prefix('student-migrations')->name('student-migrations')->group(function(){
        Route::get('/', [StudentMigrationsController::class, 'Index'])->name('.index');
        Route::get('/students', [StudentMigrationsController::class, 'GetStudents'])->name('.get');
        Route::post('/create', [StudentMigrationsController::class, 'PostMigration'])->name('.create');
    });

    Route::prefix('exam')->name('exam')->group(function(){
        Route::get('/', [ExamController::class, 'Index'])->name('.index');
        Route::get('/edit/{id}', [ExamController::class, 'EditExam'])->name('.edit');
        Route::post('/add', [ExamController::class, 'AddExam'])->name('.add');
        Route::post('/edit/{id}', [ExamController::class, 'PostEditExam'])->name('.edit.post');
    });

    Route::prefix('quizzes')->name('quizzes')->group(function(){
        Route::get('/', [QuizController::class, 'Index'])->name('.index');
        Route::get('/getData', [QuizController::class, 'GetData'])->name('.get.data');
        Route::post('/create', [QuizController::class, 'Create'])->name('.create');
        Route::get('/edit/{id}', [QuizController::class, 'Edit'])->name('.edit');
        Route::post('/update/{id}', [QuizController::class, 'Update'])->name('.update');
        Route::post('/delete', [QuizController::class, 'Delete'])->name('.delete');
    });

    Route::prefix('quizresult')->name('quizresult')->group(function(){
        Route::get('/{id}', [QuizResultController::class, 'Index'])->name('.index');
        Route::post('/create', [QuizResultController::class, 'Create'])->name('.create');
        // Route::get('/edit/{id}', [QuizResultController::class, 'Edit'])->name('.edit');
        // Route::post('/update/{id}', [QuizResultController::class, 'Update'])->name('.update');
        // Route::post('/delete', [QuizResultController::class, 'Delete'])->name('.delete');
    });

    Route::prefix('manage-result')->name('manage-result')->group(function(){
        Route::get('/', [ManageStudentResultCtrl::class, 'Index'])->name('.index');
        Route::get('/make', [ManageStudentResultCtrl::class, 'MakeResult'])->name('.make');
        Route::get('/resultattributes', [ManageStudentResultCtrl::class, 'ResultAttributes'])->name('.attributes');
        Route::get('/maketranscript', [ManageStudentResultCtrl::class, 'MakeTranscript'])->name('.maketranscript');
        Route::post('/maketranscript', [ManageStudentResultCtrl::class, 'SaveTranscript'])->name('.maketranscript.create');
        Route::post('/make', [ManageStudentResultCtrl::class, 'UpdateResult'])->name('.result');
    });

    Route::prefix('library')->name('library')->group(function(){
        Route::get('/', [LibraryController::class, 'GetLibrary'])->name('.index');
        Route::get('/edit/{id}', [LibraryController::class, 'EditBook'])->name('.edit');
        Route::post('/add', [LibraryController::class, 'AddBook'])->name('.add');
        Route::post('/edit/{id}', [LibraryController::class, 'PostEditBook'])->name('.edit.post');  
    });

    Route::prefix('noticeboard')->name('noticeboard')->group(function(){
        Route::get('/', [NoticeBoardCtrl::class, 'Index'])->name('.index');
        Route::post('/create', [NoticeBoardCtrl::class, 'CreateNotice'])->name('.create');
        Route::post('/delete', [NoticeBoardCtrl::class, 'DeleteNotice'])->name('.delete');
    });

     Route::prefix('fee')->name('fee')->group(function(){
        Route::get('/', [FeesController::class, 'Index'])->name('.index');
        Route::get('/group-chalan/{guardian_id}', [FeesController::class, 'GetGroupInvoice'])->name('.group.chalan.print');
        Route::get('/bulk-print-invoice', [FeesController::class, 'BulkPrintInvoice'])->name('.bulk.print.invoice');
        Route::get('/create ', [FeesController::class, 'FindInvoice'])->name('.create');
        Route::post('/create/{id}', [FeesController::class, 'CreateInvoice'])->name('.create.store');
        Route::post('/bulk-create-invoice', [FeesController::class, 'CreateBulkInvoice'])->name('.bulk.create.invoice');
        Route::post('/bulk-create-group-invoice', [FeesController::class, 'CreateGroupInvoice'])->name('.bulk.create.group.invoice');
        Route::get('/collect', [FeesController::class, 'GetInvoice'])->name('.get.invoice.collect');
        Route::post('/collect ', [FeesController::class, 'CollectInvoice'])->name('.collect.store');
        Route::get('/update ', [FeesController::class, 'GetStudentFee'])->name('.get.student.fee');
        Route::post('/update ', [FeesController::class, 'UpdateFee'])->name('.update');
        Route::get('/chalan/{id}', [FeesController::class, 'PrintChalan'])->name('.chalan.print');
        Route::get('/edit-invoice', [FeesController::class, 'GetEditInvoice'])->name('.edit.invoice');
        Route::post('/edit-invoice', [FeesController::class, 'PostEditInvoice'])->name('.edit.invoice.post');
        Route::get('/findstu', [FeesController::class, 'FindStudent'])->name('.findstu');
        Route::get('/invoice/{id} ', [FeesController::class, 'PrintInvoice'])->name('.invoice.print');
    });

    Route::prefix('expense')->name('expense')->group(function(){
        Route::get('/', [ExpenseController::class, 'Index'])->name('.index');
        Route::get('/edit/{id}', [ExpenseController::class, 'EditExpense'])->name('.edit');
        Route::get('/summary', [ExpenseController::class, 'Summary'])->name('.summary');
        Route::post('/add', [ExpenseController::class, 'AddExpense'])->name('.add');
        Route::post('/edit/{id}', [ExpenseController::class, 'PostEditExpense'])->name('.edit.post');
    });

    Route::prefix('smsnotifications')->name('smsnotifications')->group(function(){
        Route::get('/', [SmsController::class, 'Index'])->name('.index');
        Route::post('/send', [SmsController::class, 'SendSms'])->name('.sendsms');
        Route::post('/send-bulk', [SmsController::class, 'SendBulkSms'])->name('.sendbulksms');
        Route::post('/history', [SmsController::class, 'History'])->name('.history');
    });

    Route::get('seats-report', [SeatsReportController::class, 'GetSeatsStatus'])->name('seatsreport');

    Route::prefix('fee-collection-reports')->name('fee-collection-reports')->group(function(){
        Route::get('/', [FeeCollectionReportController::class, 'Index'])->name('.index');
        Route::post('/fee-receipts-statment', [FeeCollectionReportController::class, 'FeeReceiptStatment'])->name('.fee.receipts.statment');
        Route::post('/daily-fee-collection', [FeeCollectionReportController::class, 'DailyFeeCollection'])->name('.daily.fee.collection');
        Route::post('/freeship-students', [FeeCollectionReportController::class, 'FreeshipStudents'])->name('.free.ship.students');
        Route::post('/unpaid-fee-statment', [FeeCollectionReportController::class, 'UnpaidFeeStatment'])->name('.unpaid.fee.statment');
        Route::post('/yearly-collection-statment', [FeeCollectionReportController::class, 'YearlyCollectionStatment'])->name('.yearly.collection.statment');
    });

    Route::prefix('exam-reports')->name('exam-reports')->group(function(){
        Route::get('/', [ExamReportController::class, 'Index'])->name('.index');
        Route::post('/', [ExamReportController::class, 'UpdateRank'])->name('.update.rank');
        Route::post('/tabulation-sheet', [ExamReportController::class, 'GetExamTabulation'])->name('.tabulation.sheet');
        Route::post('/award-list', [ExamReportController::class, 'AwardList'])->name('.award.list');
        Route::post('/average-result', [ExamReportController::class, 'AverageResult'])->name('.average.result');
        Route::get('/findstu', [ExamReportController::class, 'FindStudent'])->name('.find.student');
        Route::post('/result-transcript', [ExamReportController::class, 'ResultTranscript'])->name('.result.transcript');
    });


    Route::prefix('msg-notifications')->name('msg-notifications')->group(function(){
        Route::get('/', [NotificationsController::class, 'index'])->name('.index');
        Route::post('/get/data', [NotificationsController::class, 'getData'])->name('.get.data');
        Route::post('/messsage-send', [NotificationsController::class, 'send'])->name('.send');
        Route::get('/logs', [NotificationsController::class, 'msgLog'])->name('.msg.log');
    });

    Route::prefix('notifications')->name('notifications')->group(function(){
        Route::get('/', [NotificationsController::class, 'log'])->name('.log');
        Route::post('/{id}', [NotificationsController::class, 'logRead'])->name('.log.read');
    });

    Route::group(['middleware' => 'role:Developer','prefix' => 'academic-sessions','as' => 'academic-sessions'], function () {
        Route::get('/', [AcademicSessionController::class, 'index'])->name('.index');
        Route::post('/create', [AcademicSessionController::class, 'create'])->name('.create');
        // Route::get('/edit/{id}', [AcademicSessionController::class, 'edit'])->name('edit');
        // Route::post('/update', [AcademicSessionController::class, 'update'])->name('update');
        // Route::post('/delete', [AcademicSessionController::class, 'delete'])->name('delete');
    });

    Route::prefix('users')->name('users')->group(function(){
        Route::get('/', [UsersController::class, 'index'])->name('.index');
        Route::post('/create', [UsersController::class, 'create'])->name('.create');
        Route::get('/edit/{id}', [UsersController::class, 'edit'])->name('.edit');
        Route::post('/edit/{id}', [UsersController::class, 'update'])->name('.update');
        Route::get('/login/{id}', [UsersController::class, 'loginAsUser'])->middleware('role:Developer');
    });

    Route::prefix('roles')->name('roles')->group(function(){
        Route::get('/', [RoleController::class, 'index'])->name('.index');
        Route::post('/create', [RoleController::class, 'create'])->name('.create');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('.edit');
        Route::post('/update/{id}', [RoleController::class, 'update'])->name('.update');
    });

    Route::prefix('system-setting')->name('system-setting')->group(function(){
        Route::get('/', [SystemSettingController::class, 'GetSetting'])->name('.index');
        Route::get('/logo', [SystemSettingController::class, 'GetLog'])->name('.logo');
        Route::post('/update', [SystemSettingController::class, 'UpdateSetting'])->name('.update');
        Route::post('/module-permissions', [SystemSettingController::class, 'UpdateModulePermissions'])->name('.module.permissions.update');
        Route::get('/print-invoice-history', [SystemSettingController::class, 'PrintInvoiceHistory'])->name('.print.invoice.history');
        Route::post('/history', [SystemSettingController::class, 'History'])->name('.history');
        Route::post('/notification-settings/{id}', [SystemSettingController::class, 'NotificationSettings'])->name('.notification.settings');
    });

    Route::prefix('fee-scenario')->name('fee-scenario')->group(function(){
        Route::get('/', [FeeScenarioController::class, 'Index'])->name('.index');
        Route::post('/update', [FeeScenarioController::class, 'UpdateScenario'])->name('.update.scenario');
    });

    Route::prefix('exam-grades')->name('exam-grades')->group(function(){
        Route::get('/', [ExamGradesController::class, 'Index'])->name('.index');
        Route::post('/update', [ExamGradesController::class, 'UpdateGrade'])->name('.update');
    });
});
