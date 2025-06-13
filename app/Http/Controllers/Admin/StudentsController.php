<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
//use Illuminate\Http\Request;
use Request;
use App\Student;
use App\Guardian;
use App\Classe;
use App\Section;
use App\AdditionalFee;
use DB;
use Carbon\Carbon;
use Auth;
use App\AcademicSessionHistory;
use App\Http\Controllers\Controller;
use Validator;
use App\Certificate;
use App\ParentInterview;
use Larapack\ConfigWriter\Repository as ConfigWriter;

class StudentsController extends Controller 
{

//  protected $Routes;
	protected $data, $Student, $Request, $Input;

	public function __Construct($Routes, $Request){
		$this->data['root'] = $Routes;
		$this->Request = $Request;
		$this->Input  =    $Request->input();
		//for session update temperory
//		$this->UpdateStd();
	}

	public function GetImage(){
		$student  = Student::findorfail($this->data['root']['option']);
		$image = Storage::get($student->image_dir);
//    $image = Storage::disk('public/studnets')->get('1.jpg');
//    return Response($image, 200);
		return Response($image, 200)->header('Content-Type', 'image');
	}

	public function GetProfile() {
		$this->data['student']  = Student::with('Certificates')
									->with('StdClass')
									->with('Section')
									->with(['Guardian'	=>	function($qry){
										$qry->with(['Student'	=>	function($qry){
											$qry->select('id', 'guardian_id', 'name', 'gr_no');
											$qry->where('id', '!=', $this->data['root']['option']);
										}]);
									}])
									->findorfail($this->data['root']['option']);
		$this->data['student']->date_of_birth_inwords = $this->data['student']->getOriginal('date_of_birth');
		return view('admin.student_profile', $this->data);
	}

	protected function PostValidate(){
		$this->validate($this->Request, [
				'name'    =>  'required',
				'father_name'    =>  'required',
				'gender'  =>  'required',
				'class'   =>  'sometimes|required',
				'section'  =>  'sometimes|required',
				'gr_no'  =>  ($this->data['root']['job'] == 'edit')? 'required|unique:students,gr_no,'.$this->data['root']['option'] : 'required|unique:students',
				'guardian'    =>  'required',
				'guardian_relation'  =>  'required',
// 		    	'email'   =>  ($this->data['root']['job'] == 'edit')? 'email|unique:students,email,'.$this->data['root']['option'] : 'email|unique:students',
				'tuition_fee'  =>  'sometimes|required|numeric',
				'dob'       =>  'required',
				'doa'       =>  'required',
				'doe'       =>  'required',
				'img'       =>  'image|mimes:jpeg,png,jpg|max:4096',
		]);
	}

	public function Index(){
		//$this->data['teachers'] = Teacher::select('name', 'email', 'address', 'id', 'phone')->get();
		$this->data['classes'] = Classe::select('id', 'name')->get();
		if (Request::ajax()) {
	/*
			return DataTables::eloquent(Student::query())->make(true);
	*/
			return DataTables::of(DB::table('students')
				->join('academic_session_history', 'students.id', '=', 'academic_session_history.student_id')
				->select('students.*', 'academic_session_history.class_id AS class')
				->where([
					'academic_session_history.academic_session_id' => Auth::user()->academic_session
				])
				)
				->editColumn('class_name', function($students){
				$html = ($this->data['classes']->where('id', $students->class)->first())->name;
				return $html;
				})
				->make(true);
//      return DataTables::eloquent(Student::query()->CurrentSession())->make(true);
		}
		$this->data['guardians'] = Guardian::select('id', 'name', 'email')->get();
		$this->data['no_of_active_students'] = Student::active()->count();

		foreach ($this->data['classes'] as $key => $class) {
			$this->data['sections']['class_'.$class->id] = Section::select('name', 'id')->where(['class_id' => $class->id])->get();
		}
		return view('admin.students', $this->data);
		}

