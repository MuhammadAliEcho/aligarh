<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Teacher;
use App\Classe;
use App\Section;
use App\Routine;
use App\Subject;
use DB;
use Auth;
use App\Http\Controllers\Controller;

class ManageRoutine extends Controller
{
  protected $data;
  public $days;

  public function __Construct(){
    $this->days	=	[
				'monday'	=> 'Monday',
				'tuesday'	=> 'Tuesday',
				'wednesday'	=> 'Wednesday',
				'thrusday'	=> 'Thrusday',
				'friday'	=> 'Friday',
				'saturday'	=> 'Saturday',
				'sunday'	=> 'Sunday',
				];
  }

  protected function RoutinesSortDays($section){
  	$days = $this->days;

  	foreach($days AS $k=>$v){
		$this->data['routines']['section_'.$section->id][$v] = DB::table('routines')
			->leftjoin('teachers', 'routines.teacher_id', '=', 'teachers.id')
			->leftjoin('subjects', 'routines.subject_id', '=', 'subjects.id')
			->select([
					'routines.id',
					'subjects.name AS subject_name',
					'teachers.name AS teacher_name',
					'routines.from_time',
					'routines.to_time',
					])
			->where([
				//'routiens.class_id' => $class->id,
				'routines.section_id' => $section->id, 'day' => $v])
			->get();
  	}
  }

  public function GetRoutine(){

    $this->data['classes'] = Classe::select('name', 'id')->get();
    $this->data['teachers'] = Teacher::select('name', 'id')->get();

	foreach ($this->data['classes'] as $key => $class) {
		
		$this->data['sections']['class_'.$class->id] = Section::select('name', 'id')
                                                            ->where(['class_id' => $class->id])
                                                            ->orderBy('sections.id', 'ASC')
                                                            ->get();
		$this->data['subjects']['class_'.$class->id] = Subject::select('name', 'id')->where(['class_id' => $class->id])->get();

		foreach($this->data['sections']['class_'.$class->id] as $k => $v){

			$this->RoutinesSortDays($v);

		}

    }
    //return dump($this->data);
    $this->data['days'] = $this->days;
    return view('admin.routines', $this->data);

  }

	public function EditRoutine($id){
		if(Routine::where('id', $id)->count() == 0){
		return  redirect('routines')->with([
		    'toastrmsg' => [
		      'type' => 'warning', 
		      'title'  =>  '# Invalid URL',
		      'msg' =>  'Do Not write hard URL\'s'
		      ]
		  ]);
		}

		$this->data['routine'] = Routine::find($id);
		$this->data['classes'] = Classe::select('name', 'id')->get();
		$this->data['teachers'] = Teacher::select('name', 'id')->get();

		foreach ($this->data['classes'] as $key => $class) {
			
			$this->data['sections']['class_'.$class->id] = Section::select('name', 'id')->where(['class_id' => $class->id])->get();
			$this->data['subjects']['class_'.$class->id] = Subject::select('name', 'id')->where(['class_id' => $class->id])->get();

		}

		$this->data['days'] = $this->days;

		return view('admin.edit_routine', $this->data);
	}

  public function AddRoutine(Request $request){

    $request = $request;
    $this->PostValidate($request);
    $Routines = new Routine;
    $this->SetAttributes($Routines, $request);
    $Routines->created_by = Auth::user()->id;
    $Routines->save();

    return redirect('routines')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Routines Timtable',
          'msg' =>  'Registration Successfull'
          ]
      ]);

  }

  public function DeleteRoutine(Request $request){
//    echo "sdthdryh";
    $Routines = Routine::find($request->input('id'));
//    $Routines->find($request->input('id'));
    $Routines->delete();

    if ($request->ajax()) {
      return  response(['type' => 'success','title'  =>  'Routines Timtable','msg' =>  'Routine Deleted']);
    } else { 
    return redirect('routines')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Routines Timtable',
          'msg' =>  'Routine Deleted'
          ]
      ]);
    }

  }

  public function PostEditRoutine(Request $request, $id){

    $request = $request;
    $this->PostValidate($request);

    if(Routine::where('id', $id)->count() == 0){
    return  redirect('routines')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
    }

    $Routines = Routine::find($id);

    $this->SetAttributes($Routines, $request);
    $Routines->updated_by = Auth::user()->id;
    $Routines->save();

    return redirect('routines')->with([
        'toastrmsg' => [
          'type' => 'success',
          'title'  =>  'Routines Settings',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
  }

  protected function PostValidate($request){
    $this->validate($request, [
        'class'  =>  'required',
        'section'  =>  'required',
        'subject'  =>  'required',
//        'teacher' =>  'required',
        'day' =>  'required',
        'from_time' =>  'required',
        'to_time' =>  'required',
    ]);
  }

  protected function SetAttributes($Routines, $request){
    $Routines->class_id = $request->input('class');
    $Routines->section_id = $request->input('section');
    $Routines->teacher_id = $request->input('teacher');
    $Routines->day = $request->input('day');
    $Routines->subject_id = $request->input('subject');
    $Routines->from_time = $request->input('from_time');
    $Routines->to_time = $request->input('to_time');
  }

}
