<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests;
use App\InvoiceMaster;
use App\InvoiceDetail;
use App\InvoiceMonth;
use Auth;
use Carbon\Carbon;
use Request;
use App\Student;
use App\AdditionalFee;
use DB;
use PDF;
use App\Http\Controllers\Controller;
use App\AcademicSession;
use Validator;
use Larapack\ConfigWriter\Repository as ConfigWriter;

class FeesController extends Controller
{

	//  protected $Routes;
	protected $data, $InvoiceMaster, $Request, $Input, $AuthUser, $Student, $AdditionalFee;

	protected $mons = [];

	public function __Construct($Routes, $Request){
		$this->data['root'] = $Routes;
		// Illuminate\hTTP\Request;
		$this->Request = $Request;
		$this->Input = $Request->input();
		$this->data['months']	=	[];
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
				->make(true);
*/
		}
		$this->data['year'] = Carbon::now()->year;
	    return view('admin.fee', $this->data);
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

	public function FindInvoice(){
		$this->validate($this->Request, [
			'gr_no'  	=>  'required',
//			'month'  	=>  'required',
    	]);

		$this->data['student'] = Student::find($this->Request->input('gr_no'));

		if (empty($this->data['student'])) {
			return redirect()->back()->withInput()->withErrors(['gr_no' => 'GR No Not Found!']);
		}

		$invoice =	InvoiceMaster::where('student_id', $this->data['student']->id)->orderBy('id', 'desc')->first();

		if($invoice){

			if($invoice->getOriginal('due_date') >= Carbon::now()->toDateString()){
				return redirect('fee')->withInput()->withErrors(['gr_no' => "Invoice# $invoice->id already created"])->with([
					'toastrmsg' => [
						'type' => 'info', 
						'title'  =>  "Invoice# $invoice->id",
						'msg' =>  'Invoice Already Created'
					],
				]);
			}

			if($invoice->getOriginal('data_of_payment') >= $invoice->getOriginal('due_date')){
				$this->data['arrears']	=	($invoice->net_amount+$invoice->late_fee) - $invoice->paid_amount;
			} else {
				$this->data['arrears']	=	$invoice->net_amount - $invoice->paid_amount;
			}
		}

		$this->data['session'] = AcademicSession::find(Auth::user()->academic_session);

		$this->data['Input'] = $this->Request->input();

		$this->FetchMonths();

		return $this->Index();
	}

	public function GetEditInvoice(){

		$this->validate($this->Request, [
			'id'  			=>  'required',
		]);

		$this->data['invoice_master'] = InvoiceMaster::findOrfail($this->Request->input('id'));
		$this->data['invoice_detail']	=	$this->data['invoice_master']->InvoiceDetail;
		$this->data['invoice_months']	=	$this->data['invoice_master']->InvoiceMonths;
		$this->data['student']	=	$this->data['invoice_master']->Student;

		$this->data['session'] = AcademicSession::find(Auth::user()->academic_session);

		$this->data['Input'] = $this->Request->input();

		$this->FetchMonths();

		foreach ($this->data['invoice_months'] as $key => $invoice_month) {
			$this->data['months'][] = [
				'selected' => true,
				'title' => Carbon::createFromFormat('Y-m-d', $invoice_month->getOriginal('month'))->Format('M-Y'),
				'value' => Carbon::createFromFormat('Y-m-d', $invoice_month->getOriginal('month'))->Format('Y-m-d')
			];
		}

	    return view('admin.fee.edit_fee_invoice', $this->data);

	}

