<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Grade;

class ExamGradesController extends Controller
{
	public function Index(){
		$data['grades']	=	Grade::all();
		return view('admin.exam_grades', $data);
	}


	public function UpdateGrade(Request $request){
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
