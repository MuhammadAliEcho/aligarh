<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Guardian;
use Auth;

class ParentsController extends Controller
{

    protected $data, $Parent, $Request;

    public function __Construct($Routes){
      $this->data['root'] = $Routes;
    }

    protected function PostValidate(){
      $this->validate($this->Request, [
          'name'  =>  'required',
//          'email' =>  'required|email',
//          'profession' =>  'required',
          'income' =>  'numeric',
      ]);
    }

    public function GetParent(){
      return view('parent', $this->data);
    }

    public function AjaxGetParent(){
      return Datatables::eloquent(Guardian::select('name', 'email', 'id', 'phone', 'address'))->make(true);
    }

    public function GetProfile(){
      $this->data['parent']  = Guardian::findorfail($this->data['root']['option']);
      return view('parent_profile', $this->data);
    }

    public function EditParent(){

      if(Guardian::where('id', $this->data['root']['option'])->count() == 0){
      return  redirect('parents')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $this->data['parent'] = Guardian::find($this->data['root']['option']);
      return view('edit_parent', $this->data);
    }

    public function PostEditParent(Request $request){

      $this->Request = $request;
      $this->PostValidate();

      if(Guardian::where('id', $this->data['root']['option'])->count() == 0){
      return  redirect('parents')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $this->Parent = Guardian::find($this->data['root']['option']);
      $this->SetAttributes();
      $this->Parent->updated_by = Auth::user()->id;
      $this->Parent->save();

      return redirect('parents')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Parents Registration',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
    }

    public function AddParent(Request $request){

      $this->Request = $request;
      $this->PostValidate();
      $this->Parent = new Guardian;
      $this->SetAttributes();
      $this->Parent->created_by = Auth::user()->id;
      $this->Parent->save();

      return redirect('parents')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Parents Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);

    }

    protected function SetAttributes(){
      $this->Parent->name = $this->Request->input('name');
      $this->Parent->email = $this->Request->input('email');
      $this->Parent->profession = $this->Request->input('profession');
      $this->Parent->income = $this->Request->input('income');
      $this->Parent->address = $this->Request->input('address');
      $this->Parent->phone = $this->Request->input('phone');
    }

}
