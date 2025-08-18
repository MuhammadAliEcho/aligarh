<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class RoleController extends Controller
{
	public function index(Request $request)
	{
		$data = [];
		if ($request->ajax()) {
			return DataTables::eloquent(
				Role::select('id', 'name', 'created_at')->notDeveloper()
			)
				->editColumn('created_at', function ($role) {
					return $role->created_at->format('Y-m-d');
				})
				->make(true);
		}
		$data['content'] = null;
		$data['permissions'] = $this->getPermissions();
		return view('admin.roles', $data);
	}


	public function create(Request $request)
	{

		$request->validate([
			'name' => 'required|unique:roles,name',
			'permissions.*' => 'exists:permissions,name',
		], [
			'permissions.*.exists' => 'The selected permission is invalid.',
		]);

		DB::beginTransaction();
		try {
			$Role =	Role::create(['name' => $request->input('name'), 'guard_name' => 'web']);
			$Role->syncPermissions($request->input('permissions'));
			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
			return redirect('roles')->with([
				'toastrmsg' => [
					'type' => 'error', 
					'title'  =>  'Roles',
					'msg' =>  'There was an issue while Updating Role'
				]
			]);
		}

		return redirect('roles')->with([
        'toastrmsg' => [
          'type' 	=> 'success', 
          'title'  	=>  'Role Registration',
          'msg' 	=>  'Registration Successfull'
          ]
      	]);
	}

	public function edit($id)
	{
		$role = Role::notDeveloper()->findOrFail($id); 
		$rolePermissions = $role->permissions->pluck('name')->toArray();
		$permissions = $this->getPermissions();


      	return view('admin.edit_role', compact('role', 'rolePermissions', 'permissions'));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'permissions.*' => 'exists:permissions,name',
		], [
			'permissions.*.exists' => 'The selected permission is invalid.',
		]);

		DB::beginTransaction();
		try {
			$Role = Role::NotDeveloper()->findOrFail($id);
			$Role->syncPermissions($request->input('permissions'));
			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
			return redirect('roles')->with([
				'toastrmsg' => [
					'type' => 'error', 
					'title'  =>  'Roles',
					'msg' =>  'There was an issue while Updating Role'
				]
			]);
		}
		return redirect('roles')->with([
        'toastrmsg' => [
          'type' 	=> 'success', 
          'title'  	=>  'Role Updated',
          'msg' 	=>  'Updated Successfull'
          ]
      	]);
	}

	// public function delete(Request $request, $id)
	// {
	// 	$Role = Role::NotDeveloper()->findOrFail($id);

	// 	if ($Role->users()->count('id')) {
	// 		return response()->json(['message' => "Sorry users have this Role " . $Role->name], 422);
	// 	}

	// 	$Role->syncPermissions([]);
	// 	$Role->delete();
	// 	return response()->json(['success' => 'Role Deleted successfully']);
	// }

	private function getPermissions()
	{
		return [
			'Dashboard & Settings' => [
				'dashboard' => 'Dashboard',
				'dashboard.top_content' => 'Show Total Students, Teacher etc..',
				'dashboard.timeline' => 'Show TimeLins (Notice Board)',
				'dashboard.monthly_attendance' => 'Show Monthly Attendance',
				'dashboard.fee_Collection' => 'Show Fee Collection',
				'dashboard.monthly_expenses' => 'Show Monthly Expenses',
				'student.card' => 'Student Card',
				'user-settings.index' => 'User Settings View',
				'user-settings.password.update' => 'Password Update',
				'user-settings.change.session' => 'Change Session',
			],
			'Users' => [
				'users.index' => 'User View',
				'users.create' => 'User Create',
				'users.edit' => 'User Edit',
				'users.update' => 'User Update',
				'users.update.update_password' => 'Update Password (User Update)'
			],
			'Roles' => [
				'roles.index' => 'Role View',
				'roles.create' => 'Role Create',
				'roles.edit' => 'Role Edit',
				'roles.update' => 'Role update',
			],
			'Students' => [
				'students.index' => 'Students View',
				'students.grid' => 'Students Gird View',
				'students.add' => 'Students Create',
				'students.edit' => 'Students Edit',
				'students.card' => 'Student View Card',
				'students.class_edit' => 'Edit Class',
				'students.edit.post' => 'Students Update',
				'students.profile' => 'Students Profile',
				'students.image' => 'Students Image',
				'students.interview.get' => 'Interview View',
				'students.interview.update.create' => 'Interview Update',
				'students.certificate.get' => 'Certificate View',
				'students.certificate.create' => 'Certificate Create',
				'students.leave' => 'Students Leave',
			],
			'Teachers' => [
				'teacher.index' => 'Teachers View',
				'teacher.add' => 'Teachers Create',
				'teacher.edit' => 'Teachers Edit',
				'teacher.edit.post' => 'Teachers Update',
				'teacher.profile' => 'Teachers Profile',
				'teacher.image' => 'Teachers Image',
				'teacher.find' => 'Find Teachers',
			],
			'Employees' => [
				'employee.index' => 'Employees View',
				'employee.add' => 'Employees Create',
				'employee.edit' => 'Employees Edit',
				'employee.edit.post' => 'Employees Update',
				'employee.profile' => 'Employees Profile',
				'employee.image' => 'Employees Image',
				'employee.find' => 'Find Employees',
			],
			'Guardians' => [
				'guardian.index' => 'Guardians View',
				'guardian.add' => 'Guardians Create',
				'guardian.edit' => 'Guardians Edit',
				'guardian.edit.post' => 'Guardians Update',
				'guardian.profile' => 'Guardians Profile',
			],
			'Classes & Sections' => [
				'manage-classes.index' => 'Classes View',
				'manage-classes.add' => 'Classes Create',
				'manage-classes.edit' => 'Classes Edit',
				'manage-classes.edit.post' => 'Classes Update',
				'manage-sections.index' => 'Sections View',
				'manage-sections.add' => 'Sections Create',
				'manage-sections.edit' => 'Sections Edit',
				'manage-sections.edit.post' => 'Sections Update',
			],
			'Subjects' => [
				'manage-subjects.index' => 'Subjects View',
				'manage-subjects.add' => 'Subjects Create',
				'manage-subjects.edit' => 'Subjects Edit',
				'manage-subjects.edit.post' => 'Subjects Update',
			],
			'Vendors & Items' => [
				'vendors.index' => 'Vendors View',
				'vendors.add' => 'Vendors Create',
				'vendors.edit' => 'Vendors Edit',
				'vendors.edit.post' => 'Vendors Update',
				'items.index' => 'Items View',
				'items.add' => 'Items Create',
				'items.edit' => 'Items Edit',
				'items.edit.post' => 'Items Update',
			],
			'Vouchers' => [
				'vouchers.index' => 'Vouchers View',
				'vouchers.add' => 'Vouchers Create',
				'vouchers.edit' => 'Vouchers Edit',
				'vouchers.edit.post' => 'Vouchers Update',
				'vouchers.detail' => 'Vouchers Detail',
			],
			'Routines' => [
				'routines.index' => 'Routines View',
				'routines.add' => 'Routines Create',
				'routines.edit' => 'Routines Edit',
				'routines.edit.post' => 'Routines Update',
				'routines.delete' => 'Routines Delete',
			],
			'Attendance' => [
				'student-attendance.index' => 'Student Attendance View',
				'student-attendance.make' => 'Student Attendance Get',
				'student-attendance.make.post' => 'Student Attendance Make',
				'student-attendance.report' => 'Student Attendance Report',
				'teacher-attendance.index' => 'Teacher Attendance View',
				'teacher-attendance.make' => 'Teacher Attendance Get',
				'teacher-attendance.make.post' => 'Teacher Attendance Make',
				'teacher-attendance.report' => 'Teacher Attendance Report',
				'employee-attendance.index' => 'Employee Attendance View',
				'employee-attendance.make' => 'Employee Attendance Get',
				'employee-attendance.make.post' => 'Employee Attendance Make',
				'employee-attendance.report' => 'Employee Attendance Report',
			],
			'Attendance Leave' => [
				'attendance-leave.index' => 'Leave View',
				'attendance-leave.get.data' => 'get Data',
				'attendance-leave.make' => 'Leave Make',
				'attendance-leave.edit' => 'Leave Edit',
				'attendance-leave.update' => 'Leave Update',
				'attendance-leave.delete' => 'Leave Delete',
			],
			'Student Migrations' => [
				'student-migrations.index' => 'Migrations View',
				'student-migrations.get' => 'Migrations Get',
				'student-migrations.create' => 'Migrations Create',
			],
			'Exams & Results' => [
				'exam.index' => 'Exams View',
				'exam.add' => 'Exams Create',
				'exam.edit' => 'Exams Edit',
				'exam.edit.post' => 'Exams Update',
				'manage-result.index' => 'Results View',
				'manage-result.make' => 'Results Make',
				'manage-result.attributes' => 'Results Attributes',
				'manage-result.maketranscript' => 'Make Transcript',
				'manage-result.maketranscript.create' => 'Create Transcript',
				'manage-result.result' => 'View Result',
			],

			'Quizzes' => [
				'quizzes.index' => 'Quizzess View',
				'quizzes.get.data' => 'Get Data',
				'quizzes.create' => 'Quiz Create',
				'quizzes.edit' => 'Quiz Edit',
				'quizzes.update' => 'Quiz Update',
				'quizzes.delete' => 'Quiz Delete',

				'quizresult.index' => 'Quiz Result View',
				'quizresult.create' => 'Quiz Result Create',
				// .....
			],
			'Library' => [
				'library.index' => 'Library View',
				'library.add' => 'Library Create',
				'library.edit' => 'Library Edit',
				'library.edit.post' => 'Library Update',
			],
			'Notice Board' => [
				'noticeboard.index' => 'Notice View',
				'noticeboard.create' => 'Notice Create',
				'noticeboard.delete' => 'Notice Delete',
			],
			'Fee Management' => [
				'fee.index' 				=> 'Fee View',
				'fee.create' 				=> 'Get Student',
				'fee.create.store' 			=> 'Fee Create',
				'fee.get.invoice.collect' 	=> 'Get Invoice Collect',
				'fee.collect.store' 		=> 'Store Invoice Collect',
				'fee.edit.invoice' 		=> 'Edit Invoice',
				'fee.edit.invoice.post' => 'Update Invoice',
				'fee.get.student.fee' 	=> 'Get Student Fee',
				'fee.update' 			=> 'Student Fee Update',
				'fee.chalan.print' 		=> 'Chalan Print',
				'fee.invoice.print' 	=> 'Invoice Print',
				//lnk with create and update invoice
				'fee.findstu' 			=> 'Find Student Fee',
			],
			'Expenses' => [
				'expense.index' => 'Expenses View',
				'expense.add' => 'Expenses Create',
				'expense.edit' => 'Expenses Edit',
				'expense.edit.post' => 'Expenses Update',
				'expense.summary' => 'Expenses Summary',
			],
			'SMS Notifications' => [
				'smsnotifications.index' => 'SMS View',
				'smsnotifications.sendsms' => 'Send SMS',
				'smsnotifications.sendbulksms' => 'Send Bulk SMS',
				'smsnotifications.history' => 'SMS History',
			],
			'Notifications' => [
				'notifications.index' => 'View',
				'notifications.get.data' => 'Get Data',
				'notifications.send' => 'Messsage Send',
				'notifications.log' => 'View Logs',
				'notifications.msg.log' => 'View Message Logs',
			],
			'Reports' => [
				'seatsreport' => 'Seats Report',
				'fee-collection-reports.index' => 'Fee Collection View',
				'fee-collection-reports.fee.receipts.statment' => 'Fee Receipts Statement',
				'fee-collection-reports.daily.fee.collection' => 'Daily Fee Collection',
				'fee-collection-reports.free.ship.students' => 'Freeship Students',
				'fee-collection-reports.unpaid.fee.statment' => 'Unpaid Fee Statement',
				'fee-collection-reports.yearly.collection.statment' => 'Yearly Collection Statement',
				
				'exam-reports.index' => 'Exam Reports View',
				'exam-reports.tabulation.sheet' => 'Tabulation Sheet',
				'exam-reports.award.list' => 'Award List',
				'exam-reports.average.result' => 'Average Result',
				'exam-reports.find.student' => 'Find Student',
				'exam-reports.result.transcript' => 'Result Transcript',
			],
			// 'Academic Sessions' => [
			// 	'academic-sessions.index' => 'Sessions View',
			// 	'academic-sessions.create' => 'Sessions create',
			// ],
			'System Settings' => [
				'system-setting.index' => 'System Settings View',
				'system-setting.update' => 'System Settings Update',
				'system-setting.print.invoice.history' => 'Print Invoice History',
				'system-setting.history' => 'System History',
				'system-setting.notification.settings' => 'Notification Settings',
				'fee-scenario.index' => 'Fee Scenario View',
				'fee-scenario.update.scenario' => 'Fee Scenario Update',
				'exam-grades.index' => 'Exam Grades View',
				'exam-grades.update' => 'Exam Grades View',
			],
		];
	}
}
