<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests;
use App\InvoiceMaster;
use App\InvoiceDetail;
use Auth;
use Carbon\Carbon;
use Request;
use App\Student;
use App\AdditionalFee;
use DB;
use PDF;

class FeesController extends Controller
{

	//  protected $Routes;
	protected $data, $InvoiceMaster, $Request, $Input, $AuthUser, $Student, $AdditionalFee;

	protected $mons = [
			1 => "Jan", 2 => "Feb",
			3 => "Mar", 4 => "Apr",
			5 => "May", 6 => "Jun",
			7 => "Jul", 8 => "Aug",
			9 => "Sep", 10 => "Oct",
			11 => "Nov", 12 => "Dec"
			];

	public function __Construct($Routes, $Request){
		$this->data['root'] = $Routes;
		// Illuminate\hTTP\Request;
		$this->Request = $Request;
		$this->Input = $Request->input();
	}

	public function Index(){
/*		echo "<pre>";
		print_r($this->Input);
		echo "</pre>";*/

		if (Request::ajax()) {
			return Datatables::eloquent(InvoiceMaster::query())->make(true);
/*			return Datatables::eloquent(InvoiceMaster::select(['invoice_master.*', 'students.name'])
				->join('students', 'invoice_master.student_id', '=', 'students.id'))
				->make(true);
*/
/*			return Datatables::queryBuilder(DB::table('invoice_master')
				->select(['invoice_master.*', 'invoice_master.id AS invoice_id', 'students.name'])
				->leftjoin('students', 'invoice_master.student_id', '=', 'students.id'))
				->make(true);*/
		}
		$this->data['months'] = $this->mons;
		$this->data['year'] = Carbon::now()->year;
	    return view('fee', $this->data);
	}

	public function FindStudent(){
		if (Request::ajax()) {
			$students = Student::where('gr_no', 'LIKE', '%'.$this->Input['q'].'%')
								->orwhere('name', 'LIKE', '%'.$this->Input['q'].'%')
									->get();
			foreach ($students as $k=>$student) {
				$data[$k]['id'] = $student->id;
				$data[$k]['text'] = $student->gr_no.' | '.$student->name;
/*				$data[$k]['htm1'] = '<span class="text-danger">';
				$data[$k]['htm2'] = '</span>';*/
			}
			return response(isset($data)? $data : [0 => ['text' => 'No Data Available']]);
		}
		return abort(404);
	}

	public function CreateInvoice(){
		$this->validate($this->Request, [
	        'gr_no'  	=>  'required',
	        'month'  	=>  'required|numeric',
	        'year'  	=>  'required|numeric',
    	]);

		$this->data['student'] = Student::find($this->Request->input('gr_no'));

		if (empty($this->data['student'])) {
			return redirect()->back()->withInput()->withErrors(['gr_no' => 'GR No Not Found !']);
		}

		$this->data['invoice'] = InvoiceMaster::where([
											'payment_month' => Carbon::createFromFormat('d/m/Y', '1/'.$this->Input['month'].'/'.$this->Input['year'])->toDateString(),
											'gr_no' => $this->data['student']->gr_no,
											])->first();


		$this->data['Input'] = $this->Request->input();
		return $this->Index();
	}

	public function UpdateInvoice(){
		$this->Student = Student::findOrfail($this->data['root']['option']);
		$this->AdditionalFee = $this->Student->AdditionalFee;

		$this->InvoiceMaster	=	new InvoiceMaster;
		$this->SetAttributes();
		$this->InvoiceMaster->save();

		$InvoiceDetail	=	new InvoiceDetail;
		$InvoiceDetail->invoice_id = $this->InvoiceMaster->id;
		$InvoiceDetail->fee_name = 'Tuition Fee';
		$InvoiceDetail->amount = $this->Student->tuition_fee;
		$InvoiceDetail->save();

		foreach ($this->AdditionalFee as $row) {
			$InvoiceDetail	=	new InvoiceDetail;
			$InvoiceDetail->invoice_id = $this->InvoiceMaster->id;
			$InvoiceDetail->fee_name = $row->fee_name;
			$InvoiceDetail->amount = $row->amount;
			$InvoiceDetail->save();
		}

		return redirect('fee')->with([
			'toastrmsg' => [
				'type' => 'success', 
				'title'  =>  'Fee Collected',
				'msg' =>  'Save Changes Successfull'
			],
			'invoice_created' => $this->InvoiceMaster->id,
		]);
	}

	protected function SetAttributes(){
		$this->InvoiceMaster->user_id = Auth::user()->id;
		$this->InvoiceMaster->student_id = $this->Student->id;
		$this->InvoiceMaster->gr_no = $this->Student->gr_no;
		$this->InvoiceMaster->payment_month = Carbon::createFromFormat('d/m/Y', $this->Input['date'])->toDateString();
		$this->InvoiceMaster->total_amount = $this->Student->total_amount;
		$this->InvoiceMaster->discount = $this->Student->discount;
		$this->InvoiceMaster->paid_amount = $this->Student->net_amount;
	}

	public function PrintInvoice(){
		$this->data['invoice'] = InvoiceMaster::findOrfail($this->data['root']['option']);
//		return PDF::loadView('printable.view_invoice', $this->data)->stream();
		return view('printable.view_invoice', $this->data);
	}

}
