<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Model\Book;
use Auth;
use App\Http\Controllers\Controller;

class LibraryController extends Controller
{
    protected function PostValidate($request){
      $this->validate($request, [
          'title'  =>  'required',
          'qty'  =>  'required',
      ]);
    }

    public function GetLibrary(Request $request){

      if ($request->ajax()) {
        return DataTables::eloquent(Book::select('id', 'title', 'author', 'edition', 'qty'))->make(true);
      }
      return view('admin.library');

    }

    public function EditBook($id){

      if(Book::where('id', $id)->count() == 0){
      return  redirect('library')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  __('modules.routine_invalid_url_title'),
          'msg' =>  __('modules.common_url_error')
          ]
      ]);
      }

      $data['book'] = Book::find($id);
      return view('admin.edit_book', $data);
    }

    public function PostEditBook(Request $request, $id){

      $request = $request;
      $this->PostValidate($request);

      if(Book::where('id', $id)->count() == 0){
      return  redirect('library')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  __('modules.routine_invalid_url_title'),
          'msg' =>  __('modules.common_url_error')
          ]
      ]);
      }

      $Book = Book::find($id);
      $this->SetAttributes($Book, $request);
      $Book->updated_by = Auth::user()->id;
      $Book->save();

      return redirect('library')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  __('modules.library_books_registration_title'),
          'msg' =>  __('modules.common_save_success')
          ]
      ]);
    }

    public function AddBook(Request $request){

      $request = $request;
      $this->PostValidate($request);
      $Book = new Book;
      $this->SetAttributes($Book, $request);
      $Book->created_by = Auth::user()->id;
      $Book->save();

      return redirect('library')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  __('modules.library_parents_registration_title'),
          'msg' =>  __('modules.common_register_success')
          ]
      ]);

    }

    protected function SetAttributes($Book, $request){
      $Book->title = $request->input('title');
      $Book->author = $request->input('author');
      $Book->edition = $request->input('edition');
      $Book->publisher = $request->input('publisher');
      $Book->description = $request->input('description');
      $Book->qty = $request->input('qty');
      $Book->rate = $request->input('rate');
    }
}
