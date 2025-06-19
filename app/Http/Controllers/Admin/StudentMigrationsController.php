<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AcademicSession;
use App\Classe;
use App\Student;
use Auth;
use App\AcademicSessionHistory;

class StudentMigrationsController extends Controller
{
    public function Index(){

        $data['academic_session'] =    AcademicSession::UserAllowSession()->get();
        $data['classes'] =    Classe::all();
		return view('admin.student_migrations', $data);
    }

    public function GetStudents(Request $request){
        $this->validate($request, [
            'from_session'  =>  'required|integer',
            'to_session'  =>  'required|integer',
            'from_class'  =>  'required|integer',
            'to_class'  =>  'required|integer|different:from_class'
        ]);
/*
		$data['students'] = Student::join('academic_session_history', 'students.id', '=', 'academic_session_history.student_id')
									->select('students.id', 'students.name', 'students.gr_no', 'academic_session_history.class_id AS session_history_class_id', 'students.class_id AS current_class_id')
									->where([
										'academic_session_history.class_id' => $request->input('from_class'),
										'academic_session_history.academic_session_id' => $request->input('from_session')
                                        ])
                                    ->Active()
                                    ->get()->toJson();
*/
		$data['students'] = Student::select('students.id', 'students.name', 'students.gr_no')
									->where([
										'class_id' => $request->input('from_class'),
										'session_id' => $request->input('from_session')
                                        ])
                                    ->Active()
                                    ->get();
        if($data['students']->count() == 0){
            return redirect('student-migrations')->with([
                                        'toastrmsg' => [
                                            'type'	=> 'error', 
                                            'title'	=>  'Students Migrations',
                                            'msg'	=>  'Students not found!'
                                        ]
                                    ]);
        }
        return $this->Index();
    }

    public function PostMigration(Request $request){
        $this->validate($request, [
            'from_session'  =>  'required|integer',
            'to_session'  =>  'required|integer',
            'from_class'  =>  'required|integer',
            'to_class'  =>  'required',
        ]);
        $classes = Classe::whereIn('id', $request->input('to_class'))->with('Section')->get();
        $students = Student::whereIn('id', array_keys($request->input('to_class')))->get();

        foreach ($request->input('to_class') as $id => $class_id) {
            $class = $classes->where('id', $class_id)->first();
            $student = $students->where('id', $id)->first();
            if($student){
                $student->gr_no = $class->prifix . $class->section[0]->nick_name ."-" . (explode('-', $student->gr_no))[1];
                $student->class_id      =   $class->id;
                $student->section_id    =   $class->section[0]->id;
                $student->session_id    =    $request->input('to_session');
                $student->save();
            }
            AcademicSessionHistory::firstOrCreate(
                [
                    'student_id' => $id,
                    'academic_session_id' => $request->input('to_session'),
                ],
                [
                    'class_id'	=>	$class_id,
                    'created_by'	=>	Auth::user()->id,
                ]);
        }

        return redirect('student-migrations')->with([
				'toastrmsg' => [
					'type' => 'success', 
					'title'  =>  'Student Migrations',
					'msg' =>  'Migrations Successfull'
					]
			]);

    }


}
