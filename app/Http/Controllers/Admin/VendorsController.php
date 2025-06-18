<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Vendor;
use Auth;
use App\Http\Controllers\Controller;

class VendorsController extends Controller
{
    protected function PostValidate($request){
      $this->validate($request, [
          'v_name'  =>  'required',
          'c_name'  =>  'required',
          'email' =>  'sometimes|email',
      ]);
    }

    public function GetVendor(Request $request){
      if ($request->ajax()) {
        return DataTables::eloquent(Vendor::select('v_name', 'c_name', 'email', 'id', 'phone', 'address'))->make(true);
      }
      return view('admin.vendor');
    }

    public function EditVendor($id){

      if(Vendor::where('id', $id)->count() == 0){
      return  redirect('vendors')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $data['vendor'] = Vendor::find($id);
      return view('admin.edit_vendor', $data);
    }

    public function PostEditVendor(Request $request, $id){

      $this->PostValidate($request);

      if(Vendor::where('id', $id)->count() == 0){
      return  redirect('vendors')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $Vendor = Vendor::find($id);
      $this->SetAttributes($Vendor, $request);
      $Vendor->updated_by = Auth::user()->id;
      $Vendor->save();

      return redirect('vendors')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Parents Registration',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
    }

    public function AddVendor(Request $request){
      $this->PostValidate($request);
      $Vendor = new Vendor;
      $this->SetAttributes($Vendor, $request);
      $Vendor->created_by = Auth::user()->id;
      $Vendor->save();

      return redirect('vendors')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Parents Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);

    }

    protected function SetAttributes($Vendor, $request){
      $Vendor->v_name = $request->input('v_name');
      $Vendor->c_name = $request->input('c_name');
      $Vendor->email = $request->input('email');
      $Vendor->phone = $request->input('phone');
      $Vendor->address = $request->input('address');
    }
}
