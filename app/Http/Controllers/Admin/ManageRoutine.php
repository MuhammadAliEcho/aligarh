<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\Teacher;
use App\Model\Classe;
use App\Model\Section;
use App\Model\Routine;
use App\Model\Subject;
use DB;
use Auth;
use App\Http\Controllers\Controller;
use App\Helpers\PrintableViewHelper;

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
		      'title'  =>  __('modules.routine_invalid_url_title'),
		      'msg' =>  __('modules.common_url_error')
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
          'title'  =>  __('modules.routine_timetable_title'),
          'msg' =>  __('modules.common_register_success')
          ]
      ]);

  }

  public function DeleteRoutine(Request $request){
//    echo "sdthdryh";
    $Routines = Routine::find($request->input('id'));
//    $Routines->find($request->input('id'));
    $Routines->delete();

    if ($request->ajax()) {
      return  response(['type' => 'success','title'  =>  __('modules.routine_timetable_title'),'msg' =>  __('modules.routine_deleted_message')]);
    } else { 
    return redirect('routines')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  __('modules.routine_timetable_title'),
          'msg' =>  __('modules.routine_deleted_message')
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
          'title'  =>  __('modules.routine_invalid_url_title'),
          'msg' =>  __('modules.common_url_error')
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
          'title'  =>  __('modules.routine_settings_title'),
          'msg' =>  __('modules.common_save_success')
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

  public function printTimetable($classId, $sectionId = null) {
    $class = Classe::findOrFail($classId);
    $data['class'] = $class;

    if ($sectionId) {
      $sections = Section::where('id', $sectionId)->where('class_id', $classId)->get();
    } else {
      $sections = Section::where('class_id', $classId)->get();
    }

    $data['sections'] = $sections;
    $data['routines'] = [];

    foreach ($sections as $section) {
      $this->RoutinesSortDays($section);
      $data['routines']['section_' . $section->id] = $this->data['routines']['section_' . $section->id];
    }

    $data['days'] = $this->days;

    $view = PrintableViewHelper::resolve('routine_timetable');
    return view($view, $data);
  }

}
