<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Facades\Datatables;
//use Illuminate\Http\Request;
use App\Http\Requests;
use Request;
use App\Exam;
use Carbon\Carbon;
use Auth;

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
			'name'  =>  'required',
			'description'  =>  'required',
			'start_date' =>  'required',
			'end_date'  =>  'required',
		]);
	}

	public function Index(){

		if (Request::ajax()) {
			return Datatables::eloquent(Exam::query())->make(true);
		}

		return view('exam', $this->data);
	}

	public function EditExam(){
		$this->data['exam'] = Exam::findOrfail($this->data['root']['option']);
		return view('edit_exam', $this->data);
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
		$this->Exam->name			=	$this->Input['name'];
		$this->Exam->description 	=	$this->Input['description'];
		$this->Exam->start_date = Carbon::createFromFormat('d/m/Y', $this->Input['start_date'])->toDateString();
		$this->Exam->end_date = Carbon::createFromFormat('d/m/Y', $this->Input['end_date'])->toDateString();
	}

}
