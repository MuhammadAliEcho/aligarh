<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Guardian;
use Auth;
use App\Http\Controllers\Controller;

class GuardiansController extends Controller
{
    protected function PostValidate(Request $request){
      $this->validate($request, [
          'name'  =>  'required',
//          'email' =>  'required|email',
//          'profession' =>  'required',
          'income' =>  'numeric',
      ]);
    }

    public function GetGuardian(Request $request){

      if ($request->ajax()) {
        return DataTables::eloquent(Guardian::select('name', 'email', 'id', 'phone', 'address'))->make(true);
      }
      
      return view('admin.guardian');
    }


    public function Grid(Request $request){

    $Guardians = Guardian::query(); 

		if ($request->filled('search_guardians')) {
			$search = $request->input('search_guardians');

			$Guardians->where(fn($query) => 
			$query->where('name', 'like', "%{$search}%")
				->orWhere('email', 'like', "%{$search}%")
				->orWhere('phone', 'like', "%{$search}%")
				->orWhere('income', 'like', "%{$search}%")
				->orWhere('profession', 'like', "%{$search}%")
			);
		}

		$Guardians = $request->filled('per_page') ? $Guardians->paginate($request->input('per_page')) : $Guardians->get();
		
		return response()->json($Guardians);
  }




    public function GetProfile($id){
      $data['guardian']  = Guardian::findorfail($id);
      return view('admin.guardian_profile', $data);
    }

    public function EditGuardian($id){

      if(Guardian::where('id', $id)->count() == 0){
      return  redirect('guardians')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $data['guardian'] = Guardian::find($id);
      return view('admin.edit_guardian', $data);
    }

    public function PostEditGuardian(Request $request, $id){

      $this->PostValidate($request);

      if(Guardian::where('id', $id)->count() == 0){
      return  redirect('guardians')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $Guardian = Guardian::find($id);
      $this->SetAttributes($Guardian, $request);
      $Guardian->updated_by = Auth::user()->id;
      $Guardian->save();

      return redirect('guardians')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Guardians Registration',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
    }

    public function AddGuardian(Request $request){

      $this->PostValidate($request);
      $Guardian = new Guardian;
      $this->SetAttributes($Guardian, $request);
      $Guardian->created_by = Auth::user()->id;
      $Guardian->save();

      return redirect('guardians')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'guardians Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);

    }

    protected function SetAttributes($Guardian, $request){
      $Guardian->name = $request->input('name');
      $Guardian->email = $request->input('email');
      $Guardian->profession = $request->input('profession');
      $Guardian->income = $request->input('income');
      $Guardian->address = $request->input('address');
      $Guardian->phone = $request->input('phone');
    }

}