	public function PostEditInvoice(){

		$this->validate($this->Request, [
			'invoice_id'		=>	'required',
			'months'  			=>  'required',
			'issue_date'		=>	'required|date',
			'due_date'			=>	'required|date|after_or_equal:issue_date',

			'date_of_payment'	=>	'sometimes|required|date',
			'paid_amount'	=>	'sometimes|required|integer',
			'payment_type'	=>	'sometimes|required'
		]);

		$this->InvoiceMaster = InvoiceMaster::findOrfail($this->Request->input('invoice_id'));

//		dd($this->Request->all());

		$this->InvoiceMaster->fill([

			'created_at'	=>	$this->Request->input('issue_date'),
			'due_date'	=>	$this->Request->input('due_date'),

			'total_amount'	=>	$this->Request->input('total_amount'),
			'discount' => $this->Request->input('discount'),
			'net_amount'	=>	$this->Request->input('net_amount'),
			'late_fee'	=>	$this->Request->input('late_fee'),

			'date_of_payment'	=> $this->Request->input('paid')? $this->Request->input('date_of_payment') : '0000-00-00',
			'paid_amount'	=> $this->Request->input('paid')? $this->Request->input('paid_amount') :	0,
			'payment_type'	=> $this->Request->input('paid')? $this->Request->input('payment_type') :	'',

		])->save();

			InvoiceDetail::where('invoice_id', $this->InvoiceMaster->id)->delete();

		foreach ($this->Request->input('additionalfee') as $key => $fee) {

			InvoiceDetail::create(
				[
					'invoice_id'	=>	$this->InvoiceMaster->id,
					'fee_name'		=>	$fee['fee_name'],
					'amount'	=>	$fee['amount'],
				]
			);

		}

		InvoiceMonth::where('invoice_id', $this->InvoiceMaster->id)->delete();

		foreach ($this->Request->input('months') as $month) {
			InvoiceMonth::create(
				[
					'invoice_id' => $this->InvoiceMaster->id,
					'month' => $month,
					'student_id' => $this->InvoiceMaster->student_id,
				]
			);
		}

//		dd($this->Request->all());

		return redirect('fee')->with([
			'toastrmsg' => [
				'type' => 'success', 
				'title'  =>  'Invoice',
				'msg' =>  'Invoice Updated Successfull'
			],
			'invoice_created' => $this->InvoiceMaster->id,
		]);

	}

	protected function FetchMonths(){
		$this->data['betweendates']	=	[
//				'start'	=>	$this->data['session']->getOriginal('start'),
//				'start'	=>	$this->data['student']->getOriginal('date_of_enrolled'),
				'start'	=>	Carbon::createFromFormat('Y-m-d', $this->data['student']->getOriginal('date_of_enrolled'))->startOfMonth()->toDateString(),
//				'end'	=>	$this->data['session']->getOriginal('end')
				'end'	=> Carbon::now()->addYear()->startOfMonth()->toDateString()
			];

		$this->data['payment_months'] = InvoiceMonth::select('month')
											->whereBetween('month', [$this->data['betweendates']['start'], $this->data['betweendates']['end']])
											->where([
												'student_id' => $this->data['student']->id,
											])->get();

		$month = $this->data['betweendates']['start'];
		while ($month <= $this->data['betweendates']['end']) {

			if ($this->data['payment_months']) {
				if ($this->data['payment_months']->where('month', Carbon::createFromFormat('Y-m-d', $month)->Format('M-Y'))->count() === 0) {
					$this->mons[] = [
							'selected'	=>	false,
							'title' => Carbon::createFromFormat('Y-m-d', $month)->Format('M-Y'),
							'value' => Carbon::createFromFormat('Y-m-d', $month)->Format('Y-m-d')
						];
				}
			} else {
				$this->mons[] = [
						'selected'	=>	false,
						'title' => Carbon::createFromFormat('Y-m-d', $month)->Format('M-Y'),
						'value' => Carbon::createFromFormat('Y-m-d', $month)->Format('Y-m-d')
					];
			}

			$month = Carbon::createFromFormat('Y-m-d', $month)->addMonth()->format('Y-m-d');
		}
		$this->data['months'] = $this->mons;
	}

	public function CreateInvoice(){

		$this->validate($this->Request, [
			'months'  			=>  'required',
			'issue_date'		=>	'required|date',
			'due_date'			=>	'required|date|after_or_equal:issue_date'
		]);

		
		$this->Student = Student::findOrfail($this->data['root']['option']);
		$this->AdditionalFee = $this->Student->AdditionalFee;
		
		$validateInvoiceMonth = InvoiceMonth::where('student_id', $this->Student->id)
			->whereIn('month', $this->Request->input('months'))->get();

		if($validateInvoiceMonth->count()){
			return redirect()->back()->with([
				'toastrmsg' => [
					'type' => 'info', 
					'title'  =>  "Invoice",
					'msg' =>  'Invoice Already Created'
				],
			]);
		}

		$this->SaveInvoice();
		$this->SaveDetails();
		$this->SaveMonths();
		
//		dd($this->Request->all());
		return redirect('fee')->with([
			'toastrmsg' => [
				'type' => 'success', 
				'title'  =>  'Invoice',
				'msg' =>  'Invoice Created Successfull'
			],
			'invoice_created' => $this->InvoiceMaster->id,
		]);
	}

