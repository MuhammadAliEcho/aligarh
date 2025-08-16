<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Expense;
use Carbon\Carbon;
use Auth;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
  protected function PostValidate($request){
    $this->validate($request, [
        'type'  =>  'required',
        'description'  =>  'required',
        'amount' =>  'required|numeric',
        'date'  =>  'required',
    ]);
  }

  public function Index(Request $request){

  	if ($request->ajax()) {
	    return DataTables::eloquent(Expense::query())->make(true);
  	}

    return view('admin.expense');
  }

  public function EditExpense($id){
    $data['expense'] = Expense::findOrfail($id);
    return view('admin.edit_expense', $data);
  }

  public function PostEditExpense(Request $request, $id){

    $this->PostValidate($request);

    $Expense = Expense::findOrfail($id);
    $this->SetAttributes($Expense, $request);
    $Expense->updated_by = Auth::user()->id;
    $Expense->save();

    return redirect('expense')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Expenses',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
  }

  public function AddExpense(Request $request){

    $this->PostValidate($request);
    $Expense = new Expense;
    $this->SetAttributes($Expense, $request);
    $Expense->created_by = Auth::user()->id;
    $Expense->save();

    return redirect('expense')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Expense',
          'msg' =>  'Registration Successfull'
          ]
      ]);

  }

  protected function SetAttributes($Expense, $request){
    $Expense->type = $request->input('type');
    $Expense->description = $request->input('description');
    $Expense->amount = $request->input('amount');
    $Expense->date = Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateString();
  }

  public function Summary(Request $request){

    if ($request->ajax() == 0) {
      abort(404);
    }

    $from_date = Carbon::createFromFormat('d/m/Y', $request->input('from_date'))->toDateString();
    $to_date = Carbon::createFromFormat('d/m/Y', $request->input('to_date'))->toDateString();
    $Expense = Expense::whereBetween('date', [$from_date, $to_date]);


    if($request->has('description')){

      $summary = $Expense->where('description', 'LIKE', '%'.$request->input('description').'%');

    } 
    if($request->has('type')){

      $summary = $Expense->where('type', '=', $request->input('type'));

    } else {
      
      $sum['salary'] = Expense::whereBetween('date', [$from_date, $to_date])->where(['type' => 'salary']);
      $sum['bills'] = Expense::whereBetween('date', [$from_date, $to_date])->where(['type' => 'bills']);
      $sum['maintenance'] = Expense::whereBetween('date', [$from_date, $to_date])->where(['type' => 'maintenance']);
      $sum['others'] = Expense::whereBetween('date', [$from_date, $to_date])->where(['type' => 'others']);
      if($request->has('description')){
        $sum['salary'] = $sum['salary']->where('description', 'LIKE', '%'.$request->input('description').'%');
        $sum['bills'] = $sum['bills']->where('description', 'LIKE', '%'.$request->input('description').'%');
        $sum['maintenance'] = $sum['maintenance']->where('description', 'LIKE', '%'.$request->input('description').'%');
        $sum['others'] = $sum['others']->where('description', 'LIKE', '%'.$request->input('description').'%');
      }
      $sum = [
          'salary' => $sum['salary']->sum('amount'),
          'bills' => $sum['bills']->sum('amount'),
          'maintenance' => $sum['maintenance']->sum('amount'),
          'others' => $sum['others']->sum('amount'),
      ];
    
    }

      $summary = $Expense->get();

    return view('admin.ajax.expense_rpt', [
                          'summary' =>  $summary,
                          'Input' => $request->input(),
                          'sum_salary'  =>  isset($sum['salary'])? $sum['salary'] : 0,
                          'sum_bills' =>  isset($sum['bills'])? $sum['bills'] : 0,
                          'sum_maintenance' => isset($sum['maintenance'])? $sum['maintenance'] : 0,
                          'sum_others'  =>  isset($sum['others'])? $sum['others'] : 0,
                          ]);

  }

}
