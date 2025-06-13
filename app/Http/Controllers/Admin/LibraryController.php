<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Book;
use Auth;
use App\Http\Controllers\Controller;

class LibraryController extends Controller
{
    protected $data, $Book, $Request;

    public function __Construct($Routes, $request){
      $this->data['root'] = $Routes;
      $this->Request = $request;
    }

    protected function PostValidate(){
      $this->validate($this->Request, [
          'title'  =>  'required',
          'qty'  =>  'required',
      ]);
    }

    public function GetLibrary(){

      if ($this->Request->ajax()) {
        return DataTables::eloquent(Book::select('id', 'title', 'author', 'edition', 'qty'))->make(true);
      }
      return view('admin.library', $this->data);

    }

    public function EditBook(){

      if(Book::where('id', $this->data['root']['option'])->count() == 0){
      return  redirect('library')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $this->data['book'] = Book::find($this->data['root']['option']);
      return view('admin.edit_book', $this->data);
    }

    public function PostEditBook(Request $request){

      $this->Request = $request;
      $this->PostValidate();

      if(Book::where('id', $this->data['root']['option'])->count() == 0){
      return  redirect('library')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $this->Book = Book::find($this->data['root']['option']);
      $this->SetAttributes();
      $this->Book->updated_by = Auth::user()->id;
      $this->Book->save();

      return redirect('library')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Library Books Registration',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
    }

    public function AddBook(Request $request){

      $this->Request = $request;
      $this->PostValidate();
      $this->Book = new Book;
      $this->SetAttributes();
      $this->Book->created_by = Auth::user()->id;
      $this->Book->save();

      return redirect('library')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Parents Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);

    }

    protected function SetAttributes(){
      $this->Book->title = $this->Request->input('title');
      $this->Book->author = $this->Request->input('author');
      $this->Book->edition = $this->Request->input('edition');
      $this->Book->publisher = $this->Request->input('publisher');
      $this->Book->description = $this->Request->input('description');
      $this->Book->qty = $this->Request->input('qty');
      $this->Book->rate = $this->Request->input('rate');
    }
}
