<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Student;
use App\Guardian;
use App\Teacher;
use App\Classe;
use App\Employee;
use Validator;

class SmsController extends Controller
{

	public function __Construct($Routes){
		$this->data['root'] = $Routes;
	}

	public function Index(){
		$this->data['Students']	=	Student::CurrentSession()->with('Guardian')->get();
		$this->data['Teachers']	=	Teacher::all();
		$this->data['Employee']	=	Employee::all();
		$this->data['Classe']	=	Classe::all();
	    return view('admin.sms_notifications', $this->data);
	}

	public function SendSms(Request $request){

		if($request->ajax()){

			$validator = Validator::make($request->all(), [
				'send_to' => 'required',
				'message' => 'required',

				'student_id' => 'sometimes|required|numeric',
				'teacher_id' => 'sometimes|required|numeric',
				'employee_id' => 'sometimes|required|numeric',
				'guardian_id' => 'sometimes|required|numeric',

				'student_number' => 'sometimes|required|numeric|digits:10',
				'guardian_number' => 'sometimes|required|numeric|digits:10',
				'employee_number' => 'sometimes|required|numeric|digits:10',
				'teacher_number' => 'sometimes|required|numeric|digits:10',
			]);

			if ($validator->fails()) {
				return  [
					'errors'	=>	true,
					'toastrmsg'	=>	[
						'type'	=> 'error', 
						'title'	=>  'Notifications',
						'msg'	=>  'Something is wrong!'
					]
				];
			}

			return	[
				'errors'	=>	false,
				'toastrmsg'	=>	[
					'type'	=> 'success', 
					'title'	=>  'Notifications',
					'msg'	=>  'Query Submitted'
				]
			];
		}

		return redirect('smsnotifications')->with([
									'toastrmsg' => [
										'type'	=> 'warning', 
										'title'	=>  'Notifications',
										'msg'	=>  'Something is wrong!'
									]
								]);
	}

	public function SendBulkSms(Request $request){

		if($request->ajax()){

			$validator = Validator::make($request->all(), [
				'bulk_to' => 'required',
				'message' => 'required',

				$request->input('bulk_to') => 'required',
			]);

			if ($validator->fails()) {
				return  [
					'errors'	=>	true,
					'toastrmsg'	=>	[
						'type'	=> 'error', 
						'title'	=>  'Notifications',
						'msg'	=>  'Something is wrong!'
					]
				];
			}

			return	[
				'errors'	=>	false,
				'toastrmsg'	=>	[
					'type'	=> 'success', 
					'title'	=>  'Notifications',
					'msg'	=>  'Query Submitted'
				]
			];
		}

		return redirect('smsnotifications')->with([
									'toastrmsg' => [
										'type'	=> 'warning', 
										'title'	=>  'Notifications',
										'msg'	=>  'Something is wrong!'
									]
								]);

	}

}