	public function AddStudent(){

		$this->PostValidate();

		if(Student::active()->count() >= config('systemInfo.student_capacity')){
			return redirect('students')->with([
									'toastrmsg' => [
										'type'	=> 'error', 
										'title'	=>  'Students',
										'msg'	=>  'Over students limit'
									]
								]);
		}

		$this->Student = new Student;
		$this->SetAttributes();
		$this->Student->created_by  = Auth::user()->id;
		$this->Student->session_id  = Auth::user()->academic_session;
		$this->UpdateGrNo();
		$this->Student->save();
		if($this->Request->hasFile('img')){
			$this->SaveImage();
			$this->Student->save();
		}

		$this->UpdateAcademicSessionHistory();

		$this->UpdateAdditionalFee();

		return redirect('students')->with([
				'toastrmsg' => [
					'type' => 'success', 
					'title'  =>  'Student Registration',
					'msg' =>  'Registration Successfull'
					]
			]);

	}

	public function EditStudent(){

		$this->data['guardians'] = Guardian::select('id', 'name', 'email')->get();
		$this->data['classes'] = Classe::select('id', 'name')->get();
		foreach ($this->data['classes'] as $key => $class) {
			$this->data['sections']['class_'.$class->id] = Section::select('name', 'id')->where(['class_id' => $class->id])->get();
		}

		$this->data['student'] = Student::findorFail($this->data['root']['option']);
		$this->data['additional_fee'] = $this->data['student']->AdditionalFee;

		return view('admin.edit_student', $this->data);
	}

	public function PostEditStudent(){

		$this->PostValidate();
		$this->Student = Student::findorFail($this->data['root']['option']);
		$this->SetAttributes(false);

		$this->UpdateGrNo();

		if($this->Request->hasFile('img')){
			$this->SaveImage();
		} else if($this->Request->input('removeImage')){
			$this->DeleteImage();
		}

		$this->Student->updated_by  = Auth::user()->id;
		$this->Student->save();

//		$this->UpdateAcademicSessionHistory();
//		$this->UpdateAdditionalFee();

		return redirect('students')->with([
				'toastrmsg' => [
					'type' => 'success', 
					'title'  =>  'Students Registration',
					'msg' =>  'Save Changes Successfull'
					]
			]);
	}

	public function PostLeaveStudent(){

		if($this->Request->ajax()){
			$validator = Validator::make($this->Request->all(), [
				'id'				=>	'required|numeric',
				'date_of_leaving'	=>	'required|date'
				]);

			if ($validator->fails()) {
				return  [
					'updated'	=>	false,
					'toastrmsg'	=>	[
						'type'	=> 'error', 
						'title'	=>  'Students',
						'msg'	=>  'Something is wrong!'
					]
				];
			}

			$student =	Student::findorfail($this->Request->input('id'));
			$student->date_of_leaving = $this->Request->input('date_of_leaving');
			$student->cause_of_leaving = $this->Request->input('cause_of_leaving');
			$student->active = 0;
			$student->save();

				return  [
					'updated'	=>	true,
					'toastrmsg'	=>	[
						'type'	=> 'success', 
						'title'	=>  'Students',
						'msg'	=>  'Update Successfull'
					]
				];
		}

		return redirect('students')->with([
									'toastrmsg' => [
										'type'	=> 'warning', 
										'title'	=>  'Students',
										'msg'	=>  'Something is wrong!'
									]
								]);


	}

	public function Certificates(){

		$this->validate($this->Request, [
			'id' => 'required|numeric'
		]);

		switch ($this->data['root']['option']) {
			case 'transfercertificate':
				$this->data['student']	=	Student::with('StdClass')->findorfail($this->Request->input('id'));
				return view('admin.printable.student_transfer_certificate', $this->data);
				break;
			
			default:
				return abort(404);
				break;
		}

	}

	public function GetCertificate(){

		switch ($this->data['root']['option']) {
			case 'new':
				$this->CompileStudentForCertificate($this->Request->input('student_id'));
				break;

			case 'update':
				$this->data['certificate']	= Certificate::findorfail($this->Request->input('certificate_id'));
				$this->CompileStudentForCertificate($this->data['certificate']->student_id);
				break;
			
			default:
				abort(404);
				break;
		}

		return view('admin.student_certificate', $this->data);

	}