	public function CollectInvoice(){

		if($this->Request->ajax() == false){
			return redirect('fee')->with([
				'toastrmsg' => [
					'type'	=> 'warning', 
					'title'	=>  'Student Fee',
					'msg'	=>  'Something is wrong!'
				]
			]);
		}

        $validator = Validator::make($this->Request->all(), [
			'invoice_no'  	=>  'required|integer|exists:invoice_master,id',
			'date_of_payment'	=>	'sometimes|required|date',
			'paid_amount'	=>	'sometimes|required|integer',
			'payment_type'	=>	'sometimes|required'
        ]);

        if ($validator->fails()) {
			return response([
				'type'	=> 'error', 
				'title'	=>  'Student Fee',
				'msg'	=>  'Error in posting fee invoice'
			], 422);
        }

		$invoice = InvoiceMaster::findOrfail($this->Request->input('invoice_no'));
		$invoice->date_of_payment = $this->Request->input('date_of_payment');
		$invoice->paid_amount = $this->Request->input('paid_amount');
		$invoice->payment_type = $this->Request->input('payment_type');
		$invoice->save();

		return response([
			'type'	=> 'success', 
			'title'	=>  'Student Fee',
			'msg'	=>  'Invoice paid successfully'
		], 200);

	}

	public function GetInvoice(){

		if($this->Request->ajax() == false){
			return redirect('fee')->with([
				'toastrmsg' => [
					'type'	=> 'warning', 
					'title'	=>  'Student Fee',
					'msg'	=>  'Something is wrong!'
				]
			]);
		}

        $validator = Validator::make($this->Request->all(), [
			'invoice_no'  	=>  'required|integer|exists:invoice_master,id',
        ]);

        if ($validator->fails()) {
			return response([
				'type'	=> 'error', 
				'title'	=>  'Student Fee',
				'msg'	=>  'Invoice No not exists!'
			], 422);
        }
		$invoice = InvoiceMaster::findOrfail($this->Request->input('invoice_no'));
		$this->data['invoice_detail'] = $invoice->InvoiceDetail;
		$this->data['invoice_months'] = $invoice->InvoiceMonths;

		$this->data['invoice']	=	$invoice->getOriginal();

		$this->data['student']	= Student::with('StdClass')->find($this->data['invoice']['student_id']);

        return response()->json($this->data, 200, ['Content-Type' => 'application/json'], JSON_NUMERIC_CHECK);
	}

	public function PrintInvoice(){
		$this->data['invoice'] = InvoiceMaster::findOrfail($this->data['root']['option']);
//		return PDF::loadView('admin.printable.view_invoice', $this->data)->stream();
		return view('admin.printable.view_invoice', $this->data);
	}

	public function PrintChalan(){

		$this->data['invoice'] = InvoiceMaster::with('InvoiceDetail')->with('InvoiceMonths')->findOrfail($this->data['root']['option']);
		$this->data['student']	= Student::find($this->data['invoice']->student_id);
//		dd($this->data['invoice']->InvoiceMonths[0]->month);
		return view('admin.printable.view_chalan', $this->data);
	}

	public function GetStudentFee(){

		if($this->Request->ajax()){

			$this->validate($this->Request, [
				'gr_no'  	=>  'required',
			]);

			$this->data['student'] = Student::with('AdditionalFee')->find($this->Request->input('gr_no'));

			if (empty($this->data['student'])) {
				return redirect('fee')->withErrors(['gr_no' => 'GR No Not Found!']);
			}

			return response()->json($this->data['student'], 
				200, 
				['Content-Type' => 'application/json'],
				JSON_NUMERIC_CHECK);

		}

		return redirect('fee')->with([
									'toastrmsg' => [
										'type'	=> 'warning', 
										'title'	=>  'Student Fee',
										'msg'	=>  'Something is wrong!'
									]
								]);

	}

