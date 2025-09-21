<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
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

class StudentsController extends Controller 
{

	//  protected $Routes;
	// protected $data, $Student, $Request, $Input;

	// 	public function __Construct($Routes, Request $Request){
	// 		$this->data['root'] = $Routes;
	// 		$this->Request = $Request;
	// 		$this->Input  =    $Request->input();
	// 		// for session update temperory
	// //		$this->UpdateStd();
	// 	}

	public function GetImage($id)
	{
		$student = Student::findOrFail($id);

		// Check if file exists in the current tenant's storage
		if (!Storage::exists($student->image_dir)) {
			abort(404, 'Image not found.');
		}

		// Get the image content using the default storage (which handles tenancy)
		$image = Storage::get($student->image_dir);

		// Get MIME type
		$mime = Storage::mimeType($student->image_dir);

		return response($image, 200)->header('Content-Type', $mime ?? 'image/jpeg');
	}

	public function GetProfile($id) {
		$student  = Student::with('Certificates')
									->with('StdClass')
									->with('Section')
									->with(['Guardian'	=>	function($qry) use ($id){
										$qry->with(['Student'	=>	function($qry) use ($id){
											$qry->select('id', 'guardian_id', 'name', 'gr_no');
											$qry->where('id', '!=', $id);
										}]);
									}])
									->findorfail($id);
		$student->date_of_birth_inwords = $student->date_of_birth;
		return view('admin.student_profile', compact('student'));
	}

	protected function PostValidate($request,$id = null){
		$this->validate($request, [
				'name'    =>  'required',
				'father_name'    =>  'required',
				'gender'  =>  'required',
				'class'   =>  'sometimes|required',
				'section'  =>  'sometimes|required',
				'gr_no'  =>  'required|unique:students,gr_no,'.$id,
				'guardian'    =>  'required',
				'guardian_relation'  =>  'required',
				'tuition_fee'  =>  'sometimes|required|numeric',
				'dob'       =>  'required',
				'doa'       =>  'required',
				'doe'       =>  'required',
				'img'       => 	'image|mimes:jpg,jpeg,png|max:1524',
		]);
	}

	public function Index(Request $request){
		$data['classes'] = Classe::select('id', 'name')->get();
		if ($request->ajax()) {
			return DataTables::of(DB::table('students')
				->join('academic_session_history', 'students.id', '=', 'academic_session_history.student_id')
				->select('students.*', 'academic_session_history.class_id AS class')
				->where([
					'academic_session_history.academic_session_id' => Auth::user()->academic_session
				])
				)
				->editColumn('class_name', function($students) use ($data){
				$html = ($data['classes']->where('id', $students->class)->first())->name;
				return $html;
				})
				->make(true);
		}
		$data['guardians'] = Guardian::select('id', 'name', 'email', 'phone', 'address')->get();
		$data['no_of_active_students'] = Student::active()->count();

		foreach ($data['classes'] as $key => $class) {
			$data['sections']['class_'.$class->id] = Section::select('name', 'id')->where(['class_id' => $class->id])->get();
		}
		return view('admin.students', $data);
		}

	public function getGuardians(Request $request)
	{
		$guardians = Guardian::select('id', 'name', 'email', 'phone', 'address')->get();
    	return response()->json($guardians);	
	}	

	public function Grid(Request $request)
	{	
		$Students = Student::with([
			'StdClass:id,name',
			'Guardian:id,name',
			'lastInvoice:id,student_id,paid_amount,date'
		]); 

		if ($request->filled('search_students')) {
			$search = $request->input('search_students');

			$Students->where(fn($query) => 
			$query->where('name', 'like', "%{$search}%")
				->orWhere('gr_no', 'like', "%{$search}%")
				->orWhere('gender', 'like', "%{$search}%")
				->orWhereHas('StdClass', fn($q) => 
					$q->where('name', 'like', "%{$search}%")
				)
			);
		}

		$Students = $request->filled('per_page') ? $Students->paginate($request->input('per_page')) : $Students->get();

		$Students->each(function ($student) {
			if ($student->lastInvoice && $student->lastInvoice->paid_amount > 0) {
				$student->invoice_status = 'paid';
			}
			elseif ($student->lastInvoice && $student->lastInvoice->paid_amount == 0) {
				$student->invoice_status = 'unpaid';
			}
			else {
				$student->invoice_status = 'not_created';
			}
		});
		
		return response()->json($Students);
	}

