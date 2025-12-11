<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Model\Item;
use Auth;
use App\Http\Controllers\Controller;

class ItemsController extends Controller
{
    protected function PostValidate($request){
      $this->validate($request, [
          'name'  =>  'required',
          'category'  =>  'required',
      ]);
    }

    public function GetItem(Request $request){
    	if ($request->ajax()) {
	    	return DataTables::eloquent(Item::select('id', 'name', 'category', 'qty', 'location', 'qty_level'))->make(true);
    	}
      return view('admin.item');
    }

    public function EditItem($id){

      if(Item::where('id', $id)->count() == 0){
      return  redirect('items')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  __('modules.common_url_error')
          ]
      ]);
      }

      $data['item'] = Item::find($id);
      return view('admin.edit_item', $data);
    }

    public function PostEditItem(Request $request, $id){

      $this->PostValidate($request);

      if(Item::where('id', $id)->count() == 0){
      return  redirect('items')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  __('modules.common_url_error')
          ]
      ]);
      }

      $Item = Item::find($id);
      $this->SetAttributes($Item, $request);
      $Item->updated_by = Auth::user()->id;
      $Item->save();

      return redirect('items')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Items Registration',
          'msg' =>  __('modules.common_save_success')
          ]
      ]);
    }

    public function AddItem(Request $request){

      $this->PostValidate($request);
      $Item = new Item;
      $this->SetAttributes($Item, $request);
      $Item->created_by = Auth::user()->id;
      $Item->save();

      return redirect('items')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Parents Registration',
          'msg' =>  __('modules.common_register_success')
          ]
      ]);

    }

    protected function SetAttributes($Item, $request){
      $Item->name = $request->input('name');
      $Item->category = $request->input('category');
      $Item->qty = $request->input('qty');
      $Item->location = $request->input('location');
      $Item->qty_level = $request->input('qty_level');
    }
}