	public function UpdateFee(){

		if($this->Request->ajax()){
			$validator = Validator::make($this->Request->all(), [
				'id' => 'required',
				'tuition_fee' => 'required',
				'fee'	=>	'sometimes|required'
			]);

			if ($validator->fails()) {
				return  [
					'type'	=> 'error', 
					'title'	=>  'Student Fee',
					'msg'	=>  'Something is wrong!'
				];
			}


			$this->Student = Student::findOrfail($this->Request->input('id'));
			$this->Student->tuition_fee = $this->Request->input('tuition_fee');
			$this->Student->late_fee = $this->Request->input('late_fee');
			$this->Student->net_amount = $this->Request->input('net_amount');
			$this->Student->discount = $this->Request->input('discount');
			$this->Student->total_amount = $this->Request->input('total_amount');
			$this->Student->save();
			$this->UpdateAdditionalFee();
		
			return	[
				'type'	=> 'success', 
				'title'	=>  'Student Fee',
				'msg'	=>  'Update Fee Successfull'
			];
		}
	
		return redirect('fee')->with([
									'toastrmsg' => [
										'type'	=> 'warning', 
										'title'	=>  'Student Fee',
										'msg'	=>  'Something is wrong!'
									]
								]);
	}

	protected function UpdateAdditionalFee(){
		AdditionalFee::where(['student_id' => $this->Student->id])->delete();
		if (COUNT($this->Request->input('fee')) >= 1) {
			foreach ($this->Input['fee'] as $key => $value) {
				$AdditionalFee = new AdditionalFee;
				$AdditionalFee->id = $value['id'];
				$AdditionalFee->student_id = $this->Student->id;
				$AdditionalFee->fee_name = $value['fee_name'];
				$AdditionalFee->amount = $value['amount'];
				$AdditionalFee->onetime = isset($value['onetime'])? 1 : 0;
				$AdditionalFee->active = isset($value['active'])? 1 : 0;
				$AdditionalFee->save();
			}
		}
	}

	protected function SaveInvoice(){
		$this->InvoiceMaster	=	InvoiceMaster::create(
					[
						'student_id' => $this->Student->id,
						'gr_no' => $this->Student->gr_no,

						'late_fee'	=>	$this->Request->input('late_fee'),
						'created_at'	=>	$this->Request->input('issue_date'),
						'due_date'	=>	$this->Request->input('due_date'),

						'total_amount'	=>	($this->Request->input('total_amount') + $this->Request->input('arrears')),
						'discount' => $this->Request->input('discount'),
						'net_amount'	=>	$this->Request->input('net_amount'),

/* 						'payment_type' => $this->Request->input('payment_type'),
						'chalan_no' => ($this->Request->input('payment_type') == 'Chalan')? $this->Request->input('chalan_no') : null,
						'date' => $this->Request->input('date'), */
					]
				);
	}

	protected function SaveDetails(){
		InvoiceDetail::updateOrCreate(
			[
				'invoice_id'	=>	$this->InvoiceMaster->id,
				'fee_name'		=>	'Tuition Fee'
			],
			[
//				'amount'	=>	($this->Student->tuition_fee * COUNT($this->Request->input('months')))
				'amount'	=>	$this->Request->input('total_tuition_fee'),
			]
		);

		InvoiceDetail::updateOrCreate(
			[
				'invoice_id'	=>	$this->InvoiceMaster->id,
				'fee_name'		=>	'Arrears'
			],
			[
				'amount'	=>	$this->Request->input('arrears')
			]
		);

		foreach ($this->AdditionalFee as $row) {
			if($row->active){
				InvoiceDetail::updateOrCreate(
					[
						'invoice_id'	=>	$this->InvoiceMaster->id,
						'fee_name'		=>	$row->fee_name
					],
					[
						'amount'	=>	($row->onetime)? $row->amount : ($row->amount * COUNT($this->Request->input('months')))
					]
				);
				if($row->onetime){
					$row->active = 0;
					$row->save();

					$this->Student->total_amount	-=	$row->amount;
					$this->Student->net_amount	-=	$row->amount;
					$this->Student->save();

				}
			}
		}
	}

	protected function SaveMonths(){
		foreach ($this->Request->input('months') as $month) {
			InvoiceMonth::updateOrCreate(
				[
					'invoice_id' => $this->InvoiceMaster->id,
					'month' => $month,
				],
				[
					'student_id' => $this->Student->id,
				]
			);
		}
	}

}
