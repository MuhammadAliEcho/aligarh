<?php

namespace App\Http\Controllers\Admin;

use Yajra\Datatables\Facades\Datatables;
//use Illuminate\Http\Request;
use App\Http\Requests;
use Request;
use App\Exam;
use Carbon\Carbon;
use Auth;
use App\Http\Controllers\Controller;

class ExamController extends Controller
{

	protected $data, $Exam, $Request, $Input;

	public function __Construct($Routes, $Request){
		$this->data['root'] = $Routes;
		$this->Request = $Request;
		$this->Input = $Request->input();
	}

	protected function PostValidate(){
		$this->validate($this->Request, [
			'exam_category'	=>	'required',
			'name'  =>  'required',
			'description'  =>  'required',
			'start_date' =>  'required',
			'end_date'  =>  'required',
		]);
	}

	public function Index(){

		if (Request::ajax()) {
			return Datatables::eloquent(Exam::query()->CurrentSession()->orderBy('id'))->make(true);
		}

		return view('admin.exam', $this->data);
	}

	public function EditExam(){
		$this->data['exam'] = Exam::findOrfail($this->data['root']['option']);
		return view('admin.edit_exam', $this->data);
	}

	public function PostEditExam(){

		$this->PostValidate();

		$this->Exam = Exam::findOrfail($this->data['root']['option']);
		$this->SetAttributes();
		$this->Exam->updated_by = Auth::user()->id;
		$this->Exam->save();

		return redirect('exam')->with([
			'toastrmsg' => [
			'type' => 'success', 
			'title'  =>  'Exams',
			'msg' =>  'Save Changes Successfull'
			]
		]);
	}

	public function AddExam(){

		$this->PostValidate();
		$this->Exam = new Exam;
		$this->SetAttributes();
		$this->Exam->created_by = Auth::user()->id;
		$this->Exam->save();

		return redirect('exam')->with([
			'toastrmsg' => [
			'type' => 'success', 
			'title'  =>  'Exams',
			'msg' =>  'Exam Created'
			]
		]);

	}

	protected function SetAttributes(){
		$this->Exam->category_id	=	$this->Input['exam_category'];
		$this->Exam->name			=	$this->Input['name'];
		$this->Exam->description 	=	$this->Input['description'];
		$this->Exam->start_date 	=	Carbon::createFromFormat('d/m/Y', $this->Input['start_date'])->toDateString();
		$this->Exam->end_date		=	Carbon::createFromFormat('d/m/Y', $this->Input['end_date'])->toDateString();
	}

}
