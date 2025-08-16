<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Exam;
use Carbon\Carbon;
use Auth;
use App\Http\Controllers\Controller;

class ExamController extends Controller
{
	protected function PostValidate($request){
		$this->validate($request, [
			'exam_category'	=>	'required',
			'name'  =>  'required',
			'description'  =>  'required',
			'start_date' =>  'required',
			'end_date'  =>  'required',
		]);
	}

	public function Index(Request $request){

		if ($request->ajax()) {
			return DataTables::eloquent(Exam::query()->CurrentSession()->orderBy('id'))->make(true);
		}

		return view('admin.exam');
	}

	public function EditExam($id){
		$data['exam'] = Exam::findOrfail($id);
		return view('admin.edit_exam', $data);
	}

	public function PostEditExam(Request $request, $id){

		$this->PostValidate($request);

		$Exam = Exam::findOrfail($id);
		$this->SetAttributes($Exam, $request);
//		dd($request->all());
		$Exam->updated_by = Auth::user()->id;
		$Exam->save();

		return redirect('exam')->with([
			'toastrmsg' => [
			'type'	=> 'success',
			'title'  =>  'Exams',
			'msg' =>  'Save Changes Successfull'
			]
		]);
	}

	public function AddExam(Request $request){

		$this->PostValidate($request);
		if($this->IsExamCreated($request)){
			return redirect('exam')->withInput()->with([
				'toastrmsg' => [
					'type' => 'error',
					'title'  =>  'Exam',
					'msg' =>  'Exam "'.config('examcategories.'.$request['exam_category']).'" already created'
					]
			]);
		}
		$Exam = new Exam;
		$Exam->academic_session_id = Auth::user()->academic_session;
		$this->SetAttributes($Exam, $request);
		$Exam->created_by = Auth::user()->id;
		$Exam->save();

		return redirect('exam')->with([
			'toastrmsg' => [
			'type' => 'success', 
			'title'  =>  'Exams',
			'msg' =>  'Exam Created'
			]
		]);

	}

	protected function SetAttributes($Exam, $request){
		$Exam->category_id	=	$request['exam_category'];
		$Exam->name			=	$request['name'];
		$Exam->active			=	$request['active'];
		$Exam->description 	=	$request['description'];
		$Exam->start_date 	=	Carbon::createFromFormat('d/m/Y', $request['start_date'])->toDateString();
		$Exam->end_date		=	Carbon::createFromFormat('d/m/Y', $request['end_date'])->toDateString();
	}

	protected function IsExamCreated($request){
		return Exam::where([
			'academic_session_id'	=>	Auth::user()->academic_session,
			'category_id'	=>	$request['exam_category'],
		])->first();
	}

}
