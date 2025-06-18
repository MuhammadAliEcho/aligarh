<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Teacher;
use App\Section;
use App\Classe;
use DB;
use Auth;
use App\Http\Controllers\Controller;

class ManageClasses extends Controller
{
	public function GetClasses(Request $request){
		if($request->ajax()){
			return DataTables::queryBuilder(DB::table('classes')->leftjoin('teachers', 'classes.teacher_id', '=', 'teachers.id')
																->select('classes.name', 'classes.numeric_name', 'classes.id','teachers.name AS teacher_name')
																)->make(true);
		}
		$data['classes'] = DB::table('classes')->leftjoin('teachers', 'classes.teacher_id', '=', 'teachers.id')
															->select('classes.*', 'teachers.name AS teacher_name')
															->get();
		$data['teachers'] = Teacher::select('name', 'id')->get();
		return view('admin.classes', $data);

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

	public function EditClass($id){
		$data['class'] = Classe::findOrFail($id);
/*
		if(Classe::where('id', $id)->count() == 0){
		return  redirect('manage-classes')->with([
				'toastrmsg' => [
					'type' => 'warning', 
					'title'  =>  '# Invalid URL',
					'msg' =>  'Do Not write hard URL\'s'
					]
			]);
		}
*///    $data['class'] = Classe::find($id);
		$data['teachers'] = Teacher::select('name', 'id')->get();
		return view('admin.edit_class', $data);
	}

	protected function PostValidate($request){
		$this->validate($request, [
				'name'  =>  'required',
				'numeric_name'  =>  'required',
				'prifix'  =>  'required',
/*        'teacher' =>  'required'*/
		]);
	}

	public function AddClass(Request $request){

		$this->PostValidate($request);
		$Classes = new Classe;
		$this->SetAttributes($Classes, $request);
		$Classes->created_by = Auth::user()->id;
		$Classes->save();

		$Section  = new Section;
		$Section->AddDefaultSection($Classes->id, $Classes->teacher_id);

		return redirect('manage-classes')->with([
				'toastrmsg' => [
					'type' => 'success', 
					'title'  =>  'Classes Registration',
					'msg' =>  'Registration Successfull'
					]
			]);

	}

	public function PostEditClass(Request $request, $id){

		$this->PostValidate($request);

		if(Classe::where('id', $id)->count() == 0){
		return  redirect('manage-classes')->with([
				'toastrmsg' => [
					'type' => 'warning', 
					'title'  =>  '# Invalid URL',
					'msg' =>  'Do Not write hard URL\'s'
					]
			]);
		}

		$Classes = Classe::find($id);

		$this->SetAttributes($Classes, $request);
		$Classes->updated_by = Auth::user()->id;
		$Classes->save();

		return redirect('manage-classes')->with([
				'toastrmsg' => [
					'type' => 'success',
					'title'  =>  'Classes Registration',
					'msg' =>  'Save Changes Successfull'
					]
			]);
	}

	protected function SetAttributes($Classes, $request){
		$Classes->name = $request->input('name');
		$Classes->numeric_name = $request->input('numeric_name');
		$Classes->teacher_id = $request->input('teacher');
		$Classes->prifix = $request->input('prifix');
	}


}
