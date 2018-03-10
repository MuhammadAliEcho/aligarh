<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Vendor;
use Auth;

class VendorsController extends Controller
{
    protected $data, $Vendor, $Request;

    public function __Construct($Routes, $request){
      $this->data['root'] = $Routes;
      $this->Request = $request;
    }

    protected function PostValidate(){
      $this->validate($this->Request, [
          'v_name'  =>  'required',
          'c_name'  =>  'required',
          'email' =>  'sometimes|email',
      ]);
    }

    public function GetVendor(){
      return view('vendor', $this->data);
    }

    public function AjaxGetVendor(){
    	if ($this->Request->ajax()) {
	    	return Datatables::eloquent(Vendor::select('v_name', 'c_name', 'email', 'id', 'phone', 'address'))->make(true);
    	}
    	return abort(404);
    }

    public function EditVendor(){

      if(Vendor::where('id', $this->data['root']['option'])->count() == 0){
      return  redirect('vendors')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $this->data['vendor'] = Vendor::find($this->data['root']['option']);
      return view('edit_vendor', $this->data);
    }

    public function PostEditVendor(Request $request){

      $this->Request = $request;
      $this->PostValidate();

      if(Vendor::where('id', $this->data['root']['option'])->count() == 0){
      return  redirect('vendors')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $this->Vendor = Vendor::find($this->data['root']['option']);
      $this->SetAttributes();
      $this->Vendor->updated_by = Auth::user()->id;
      $this->Vendor->save();

      return redirect('vendors')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Parents Registration',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
    }

    public function AddVendor(Request $request){

      $this->Request = $request;
      $this->PostValidate();
      $this->Vendor = new Vendor;
      $this->SetAttributes();
      $this->Vendor->created_by = Auth::user()->id;
      $this->Vendor->save();

      return redirect('vendors')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Parents Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);

    }

    protected function SetAttributes(){
      $this->Vendor->v_name = $this->Request->input('v_name');
      $this->Vendor->c_name = $this->Request->input('c_name');
      $this->Vendor->email = $this->Request->input('email');
      $this->Vendor->phone = $this->Request->input('phone');
      $this->Vendor->address = $this->Request->input('address');
    }
}
