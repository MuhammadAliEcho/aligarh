<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Facades\Datatables;
//use Illuminate\Http\Request;
use App\Http\Requests;
use Request;
use App\Expense;
use Carbon\Carbon;
use Auth;

class ExpenseController extends Controller
{

//  protected $Routes;
  protected $data, $Expense, $Request, $Input;

  public function __Construct($Routes, $Request){
    $this->data['root'] = $Routes;
    $this->Request = $Request;
    $this->Input = $Request->input();
  }

  protected function PostValidate(){
    $this->validate($this->Request, [
        'type'  =>  'required',
        'description'  =>  'required',
        'amount' =>  'required|numeric',
        'date'  =>  'required',
    ]);
  }

  public function Index(){

  	if (Request::ajax()) {
	    return Datatables::eloquent(Expense::query())->make(true);
  	}

    return view('expense', $this->data);
  }

  public function EditExpense(){
    $this->data['expense'] = Expense::findOrfail($this->data['root']['option']);
    return view('edit_expense', $this->data);
  }

  public function PostEditExpense(){

    $this->PostValidate();

    $this->Expense = Expense::findOrfail($this->data['root']['option']);
    $this->SetAttributes();
    $this->Expense->updated_by = Auth::user()->id;
    $this->Expense->save();

    return redirect('expense')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Expenses',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
  }

  public function AddExpense(){

    $this->PostValidate();
    $this->Expense = new Expense;
    $this->SetAttributes();
    $this->Expense->created_by = Auth::user()->id;
    $this->Expense->save();

    return redirect('expense')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Expense',
          'msg' =>  'Registration Successfull'
          ]
      ]);

  }

  protected function SetAttributes(){
    $this->Expense->type = $this->Input['type'];
    $this->Expense->description = $this->Input['description'];
    $this->Expense->amount = $this->Input['amount'];
    $this->Expense->date = Carbon::createFromFormat('d/m/Y', $this->Input['date'])->toDateString();
  }

  public function Summary(){

    if (Request::ajax() == 0) {
      abort(404);
    }

    $from_date = Carbon::createFromFormat('d/m/Y', $this->Input['from_date'])->toDateString();
    $to_date = Carbon::createFromFormat('d/m/Y', $this->Input['to_date'])->toDateString();
    $Expense = Expense::whereBetween('date', [$from_date, $to_date]);


    if($this->Request->has('description')){

      $summary = $Expense->where('description', 'LIKE', '%'.$this->Input['description'].'%');

    } 
    if($this->Request->has('type')){

      $summary = $Expense->where('type', '=', $this->Input['type']);

    } else {
      
      $sum['salary'] = Expense::whereBetween('date', [$from_date, $to_date])->where(['type' => 'salary']);
      $sum['bills'] = Expense::whereBetween('date', [$from_date, $to_date])->where(['type' => 'bills']);
      $sum['maintenance'] = Expense::whereBetween('date', [$from_date, $to_date])->where(['type' => 'maintenance']);
      $sum['others'] = Expense::whereBetween('date', [$from_date, $to_date])->where(['type' => 'others']);
      if($this->Request->has('description')){
        $sum['salary'] = $sum['salary']->where('description', 'LIKE', '%'.$this->Input['description'].'%');
        $sum['bills'] = $sum['bills']->where('description', 'LIKE', '%'.$this->Input['description'].'%');
        $sum['maintenance'] = $sum['maintenance']->where('description', 'LIKE', '%'.$this->Input['description'].'%');
        $sum['others'] = $sum['others']->where('description', 'LIKE', '%'.$this->Input['description'].'%');
      }
      $sum = [
          'salary' => $sum['salary']->sum('amount'),
          'bills' => $sum['bills']->sum('amount'),
          'maintenance' => $sum['maintenance']->sum('amount'),
          'others' => $sum['others']->sum('amount'),
      ];
    
    }

      $summary = $Expense->get();

    return view('ajax.expense_rpt', [
                          'summary' =>  $summary,
                          'Input' => $this->Input,
                          'sum_salary'  =>  isset($sum['salary'])? $sum['salary'] : 0,
                          'sum_bills' =>  isset($sum['bills'])? $sum['bills'] : 0,
                          'sum_maintenance' => isset($sum['maintenance'])? $sum['maintenance'] : 0,
                          'sum_others'  =>  isset($sum['others'])? $sum['others'] : 0,
                          ]);

  }

}
