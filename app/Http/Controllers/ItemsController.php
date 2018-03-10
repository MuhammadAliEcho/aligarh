<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Item;
use Auth;

class ItemsController extends Controller
{
    protected $data, $Item, $Request;

    public function __Construct($Routes, $request){
      $this->data['root'] = $Routes;
      $this->Request = $request;
    }

    protected function PostValidate(){
      $this->validate($this->Request, [
          'name'  =>  'required',
          'category'  =>  'required',
      ]);
    }

    public function GetItem(){
      return view('item', $this->data);
    }

    public function AjaxGetItem(){
    	if ($this->Request->ajax()) {
	    	return Datatables::eloquent(Item::select('id', 'name', 'category', 'qty', 'location', 'qty_level'))->make(true);
    	}
    	return abort(404);
    }

    public function EditItem(){

      if(Item::where('id', $this->data['root']['option'])->count() == 0){
      return  redirect('items')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $this->data['item'] = Item::find($this->data['root']['option']);
      return view('edit_item', $this->data);
    }

    public function PostEditItem(Request $request){

      $this->Request = $request;
      $this->PostValidate();

      if(Item::where('id', $this->data['root']['option'])->count() == 0){
      return  redirect('items')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $this->Item = Item::find($this->data['root']['option']);
      $this->SetAttributes();
      $this->Item->updated_by = Auth::user()->id;
      $this->Item->save();

      return redirect('items')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Items Registration',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
    }

    public function AddItem(Request $request){

      $this->Request = $request;
      $this->PostValidate();
      $this->Item = new Item;
      $this->SetAttributes();
      $this->Item->created_by = Auth::user()->id;
      $this->Item->save();

      return redirect('items')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Parents Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);

    }

    protected function SetAttributes(){
      $this->Item->name = $this->Request->input('name');
      $this->Item->category = $this->Request->input('category');
      $this->Item->qty = $this->Request->input('qty');
      $this->Item->location = $this->Request->input('location');
      $this->Item->qty_level = $this->Request->input('qty_level');
    }
}