	public function AddStudent(Request $request){

		$this->PostValidate($request);

		if(Student::active()->count() >= tenancy()->tenant->system_info['general']['student_capacity']){
			return redirect('students')->with([
									'toastrmsg' => [
										'type'	=> 'error', 
										'title'	=>  'Students',
										'msg'	=>  'Over students limit'
									]
								]);
		}

		$Student = new Student;
		$this->SetAttributes($Student, $request);
		$Student->created_by  = Auth::user()->id;
		$Student->session_id  = Auth::user()->academic_session;
		$this->UpdateGrNo($Student, $request);
		$Student->save();
		if($request->hasFile('img')){
			$this->SaveImage($Student, $request);
			$Student->save();
		}

		$this->UpdateAcademicSessionHistory($Student);

		$this->UpdateAdditionalFee($Student, $request);

		return redirect('students')->with([
				'toastrmsg' => [
					'type' => 'success', 
					'title'  =>  'Student Registration',
					'msg' =>  'Registration Successfull'
					]
			]);

	}

	public function EditStudent($id){

		$data['guardians'] = Guardian::select('id', 'name', 'email')->get();
		$data['classes'] = Classe::select('id', 'name')->get();
		foreach ($data['classes'] as $key => $class) {
			$data['sections']['class_'.$class->id] = Section::select('name', 'id')->where(['class_id' => $class->id])->get();
		}

		$data['student'] = Student::findorFail($id);
		$data['additional_fee'] = $data['student']->AdditionalFee;

		return view('admin.edit_student', $data);
	}

