<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Guardian;
use Auth;
use App\Http\Controllers\Controller;

class GuardiansController extends Controller
{

    protected $data, $Guardian, $Request;

    public function __Construct($Routes, Request $Request){
      $this->data['root'] = $Routes;
      $this->Request = $Request;
    }

    protected function PostValidate(){
      $this->validate($this->Request, [
          'name'  =>  'required',
//          'email' =>  'required|email',
//          'profession' =>  'required',
          'income' =>  'numeric',
      ]);
    }

    public function GetGuardian(){

      if ($this->Request->ajax()) {
        return DataTables::eloquent(Guardian::select('name', 'email', 'id', 'phone', 'address'))->make(true);
      }
      
      return view('admin.guardian', $this->data);
    }

    public function GetProfile(){
      $this->data['guardian']  = Guardian::findorfail($this->data['root']['option']);
      return view('admin.guardian_profile', $this->data);
    }

    public function EditGuardian(){

      if(Guardian::where('id', $this->data['root']['option'])->count() == 0){
      return  redirect('guardians')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $this->data['guardian'] = Guardian::find($this->data['root']['option']);
      return view('admin.edit_guardian', $this->data);
    }

    public function PostEditGuardian(Request $request){

      $this->Request = $request;
      $this->PostValidate();

      if(Guardian::where('id', $this->data['root']['option'])->count() == 0){
      return  redirect('guardians')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $this->Guardian = Guardian::find($this->data['root']['option']);
      $this->SetAttributes();
      $this->Guardian->updated_by = Auth::user()->id;
      $this->Guardian->save();

      return redirect('guardians')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Guardians Registration',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
    }

    public function AddGuardian(Request $request){

      $this->Request = $request;
      $this->PostValidate();
      $this->Guardian = new Guardian;
      $this->SetAttributes();
      $this->Guardian->created_by = Auth::user()->id;
      $this->Guardian->save();

      return redirect('guardians')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'guardians Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);

    }

    protected function SetAttributes(){
      $this->Guardian->name = $this->Request->input('name');
      $this->Guardian->email = $this->Request->input('email');
      $this->Guardian->profession = $this->Request->input('profession');
      $this->Guardian->income = $this->Request->input('income');
      $this->Guardian->address = $this->Request->input('address');
      $this->Guardian->phone = $this->Request->input('phone');
    }

}
