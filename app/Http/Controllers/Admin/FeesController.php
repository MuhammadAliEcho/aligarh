<?php

namespace App\Http\Controllers\Admin;

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
		$this->data['months'] = $this->mons;
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

	public function CreateInvoice(){
		$this->validate($this->Request, [
			'gr_no'  	=>  'required',
//			'month'  	=>  'required',
    	]);

		$this->data['student'] = Student::find($this->Request->input('gr_no'));

		if (empty($this->data['student'])) {
			return redirect()->back()->withInput()->withErrors(['gr_no' => 'GR No Not Found!']);
		}

		$this->data['session'] = AcademicSession::find(Auth::user()->academic_session);

		$this->data['betweendates']	=	[
//				'start'	=>	$this->data['session']->getOriginal('start'),
				'start'	=>	$this->data['student']->getOriginal('date_of_enrolled'),
				'end'	=>	$this->data['session']->getOriginal('end')
			];

		$this->data['payment_months'] = InvoiceMaster::select('payment_month')
											->whereBetween('payment_month', [$this->data['betweendates']['start'], $this->data['betweendates']['end']])
											->where([
//												'gr_no' => $this->data['student']->gr_no,
												'student_id' => $this->data['student']->id,
											])->get();

		$this->data['Input'] = $this->Request->input();

		$month = $this->data['betweendates']['start'];
		while ($month <= $this->data['betweendates']['end']) {

			if ($this->data['payment_months']) {
				if ($this->data['payment_months']->where('payment_month', Carbon::createFromFormat('Y-m-d', $month)->Format('M-Y'))->count() === 0) {
					$this->mons[] = [
							'title' => Carbon::createFromFormat('Y-m-d', $month)->Format('M-Y'),
							'value' => Carbon::createFromFormat('Y-m-d', $month)->Format('Y-m-d')
						];
				}
			} else {
				$this->mons[] = [
						'title' => Carbon::createFromFormat('Y-m-d', $month)->Format('M-Y'),
						'value' => Carbon::createFromFormat('Y-m-d', $month)->Format('Y-m-d')
					];
			}

			$month = Carbon::createFromFormat('Y-m-d', $month)->addMonth()->format('Y-m-d');
		}

		return $this->Index();
	}

	public function UpdateInvoice(){

		$this->validate($this->Request, [
			'payment_type'  	=>  'required',
			'months'  			=>  'required',
			'chalan_no'			=>	'required_if:payment_type,Chalan'
		]);

		$this->Student = Student::findOrfail($this->data['root']['option']);
		$this->AdditionalFee = $this->Student->AdditionalFee;

		foreach ($this->Request->input('months') as $month) {
			$this->SaveInvoice($month);
			$this->SaveDetails();
		}


		return redirect('fee')->with([
			'toastrmsg' => [
				'type' => 'success', 
				'title'  =>  'Fee Collected',
				'msg' =>  'Save Changes Successfull'
			],
//			'invoice_created' => $this->InvoiceMaster->id,
		]);
	}

	public function PrintInvoice(){
		$this->data['invoice'] = InvoiceMaster::findOrfail($this->data['root']['option']);
//		return PDF::loadView('admin.printable.view_invoice', $this->data)->stream();
		return view('admin.printable.view_invoice', $this->data);
	}

	public function PrintChalan(){

		$this->validate($this->Request, [
			'months'  			=>  'required',
		]);
//		dd($this->Request->all());
		$this->data['student']	=	Student::findOrfail($this->data['root']['option']);
		$this->data['additionalfee']	=	$this->data['student']->AdditionalFee;

		$ConfigWriter = new ConfigWriter('systemInfo');
		$ConfigWriter->set([
				'next_chalan_no' => (config("systemInfo.next_chalan_no"))+1,
			]);
		$ConfigWriter->save();
		$this->data['months']	=	$this->Request->input('months');
		$this->data['due_date']	= $this->Request->input('due_date');
		$this->data['issue_date']	= $this->Request->input('issue_date');
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

	protected function SaveInvoice($date){
		$this->InvoiceMaster	=	InvoiceMaster::updateOrCreate(
					[
						'student_id' => $this->Student->id,
						'payment_month' => $date,
					],
					[
						'user_id' => Auth::user()->id,
						'gr_no' => $this->Student->gr_no,
						'total_amount' => $this->Student->total_amount,
						'discount' => $this->Student->discount,
						'paid_amount' => $this->Student->net_amount,
						'payment_type' => $this->Request->input('payment_type'),
						'chalan_no' => ($this->Request->input('payment_type') == 'Chalan')? $this->Request->input('chalan_no') : null,
						'date' => $this->Request->input('date'),
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
				'amount'	=>	$this->Student->tuition_fee
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
						'amount'	=>	$row->amount
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

}