	public function PostEditStudent(Request $request, $id){

		$this->PostValidate($request, $id);
		$Student = Student::findorFail($id);
		$this->SetAttributes($Student, $request, false);

		$this->UpdateGrNo($Student, $request);

		if($request->hasFile('img')){
			$this->SaveImage($Student, $request);
		} else if($request->input('removeImage')){
			$this->DeleteImage($Student);
		}

		$Student->updated_by  = Auth::user()->id;
		$Student->save();

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

	public function PostLeaveStudent(Request $request, $id){

		if($request->ajax()){
			$validator = Validator::make($request->all(), [
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

			$student =	Student::findorfail($id);
			$student->date_of_leaving = $request->input('date_of_leaving');
			$student->cause_of_leaving = $request->input('cause_of_leaving');
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

	public function GetCertificate($action, Request $request){
		switch ($action) {
			case 'new':
				$data['student'] = $this->CompileStudentForCertificate($request->query('student_id'));
				break;

			case 'update':
				$data['certificate']	= Certificate::findorfail($request->input('certificate_id'));
				$data['student'] = 	$this->CompileStudentForCertificate($data['certificate']->student_id);
				break;
			
			default:
				abort(404);
				break;
		}
		$data['action'] = $action;
		return view('admin.student_certificate', $data);
	}

	private function CompileStudentForCertificate($student_id){
		$data['student']	= Student::findorfail($student_id);
		$data['student']->date_of_birth_inwords = $data['student']->getRawOriginal('date_of_birth');
		$data['student']['class_name']	=	Classe::select('name')->findorfail($data['student']->class_id)->name;

		return $data['student'];
	}


	public function PostCertificate(Request $request){

		$validate = [
			'title'	=>	'required',
			'certificate'	=>	'required',
			'student_id'	=>	'required'
		];
		if($request->has('id')){
			$validate['id'] = 'required';
		}
/*		echo $this->Request->input('certificate');
		$ConfigWriter = new ConfigWriter('certificates');
		$ConfigWriter->set([
				'character_certificate' => $this->Request->input('certificate'),
			]);
		$ConfigWriter->save();
		dd('');*/
		$this->validate($request, $validate);
		$data['student']	= Student::findorfail($request->input('student_id'));

		if($request->has('id')){
			Certificate::updateOrCreate(
				['id'	=>	$request->input('id')],
				[
					'updated_by'	=>	Auth::user()->id,
					'student_id'	=>	$request->input('student_id'),
					'title'	=>	$request->input('title'),
					'certificate'	=>	$request->input('certificate')
				]
			);
		} else {
			Certificate::create([
				'created_by'	=> Auth::user()->id,
				'student_id'	=>	$request->input('student_id'),
				'title'	=>	$request->input('title'),
				'certificate'	=>	$request->input('certificate')
			]);
		}

		return redirect('students/profile/'.$request->input('student_id'))->with([
									'toastrmsg' => [
										'type'	=> 'success', 
										'title'	=>  'Students',
										'msg'	=>  'Certificate is Updated!'
									]
								]);

	}

	public function GetInterview(Request $request, $id){

		$data['student'] = Student::with('ParentInterview')->with(['StdClass' => function($qry){
			$qry->select('id', 'name');
		}])->findorFail($id);
		return view('admin.parent_interview', $data);
	}

	public function UpdateOrCreateInterview(Request $request, $id){

		if($request->ajax()){
			$validator = Validator::make($request->all(), [
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
				['student_id'	=>	$request->input('student_id')],
				[
					'father_qualification'	=>	$request->input('father_qualification'),
					'mother_qualification'	=>	$request->input('mother_qualification'),
					'father_occupation'	=>	$request->input('father_occupation'),
					'mother_occupation'	=>	$request->input('mother_occupation'),
					'monthly_income'	=>	$request->input('monthly_income'),
					'other_job_father'	=>	$request->input('other_job_father'),
					'other_job_mother'	=>	$request->input('other_job_mother'),
					'family_structure'	=>	$request->input('family_structure'),
					'parents_living'	=>	$request->input('parents_living'),
					'no_of_children'	=>	$request->input('no_of_children'),
					'questions'			=>	$request->input('questions'),
					'questions_montessori'	=>	$request->input('questions_montessori'),
					'remarks'				=>	$request->input('remarks'),
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

	protected function SetAttributes($Student, $request, $new = true){
		$Student->name = $request->input('name');
		$Student->father_name = $request->input('father_name');
		$Student->gender = $request->input('gender');

		if($new || Auth::user()->can('students.class_edit')) {
			$Student->class_id = $request->input('class');
			$Student->section_id = $request->input('section');
		}
		if($new){
			$Student->tuition_fee = $request->input('tuition_fee');
			$Student->late_fee = $request->input('late_fee');
			$Student->net_amount = $request->input('net_amount');
			$Student->discount = $request->input('discount');
			$Student->total_amount = $request->input('total_amount');
		}

//		$Student->gr_no = $request->input('gr_no');
		$Student->guardian_id = $request->input('guardian');
		$Student->guardian_relation = $request->input('guardian_relation');
		$Student->email = $request->input('email');
		$Student->phone = $request->input('phone');
		$Student->address = $request->input('address');
		$Student->seeking_class = $request->input('seeking_class');
		$Student->date_of_birth   = Carbon::createFromFormat('d/m/Y', $request->input('dob'))->toDateString();
		$Student->date_of_admission   = Carbon::createFromFormat('d/m/Y', $request->input('doa'))->toDateString();
		$Student->date_of_enrolled   = $request->input('doe');
		$Student->place_of_birth  = $request->input('place_of_birth');
		$Student->religion  = $request->input('religion');
		$Student->last_school = $request->input('last_school');
		$Student->receipt_no = $request->input('receipt_no');
	}

	protected function UpdateAdditionalFee($Student, $request){
		AdditionalFee::where(['student_id' => $Student->id])->delete();
		if ($request->input('fee') && COUNT($request->input('fee')) >= 1) {
			foreach ($request->input('fee') as $key => $value) {
				$AdditionalFee = new AdditionalFee;
				$AdditionalFee->id = $value['id'];
				$AdditionalFee->student_id = $Student->id;
				$AdditionalFee->fee_name = $value['fee_name'];
				$AdditionalFee->amount = $value['amount'];
				$AdditionalFee->onetime = isset($value['onetime'])? 1 : 0;
				$AdditionalFee->active = isset($value['active'])? 1 : 0;
				$AdditionalFee->save();
			}
		}
	}

	protected function UpdateGrNo($Student, $request){
		$class = Classe::find($Student->class_id);
		$section = Section::find($Student->section_id);
//    $Student->gr_no = $class->numeric_name . $section->nick_name ."-" . $Student->id;
		$Student->gr_no = $class->prifix . $section->nick_name ."-" . $request->input('gr_no');
	}

	protected function UpdateAcademicSessionHistory($Student){
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

	protected function SaveImage($Student, $request)
	{
		$file = $request->file('img');

		if ($Student->image_dir && Storage::exists($Student->image_dir)) {
			Storage::delete($Student->image_dir);
		}

		$extension = $file->getClientOriginalExtension();
		$filename = $Student->id;
		
		$path = 'students/' . $filename;
		Storage::put($path . '.' . $extension, File::get($file));

		$Student->image_dir = "{$path}" . '.' . $extension;
		$Student->image_url = 'students/image/' . $filename;
	}

	protected function DeleteImage($Student){
		if($Student->image_dir){
			Storage::delete($Student->image_dir);
			$Student->image_dir = Null;
			$Student->image_url = Null;
			$Student->save();
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