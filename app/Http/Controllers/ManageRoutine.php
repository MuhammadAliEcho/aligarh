<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Requests;
use App\Teacher;
use App\Classe;
use App\Section;
use App\Routine;
use App\Subject;
use DB;
use Request;
use Auth;

class ManageRoutine extends Controller
{

  //  protected $Routes;
  protected $data, $Classes, $Sections, $Routines, $Request;
  public $days;

  public function __Construct($Routes){
    $this->data['root'] = $Routes;
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
    return view('routines', $this->data);

  }

	public function EditRoutine(){
		if(Routine::where('id', $this->data['root']['option'])->count() == 0){
		return  redirect('routines')->with([
		    'toastrmsg' => [
		      'type' => 'warning', 
		      'title'  =>  '# Invalid URL',
		      'msg' =>  'Do Not write hard URL\'s'
		      ]
		  ]);
		}

		$this->data['routine'] = Routine::find($this->data['root']['option']);
		$this->data['classes'] = Classe::select('name', 'id')->get();
		$this->data['teachers'] = Teacher::select('name', 'id')->get();

		foreach ($this->data['classes'] as $key => $class) {
			
			$this->data['sections']['class_'.$class->id] = Section::select('name', 'id')->where(['class_id' => $class->id])->get();
			$this->data['subjects']['class_'.$class->id] = Subject::select('name', 'id')->where(['class_id' => $class->id])->get();

		}

		$this->data['days'] = $this->days;

		return view('edit_routine', $this->data);
	}

  public function AddRoutine($request){

    $this->Request = $request;
    $this->PostValidate();
    $this->Routines = new Routine;
    $this->SetAttributes();
    $this->Routines->created_by = Auth::user()->id;
    $this->Routines->save();

    return redirect('routines')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Routines Timtable',
          'msg' =>  'Registration Successfull'
          ]
      ]);

  }

  public function DeleteRoutine($request){

//    echo "sdthdryh";
    $this->Request = $request;
    $this->Routines = Routine::find($this->Request->input('id'));
//    $this->Routines->find($this->Request->input('id'));
    $this->Routines->delete();

    if (Request::ajax()) {
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

  public function PostEditRoutine($request){

    $this->Request = $request;
    $this->PostValidate();

    if(Routine::where('id', $this->data['root']['option'])->count() == 0){
    return  redirect('routines')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
    }

    $this->Routines = Routine::find($this->data['root']['option']);

    $this->SetAttributes();
    $this->Routines->updated_by = Auth::user()->id;
    $this->Routines->save();

    return redirect('routines')->with([
        'toastrmsg' => [
          'type' => 'success',
          'title'  =>  'Routines Settings',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
  }

  protected function PostValidate(){
    $this->validate($this->Request, [
        'class'  =>  'required',
        'section'  =>  'required',
        'subject'  =>  'required',
//        'teacher' =>  'required',
        'day' =>  'required',
        'from_time' =>  'required',
        'to_time' =>  'required',
    ]);
  }

  protected function SetAttributes(){
    $this->Routines->class_id = $this->Request->input('class');
    $this->Routines->section_id = $this->Request->input('section');
    $this->Routines->teacher_id = $this->Request->input('teacher');
    $this->Routines->day = $this->Request->input('day');
    $this->Routines->subject_id = $this->Request->input('subject');
    $this->Routines->from_time = $this->Request->input('from_time');
    $this->Routines->to_time = $this->Request->input('to_time');
  }

}