	private function CompileStudentForCertificate($student_id){
		$this->data['student']	= Student::findorfail($student_id);
		$this->data['student']->date_of_birth_inwords = $this->data['student']->getOriginal('date_of_birth');
		$this->data['student']['class_name']	=	Classe::select('name')->findorfail($this->data['student']->class_id)->name;
	}


	public function PostCertificate(){

		$validate = [
			'title'	=>	'required',
			'certificate'	=>	'required',
			'student_id'	=>	'required'
		];
		if($this->Request->has('id')){
			$validate['id'] = 'required';
		}
/*		echo $this->Request->input('certificate');
		$ConfigWriter = new ConfigWriter('certificates');
		$ConfigWriter->set([
				'character_certificate' => $this->Request->input('certificate'),
			]);
		$ConfigWriter->save();
		dd('');*/
		$this->validate($this->Request, $validate);
		$this->data['student']	= Student::findorfail($this->Request->input('student_id'));

		if($this->Request->has('id')){
			Certificate::updateOrCreate(
				['id'	=>	$this->Request->input('id')],
				[
					'updated_by'	=>	Auth::user()->id,
					'student_id'	=>	$this->Request->input('student_id'),
					'title'	=>	$this->Request->input('title'),
					'certificate'	=>	$this->Request->input('certificate')
				]
			);
		} else {
			Certificate::create([
				'created_by'	=> Auth::user()->id,
				'student_id'	=>	$this->Request->input('student_id'),
				'title'	=>	$this->Request->input('title'),
				'certificate'	=>	$this->Request->input('certificate')
			]);
		}

		return redirect('students/profile/'.$this->Request->input('student_id'))->with([
									'toastrmsg' => [
										'type'	=> 'success', 
										'title'	=>  'Students',
										'msg'	=>  'Certificate is Updated!'
									]
								]);

	}

	public function GetInterview(){
		$this->data['student'] = Student::with('ParentInterview')->with(['StdClass' => function($qry){
			$qry->select('id', 'name');
		}])->findorFail($this->data['root']['option']);
		return view('admin.parent_interview', $this->data);
	}

	public function UpdateInterview(){

		if($this->Request->ajax()){
			$validator = Validator::make($this->Request->all(), [
				'student_id' => 'required'
			]);

			if ($validator->fails()) {
				return  [
					'type'	=> 'error', 
					'title'	=>  'Parent Interview',
					'msg'	=>  'Something is wrong!'
				];
			}

			ParentInterview::updateOrCreate(
				['student_id'	=>	$this->Request->input('student_id')],
				[
					'father_qualification'	=>	$this->Request->input('father_qualification'),
					'mother_qualification'	=>	$this->Request->input('mother_qualification'),
					'father_occupation'	=>	$this->Request->input('father_occupation'),
					'mother_occupation'	=>	$this->Request->input('mother_occupation'),
					'monthly_income'	=>	$this->Request->input('monthly_income'),
					'other_job_father'	=>	$this->Request->input('other_job_father'),
					'other_job_mother'	=>	$this->Request->input('other_job_mother'),
					'family_structure'	=>	$this->Request->input('family_structure'),
					'parents_living'	=>	$this->Request->input('parents_living'),
					'no_of_children'	=>	$this->Request->input('no_of_children'),
					'questions'			=>	$this->Request->input('questions'),
					'questions_montessori'	=>	$this->Request->input('questions_montessori'),
					'remarks'				=>	$this->Request->input('remarks'),
				]
			);
			
			return	[
				'type'	=> 'success', 
				'title'	=>  'Parent Interview',
				'msg'	=>  'Update Interview Successfull'
			];
		}
	
		return redirect('Students')->with([
									'toastrmsg' => [
										'type'	=> 'warning', 
										'title'	=>  'Students',
										'msg'	=>  'Something is wrong!'
									]
								]);
//		dd($this->Request);
	}

