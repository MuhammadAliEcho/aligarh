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

	protected $data, $Student, $Request, $Input;

	public function __Construct($Routes, $Request){
		$this->data['root'] = $Routes;
		$this->Request = $Request;
    }
    
    public function Index(){

        $this->data['academic_session'] =    AcademicSession::UserAllowSession()->get();
        $this->data['classes'] =    Classe::all();
		return view('admin.student_migrations', $this->data);
    }

    public function GetStudents(){
        $this->validate($this->Request, [
            'from_session'  =>  'required|integer',
            'to_session'  =>  'required|integer',
            'from_class'  =>  'required|integer',
            'to_class'  =>  'required|integer|different:from_class'
        ]);
/*
		$this->data['students'] = Student::join('academic_session_history', 'students.id', '=', 'academic_session_history.student_id')
									->select('students.id', 'students.name', 'students.gr_no', 'academic_session_history.class_id AS session_history_class_id', 'students.class_id AS current_class_id')
									->where([
										'academic_session_history.class_id' => $this->Request->input('from_class'),
										'academic_session_history.academic_session_id' => $this->Request->input('from_session')
                                        ])
                                    ->Active()
                                    ->get()->toJson();
*/
		$this->data['students'] = Student::select('students.id', 'students.name', 'students.gr_no')
									->where([
										'class_id' => $this->Request->input('from_class'),
										'session_id' => $this->Request->input('from_session')
                                        ])
                                    ->Active()
                                    ->get();
        if($this->data['students']->count() == 0){
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

    public function PostMigration(Request $Request){
        $this->validate($this->Request, [
            'from_session'  =>  'required|integer',
            'to_session'  =>  'required|integer',
            'from_class'  =>  'required|integer',
            'to_class'  =>  'required',
        ]);
        $classes = Classe::whereIn('id', $Request->input('to_class'))->with('Section')->get();
        $students = Student::whereIn('id', array_keys($Request->input('to_class')))->get();

        foreach ($Request->input('to_class') as $id => $class_id) {
            $class = $classes->where('id', $class_id)->first();
            $student = $students->where('id', $id)->first();
            if($student){
                $student->gr_no = $class->prifix . $class->section[0]->nick_name ."-" . (explode('-', $student->gr_no))[1];
                $student->class_id      =   $class->id;
                $student->section_id    =   $class->section[0]->id;
                $student->session_id    =    $Request->input('to_session');
                $student->save();
            }
            AcademicSessionHistory::firstOrCreate(
                [
                    'student_id' => $id,
                    'academic_session_id' => $Request->input('to_session'),
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
