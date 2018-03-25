<?php

namespace App\Http\Controllers\Admin;

use Yajra\Datatables\Facades\Datatables;
//use Illuminate\Http\Request;
use App\Http\Requests;
use Request;
use App\Teacher;
use App\Section;
use App\Classe;
use DB;
use Auth;
use App\Http\Controllers\Controller;

class ManageClasses extends Controller
{

	//  protected $Routes;
	protected $data, $Classes, $Request;

	public function __Construct($Routes){
		$this->data['root'] = $Routes;
	}

	public function GetClasses(){
		if(Request::ajax()){
			return Datatables::queryBuilder(DB::table('classes')->leftjoin('teachers', 'classes.teacher_id', '=', 'teachers.id')
																->select('classes.name', 'classes.numeric_name', 'classes.id','teachers.name AS teacher_name')
																)->make(true);
		}
		$this->data['classes'] = DB::table('classes')->leftjoin('teachers', 'classes.teacher_id', '=', 'teachers.id')
															->select('classes.*', 'teachers.name AS teacher_name')
															->get();
		$this->data['teachers'] = Teacher::select('name', 'id')->get();
		return view('admin.classes', $this->data);

/*    $this->data['classes'] = Classe::select('name', 'numeric_name', 'teacher_id', 'id')->teacher()->select('name')->get();
		foreach ($this->data['classes'] as $key => $class) {
			$this->data['classes'][$key]['teacher'] = Classe::find($class['id'])->teacher()->select('name')->get();
		}
			$this->data['Teachers'] = Teacher::select('name')->get();

	echo "<pre>";
	print_r($this->data['classes']);
	echo "</pre>";
	*/
	}

	public function EditClass(){
		$this->data['class'] = Classe::findOrFail($this->data['root']['option']);
/*
		if(Classe::where('id', $this->data['root']['option'])->count() == 0){
		return  redirect('manage-classes')->with([
				'toastrmsg' => [
					'type' => 'warning', 
					'title'  =>  '# Invalid URL',
					'msg' =>  'Do Not write hard URL\'s'
					]
			]);
		}
*///    $this->data['class'] = Classe::find($this->data['root']['option']);
		$this->data['teachers'] = Teacher::select('name', 'id')->get();
		return view('admin.edit_class', $this->data);
	}

	protected function PostValidate(){
		$this->validate($this->Request, [
				'name'  =>  'required',
				'numeric_name'  =>  'required',
				'prifix'  =>  'required',
/*        'teacher' =>  'required'*/
		]);
	}

	public function AddClass($request){

		$this->Request = $request;
		$this->PostValidate();
		$this->Classes = new Classe;
		$this->SetAttributes();
		$this->Classes->created_by = Auth::user()->id;
		$this->Classes->save();

		$Section  = new Section;
		$Section->AddDefaultSection($this->Classes->id, $this->Classes->teacher_id);

		return redirect('manage-classes')->with([
				'toastrmsg' => [
					'type' => 'success', 
					'title'  =>  'Classes Registration',
					'msg' =>  'Registration Successfull'
					]
			]);

	}

	public function PostEditClass($request){

		$this->Request = $request;
		$this->PostValidate();

		if(Classe::where('id', $this->data['root']['option'])->count() == 0){
		return  redirect('manage-classes')->with([
				'toastrmsg' => [
					'type' => 'warning', 
					'title'  =>  '# Invalid URL',
					'msg' =>  'Do Not write hard URL\'s'
					]
			]);
		}

		$this->Classes = Classe::find($this->data['root']['option']);

		$this->SetAttributes();
		$this->Classes->updated_by = Auth::user()->id;
		$this->Classes->save();

		return redirect('manage-classes')->with([
				'toastrmsg' => [
					'type' => 'success',
					'title'  =>  'Classes Registration',
					'msg' =>  'Save Changes Successfull'
					]
			]);
	}

	protected function SetAttributes(){
		$this->Classes->name = $this->Request->input('name');
		$this->Classes->numeric_name = $this->Request->input('numeric_name');
		$this->Classes->teacher_id = $this->Request->input('teacher');
		$this->Classes->prifix = $this->Request->input('prifix');
	}


}
