<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Grade;

class ExamGradesController extends Controller
{

	protected $data, $request, $feeses;

	public function __Construct($Routes){
		$this->data['root'] = $Routes;
	}

	public function Index(){
		$this->data['grades']	=	Grade::all();
		return view('admin.exam_grades', $this->data);
	}


	public function UpdateGrade(Request $request){

		$this->request = $request;

		$this->validate($request, [
			'grades'  =>  'required',
		]);

		grade::truncate();

		Grade::insert($request->input('grades'));

		return redirect('exam-grades')->with([
			'toastrmsg' => [
				'type' => 'success', 
				'title'  =>  'System Settings',
				'msg' =>  'Exam Grades Updated'
			]
		]);
	}


}