	protected function SetAttributes($new = true){
		$this->Student->name = $this->Request->input('name');
		$this->Student->father_name = $this->Request->input('father_name');
		$this->Student->gender = $this->Request->input('gender');

		if($new || Auth::user()->getprivileges->privileges->{$this->data['root']['content']['id']}->editclass) {
			$this->Student->class_id = $this->Request->input('class');
			$this->Student->section_id = $this->Request->input('section');
		}
		if($new){
			$this->Student->tuition_fee = $this->Request->input('tuition_fee');
			$this->Student->late_fee = $this->Request->input('late_fee');
			$this->Student->net_amount = $this->Request->input('net_amount');
			$this->Student->discount = $this->Request->input('discount');
			$this->Student->total_amount = $this->Request->input('total_amount');
		}

//		$this->Student->gr_no = $this->Request->input('gr_no');
		$this->Student->guardian_id = $this->Request->input('guardian');
		$this->Student->guardian_relation = $this->Request->input('guardian_relation');
		$this->Student->email = $this->Request->input('email');
		$this->Student->phone = $this->Request->input('phone');
		$this->Student->address = $this->Request->input('address');
		$this->Student->seeking_class = $this->Request->input('seeking_class');
		$this->Student->date_of_birth   = Carbon::createFromFormat('d/m/Y', $this->Request->input('dob'))->toDateString();
		$this->Student->date_of_admission   = Carbon::createFromFormat('d/m/Y', $this->Request->input('doa'))->toDateString();
		$this->Student->date_of_enrolled   = $this->Request->input('doe');
		$this->Student->place_of_birth  = $this->Request->input('place_of_birth');
		$this->Student->religion  = $this->Request->input('religion');
		$this->Student->last_school = $this->Request->input('last_school');
		$this->Student->receipt_no = $this->Request->input('receipt_no');
	}

	protected function UpdateAdditionalFee(){
		AdditionalFee::where(['student_id' => $this->Student->id])->delete();
		if (COUNT($this->Request->input('fee')) >= 1) {
			foreach ($this->Input['fee'] as $key => $value) {
				$AdditionalFee = new AdditionalFee;
				$AdditionalFee->id = $value['id'];
				$AdditionalFee->student_id = $this->Student->id;
				$AdditionalFee->fee_name = $value['fee_name'];
				$AdditionalFee->amount = $value['amount'];
				$AdditionalFee->onetime = isset($value['onetime'])? 1 : 0;
				$AdditionalFee->active = isset($value['active'])? 1 : 0;
				$AdditionalFee->save();
			}
		}
	}

	protected function UpdateGrNo(){
		$class = Classe::find($this->Student->class_id);
		$section = Section::find($this->Student->section_id);
//    $this->Student->gr_no = $class->numeric_name . $section->nick_name ."-" . $this->Student->id;
		$this->Student->gr_no = $class->prifix . $section->nick_name ."-" . $this->Request->input('gr_no');
	}

	protected function UpdateAcademicSessionHistory(){
		AcademicSessionHistory::updateOrCreate(
			[
				'student_id' => $this->Student->id,
				'academic_session_id' => $this->Student->session_id
			],
			[
				'class_id' => $this->Student->class_id
			]
		);
	}

	protected function SaveImage(){
		$file = $this->Request->file('img');
		Storage::delete($this->Student->image_dir);
		$extension = $file->getClientOriginalExtension();
		Storage::disk('public')->put('students/'.$this->Student->id.'.'.$extension,  File::get($file));
//    $file = $this->Request->file('img')->storePubliclyAs('images/students', $this->Student->id.'.'.$file->getClientOriginalExtension(), 'public');
		$this->Student->image_dir = 'public/students/'.$this->Student->id.'.'.$extension;
		$this->Student->image_url = 'students/image/'.$this->Student->id;
	}

	protected function DeleteImage(){
		if($this->Student->image_dir){
			Storage::delete($this->Student->image_dir);
			$this->Student->image_dir = Null;
			$this->Student->image_url = Null;
		}
	}

// for session update temperory 
	protected function UpdateStd(){
		$Students 	=	Student::all();
		foreach ($Students as $key => $Student) {
			AcademicSessionHistory::updateOrCreate(
				[
					'student_id' => $Student->id,
					'academic_session_id' => $Student->session_id
				],
				[
					'class_id' => $Student->class_id
				]
			);
		}
	}

}