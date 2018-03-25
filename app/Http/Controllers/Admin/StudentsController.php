<?php

namespace App\Http\Controllers\Admin;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
//use Illuminate\Http\Request;
use Request;
use App\Http\Requests;
use App\Student;
use App\Guardian;
use App\Classe;
use App\Section;
use App\AdditionalFee;
use DB;
use Carbon\Carbon;
use Auth;
use App\Http\Controllers\Controller;

class StudentsController extends Controller 
{

//  protected $Routes;
	protected $data, $Student, $Request, $Input;

	public function __Construct($Routes, $Request){
		$this->data['root'] = $Routes;
		$this->Request = $Request;
		$this->Input  =    $Request->input();
	}

	public function GetImage(){
		$student  = Student::findorfail($this->data['root']['option']);
		$image = Storage::get($student->image_dir);
//    $image = Storage::disk('public/studnets')->get('1.jpg');
//    return Response($image, 200);
		return Response($image, 200)->header('Content-Type', 'image');
	}

	public function GetProfile() {
		$this->data['student']  = Student::findorfail($this->data['root']['option']);
		return view('admin.student_profile', $this->data);
	}

	protected function PostValidate(){
		$this->validate($this->Request, [
				'name'    =>  'required',
				'father_name'    =>  'required',
				'gender'  =>  'required',
				'class'   =>  'required',
				'section'  =>  'required',
				'gr_no'  =>  ($this->data['root']['job'] == 'edit')? 'required|unique:students,gr_no,'.$this->data['root']['option'] : 'required|unique:students',
				'guardian'    =>  'required',
				'guardian_relation'  =>  'required',
//        'email'   =>  ($this->data['root']['job'] == 'edit')? 'email|unique:students,email,'.$this->data['root']['option'] : 'email|unique:students',
				'tuition_fee'  =>  'required|numeric',
				'dob'       =>  'required',
				'img'       =>  'image|mimes:jpeg,png,jpg|max:4096',
		]);
	}

	public function Index(){
		//$this->data['teachers'] = Teacher::select('name', 'email', 'address', 'id', 'phone')->get();
		if (Request::ajax()) {
	/*
			return Datatables::queryBuilder(DB::table('students')
				->join('classes', 'students.class_id', '=', 'classes.id')
				->join('sections', 'students.section_id', '=', 'sections.id')
				->select('students.name', 'students.address', 'students.id', 'students.phone', 'students.gr_no', 'classes.name AS class_name', 'sections.name AS section_name', 'sections.nick_name AS section_nick')
				)->make(true);
	*/
//      return Datatables::eloquent(Student::query()->CurrentSession())->make(true);
			return Datatables::eloquent(Student::query())->make(true);
		}
		$this->data['guardians'] = Guardian::select('id', 'name', 'email')->get();
		$this->data['classes'] = Classe::select('id', 'name')->get();
		foreach ($this->data['classes'] as $key => $class) {		
			$this->data['sections']['class_'.$class->id] = Section::select('name', 'id')->where(['class_id' => $class->id])->get();
		}
		return view('admin.students', $this->data);
		}

	public function AddStudent(){

		$this->PostValidate();
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
		$this->SetAttributes();

		$this->UpdateGrNo();

		if($this->Request->hasFile('img')){
			$this->SaveImage();
		}

		$this->Student->updated_by  = Auth::user()->id;
		$this->Student->save();

		$this->UpdateAdditionalFee();

		return redirect('students')->with([
				'toastrmsg' => [
					'type' => 'success', 
					'title'  =>  'Students Registration',
					'msg' =>  'Save Changes Successfull'
					]
			]);
	}

	protected function SetAttributes(){
		$this->Student->name = $this->Request->input('name');
		$this->Student->father_name = $this->Request->input('father_name');
		$this->Student->gender = $this->Request->input('gender');
		$this->Student->class_id = $this->Request->input('class');
		$this->Student->section_id = $this->Request->input('section');
//		$this->Student->gr_no = $this->Request->input('gr_no');
		$this->Student->guardian_id = $this->Request->input('guardian');
		$this->Student->guardian_relation = $this->Request->input('guardian_relation');
		$this->Student->email = $this->Request->input('email');
		$this->Student->phone = $this->Request->input('phone');
		$this->Student->address = $this->Request->input('address');
		$this->Student->tuition_fee = $this->Request->input('tuition_fee');
		$this->Student->total_amount = $this->Request->input('total_amount');
		$this->Student->discount = $this->Request->input('discount');
		$this->Student->net_amount = $this->Request->input('net_amount');
		$this->Student->date_of_birth   = Carbon::createFromFormat('d/m/Y', $this->Request->input('dob'))->toDateString();
		$this->Student->date_of_admission   = Carbon::createFromFormat('d/m/Y', $this->Request->input('doa'))->toDateString();
		$this->Student->place_of_birth  = $this->Request->input('place_of_birth');
		$this->Student->relegion  = $this->Request->input('relegion');
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

	protected function SaveImage(){
		$file = $this->Request->file('img');
		Storage::delete($this->Student->image_dir);
		$extension = $file->getClientOriginalExtension();
		Storage::disk('public')->put('students/'.$this->Student->id.'.'.$extension,  File::get($file));
//    $file = $this->Request->file('img')->storePubliclyAs('images/students', $this->Student->id.'.'.$file->getClientOriginalExtension(), 'public');
		$this->Student->image_dir = 'public/students/'.$this->Student->id.'.'.$extension;
		$this->Student->image_url = 'students/image/'.$this->Student->id;
	}

}