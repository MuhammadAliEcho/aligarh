<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\InvoiceMaster;
use App\InvoiceDetail;
use App\InvoiceMonth;
use App\Guardian;
use Auth;
use Carbon\Carbon;
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
	protected $mons = [], $InvoiceMaster;

	public function Index(Request $request, array $data = [], $job = ''){

		if ($request->ajax()) {
			return DataTables::eloquent(InvoiceMaster::query()->with('Student.Guardian:id'))
				->editColumn('created_at', function ($row) {
					return Carbon::parse($row->created_at)->format('Y-m-d');
				})
				->editColumn('guardian_id', function ($row) {
					return $row->Student->Guardian->id;
				})
				->make(true);
		}
		$data['year'] = Carbon::now()->year;
		$data['root'] = $job;

	    return view('admin.fee', $data);
	}

	public function FindStudent(Request $request){
		if ($request->ajax()) {
			$students = Student::where('gr_no', 'LIKE', '%'.$request->Input('q').'%')
								->orwhere('name', 'LIKE', '%'.$request->Input('q').'%')
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

	public function FindInvoice(Request $request){
		$this->validate($request, [
			'gr_no'  	=>  'required',
			//'month'  	=>  'required',
    	]);

		$data['student'] = Student::find($request->input('gr_no'));

		if (empty($data['student'])) {
			return redirect()->back()->withInput()->withErrors(['gr_no' => 'GR No Not Found!']);
		}

		$invoice =	InvoiceMaster::where('student_id', $data['student']->id)->orderBy('id', 'desc')->first();

		if($invoice){

			if($invoice->getRawOriginal('due_date') >= Carbon::now()->toDateString()){
				return redirect('fee')->withInput()->withErrors(['gr_no' => "Invoice# $invoice->id already created"])->with([
					'toastrmsg' => [
						'type' => 'info', 
						'title'  =>  "Invoice# $invoice->id",
						'msg' =>  'Invoice Already Created'
					],
				]);
			}

			if($invoice->getRawOriginal('data_of_payment') >= $invoice->getRawOriginal('due_date')){
				$data['arrears']	=	($invoice->net_amount+$invoice->late_fee) - $invoice->paid_amount;
			} else {
				$data['arrears']	=	$invoice->net_amount - $invoice->paid_amount;
			}
		}

		$data['session'] = AcademicSession::find(Auth::user()->academic_session);

		$data['Input'] = $request->input();

		$data =	$this->FetchMonths($data);
		$job = 'create';
		return $this->Index($request, $data, $job);
	}

	public function GetEditInvoice(Request $request){

		$this->validate($request, [
			'id'  			=>  'required',
		]);

		$data['invoice_master'] = InvoiceMaster::findOrfail($request->input('id'));
		$data['invoice_detail']	=	$data['invoice_master']->InvoiceDetail;
		$data['invoice_months']	=	$data['invoice_master']->InvoiceMonths;
		$data['student']	=	$data['invoice_master']->Student;

		$data['session'] = AcademicSession::find(Auth::user()->academic_session);

		$data['Input'] = $request->input();

		$this->FetchMonths($data);

		foreach ($data['invoice_months'] as $key => $invoice_month) {
			$data['months'][] = [
				'selected' => true,
				'title' => Carbon::createFromFormat('Y-m-d', $invoice_month->getRawOriginal('month'))->format('M-Y'),
				'value' => Carbon::createFromFormat('Y-m-d', $invoice_month->getRawOriginal('month'))->Format('Y-m-d')
			];
		}

	    return view('admin.fee.edit_fee_invoice', $data);

	}

	public function PostEditInvoice(Request $request){

		$this->validate($request, [
			'invoice_id'		=>	'required',
			'months'  			=>  'required',
			'issue_date'		=>	'required|date',
			'due_date'			=>	'required|date|after_or_equal:issue_date',

			'date_of_payment'	=>	'sometimes|required|date',
			'paid_amount'	=>	'sometimes|required|integer',
			'payment_type'	=>	'sometimes|required|in:Cash,Chalan',
		]);

		$InvoiceMaster = InvoiceMaster::findOrfail($request->input('invoice_id'));

		//dd($request->all());

		$InvoiceMaster->fill([

			'created_at'	=>	$request->input('issue_date'),
			'date'			=>	$request->input('issue_date'),
			'due_date'	=>	$request->input('due_date'),

			'total_amount'	=>	$request->input('total_amount'),
			'discount' => $request->input('discount'),
			'net_amount'	=>	$request->input('net_amount'),
			'late_fee'	=>	$request->input('late_fee'),

			'date_of_payment'	=> $request->input('paid')? $request->input('date_of_payment') : '0000-00-00',
			'paid_amount'	=> $request->input('paid')? $request->input('paid_amount') :	0,
			'payment_type'	=> $request->input('paid')? $request->input('payment_type') :	'',

		])->save();

			InvoiceDetail::where('invoice_id', $InvoiceMaster->id)->delete();

		foreach ($request->input('additionalfee') as $key => $fee) {

			InvoiceDetail::create(
				[
					'invoice_id'	=>	$InvoiceMaster->id,
					'fee_name'		=>	$fee['fee_name'],
					'amount'	=>	$fee['amount'],
				]
			);

		}

		InvoiceMonth::where('invoice_id', $InvoiceMaster->id)->delete();

		foreach ($request->input('months') as $month) {
			InvoiceMonth::create(
				[
					'invoice_id' => $InvoiceMaster->id,
					'month' => $month,
					'student_id' => $InvoiceMaster->student_id,
				]
			);
		}

		//dd($request->all());

		return redirect('fee')->with([
			'toastrmsg' => [
				'type' => 'success', 
				'title'  =>  'Invoice',
				'msg' =>  'Invoice Updated Successfull'
			],
			'invoice_created' => $InvoiceMaster->id,
		]);

	}

	protected function FetchMonths($data){
		$data['betweendates']	=	[
//				'start'	=>	$data['session']->getRawOriginal('start'),
//				'start'	=>	$data['student']->getRawOriginal('date_of_enrolled'),
				'start'	=>	Carbon::createFromFormat('Y-m-d', $data['student']->getRawOriginal('date_of_enrolled'))->startOfMonth()->toDateString(),
//				'end'	=>	$data['session']->getRawOriginal('end')
				'end'	=> Carbon::now()->addYear()->startOfMonth()->toDateString()
			];

		$data['payment_months'] = InvoiceMonth::select('month')
											->whereBetween('month', [$data['betweendates']['start'], $data['betweendates']['end']])
											->where([
												'student_id' => $data['student']->id,
											])->get();

		$month = $data['betweendates']['start'];
		while ($month <= $data['betweendates']['end']) {

			if ($data['payment_months']) {
				if ($data['payment_months']->where('month', Carbon::createFromFormat('Y-m-d', $month)->Format('M-Y'))->count() === 0) {
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
		$data['months'] = $this->mons;
		return $data;
	}

	public function CreateInvoice($id, Request $request){
		$this->validate($request, [
			'months'  			=>  'required',
			'issue_date'		=>	'required|date',
			'due_date'			=>	'required|date|after_or_equal:issue_date'
		]);

		
		$Student = Student::findOrfail($id);
		$AdditionalFee = $Student->AdditionalFee;
		
		$validateInvoiceMonth = InvoiceMonth::where('student_id', $Student->id)
			->whereIn('month', $request->input('months'))->get();

		if($validateInvoiceMonth->count()){
			return redirect()->back()->with([
				'toastrmsg' => [
					'type' => 'info', 
					'title'  =>  "Invoice",
					'msg' =>  'Invoice Already Created'
				],
			]);
		}

		$this->SaveInvoice($Student, $request);
		$this->SaveDetails($request, $AdditionalFee, $Student);
		$this->SaveMonths($request, $Student);
		
//		dd($request->all());
		return redirect('fee')->with([
			'toastrmsg' => [
				'type' => 'success', 
				'title'  =>  'Invoice',
				'msg' =>  'Invoice Created Successfull'
			],
			'invoice_created' => $this->InvoiceMaster->id,
		]);
	}

	public function CollectInvoice(Request $request){

		if($request->ajax() == false){
			return redirect('fee')->with([
				'toastrmsg' => [
					'type'	=> 'warning', 
					'title'	=>  'Student Fee',
					'msg'	=>  'Something is wrong!'
				]
			]);
		}

        $validator = Validator::make($request->all(), [
			'invoice_no'  	=>  'required|integer|exists:invoice_master,id',
			'date_of_payment'	=>	'sometimes|required|date',
			'paid_amount'	=>	'sometimes|required|integer',
			'payment_type'	=>	'sometimes|required|in:Cash,Chalan',
        ]);

        if ($validator->fails()) {
			return response([
				'type'	=> 'error', 
				'title'	=>  'Student Fee',
				'msg'	=>  'Error in posting fee invoice'
			], 422);
        }

		$invoice = InvoiceMaster::findOrfail($request->input('invoice_no'));
		$invoice->date_of_payment = $request->input('date_of_payment');
		$invoice->paid_amount = $request->input('paid_amount');
		$invoice->payment_type = $request->input('payment_type');
		$invoice->save();

		return response([
			'type'	=> 'success', 
			'title'	=>  'Student Fee',
			'msg'	=>  'Invoice paid successfully'
		], 200);

	}

	public function GetInvoice(Request $request){

		if($request->ajax() == false){
			return redirect('fee')->with([
				'toastrmsg' => [
					'type'	=> 'warning', 
					'title'	=>  'Student Fee',
					'msg'	=>  'Something is wrong!'
				]
			]);
		}

        $validator = Validator::make($request->all(), [
			'invoice_no'  	=>  'required|integer|exists:invoice_master,id',
        ]);

        if ($validator->fails()) {
			return response([
				'type'	=> 'error', 
				'title'	=>  'Student Fee',
				'msg'	=>  'Invoice No not exists!'
			], 422);
        }
		$invoice = InvoiceMaster::findOrfail($request->input('invoice_no'));
		$data['invoice_detail'] = $invoice->InvoiceDetail;
		$data['invoice_months'] = $invoice->InvoiceMonths;

		$data['invoice']	=	$invoice->getRawOriginal();

		$data['student']	= Student::with('StdClass')->find($data['invoice']['student_id']);

        return response()->json($data, 200, ['Content-Type' => 'application/json'], JSON_NUMERIC_CHECK);
	}

	public function PrintInvoice($id){
		$data['invoice'] = InvoiceMaster::findOrfail($id);
//		return PDF::loadView('admin.printable.view_invoice', $data)->stream();
		return view('admin.printable.view_invoice', $data);
	}

	public function PrintChalan($id){

		$data['invoice'] = InvoiceMaster::with('InvoiceDetail')->with('InvoiceMonths')->findOrfail($id);
		$data['student']	= Student::find($data['invoice']->student_id);
//		dd($data['invoice']->InvoiceMonths[0]->month);
		return view('admin.printable.view_chalan', $data);
	}

	public function GetGroupInvoice(Request $request, $guardian_id)
	{
		$guardian = Guardian::findOrFail($guardian_id);

		$groupInvoice = Student::with([
			'dueInvoice',
			'dueInvoice.InvoiceDetail',
			'dueInvoice.InvoiceMonths',
			'std_class'
		])
			->where('guardian_id', $guardian_id)
			->get();

		$studentNames = $groupInvoice->pluck('name')->all();
		$classNames = $groupInvoice->pluck('std_class.name')->map(function ($name) {
			return $name ?? 'N/A';
		})->all();

		$totalAmount = 0;
		$consolidatedFees = [];
		$uniqueMonths = [];
		$totalDiscount = 0; 
		$dueInvoice = null;

		$groupInvoice->each(function ($student) use (&$totalAmount, &$consolidatedFees, &$uniqueMonths, &$totalDiscount, &$dueInvoice) {
			if ($student->dueInvoice) {
				$invoice = $student->dueInvoice;
				$totalAmount += $invoice->net_amount;
				$totalDiscount += $invoice->discount ?? 0; 

				$dueInvoice = $dueInvoice? $dueInvoice : $invoice->due_date;

				// Unique months
				collect($invoice->InvoiceMonths)
					->pluck('month')
					->each(function ($month) use (&$uniqueMonths) {
						if (!in_array($month, $uniqueMonths)) {
							$uniqueMonths[] = $month;
						}
					});

				// Consolidated fees
				collect($invoice->InvoiceDetail)->each(function ($detail) use (&$consolidatedFees) {
					$consolidatedFees[$detail->fee_name] = ($consolidatedFees[$detail->fee_name] ?? 0) + $detail->amount;
				});
			}
		});

		$data = [
			'dueDate' => $dueInvoice,
			'groupInvoice' => $groupInvoice,
			'guardian' => $guardian,
			'totalAmount' => $totalAmount,
			'totalDiscount' => $totalDiscount,
			'consolidatedFees' => $consolidatedFees,
			'uniqueMonths' => $uniqueMonths,
			'studentNames' => array_unique($studentNames),
			'classNames' => array_unique($classNames),
			'invoiceCount' => $groupInvoice->filter(function ($student) {
				return $student->dueInvoice !== null;
			})->count()
		];

		// ddd($data);
		return view('admin.printable.view_group_chalan', $data);
	}

	public function ShowBulkInvoice(Request $request)
	{
		$ids = session('bulk_invoice_ids');

		if (!$ids || !is_array($ids)) {
			abort(404, 'No invoice data found.');
		}

		$invoices = InvoiceMaster::with(['InvoiceDetail', 'InvoiceMonths'])
			->whereIn('id', $ids)
			->get();

		$students = Student::whereIn('id', $invoices->pluck('student_id')->unique())->get()->keyBy('id');

		return view('admin.printable.view_bulk_invoice', [
			'invoices' => $invoices,
			'students' => $students
		]);
	}



	public function BulkPrintInvoice(Request $request)
	{
		$ids = $request->input('ids');

		if (empty($ids) || !is_array($ids)) {
			return response()->json(['success' => false, 'message' => 'No invoices selected.']);
		}
		session(['bulk_invoice_ids' => $ids]);

		return response()->json([
			'success' => true,
			'redirect_url' => route('fee.bulk.invoice.print')
		]);
	}

	public function GetStudentFee(Request $request){

		if($request->ajax()){

			$this->validate($request, [
				'gr_no'  	=>  'required',
			]);

			$data['student'] = Student::with('AdditionalFee')->find($request->input('gr_no'));

			if (empty($data['student'])) {
				return redirect('fee')->withErrors(['gr_no' => 'GR No Not Found!']);
			}

			return response()->json($data['student'], 
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

	public function UpdateFee(Request $request){

		if($request->ajax()){
			$validator = Validator::make($request->all(), [
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


			$Student = Student::findOrfail($request->input('id'));
			$Student->tuition_fee = $request->input('tuition_fee');
			$Student->late_fee = $request->input('late_fee');
			$Student->net_amount = $request->input('net_amount');
			$Student->discount = $request->input('discount');
			$Student->total_amount = $request->input('total_amount');
			$Student->save();
			$this->UpdateAdditionalFee($Student, $request);
		
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

	protected function UpdateAdditionalFee($Student, $request){
		AdditionalFee::where(['student_id' => $Student->id])->delete();
		if ($request->input('fee') && COUNT($request->input('fee')) >= 1) {
			foreach ($request->input('fee') as $key => $value) {
				$AdditionalFee = new AdditionalFee;
				$AdditionalFee->id = $value['id'];
				$AdditionalFee->student_id = $Student->id;
				$AdditionalFee->fee_name = $value['fee_name'];
				$AdditionalFee->amount = $value['amount'];
				$AdditionalFee->onetime = isset($value['onetime'])? 1 : 0;
				$AdditionalFee->active = isset($value['active'])? 1 : 0;
				$AdditionalFee->save();
			}
		}
	}

	protected function SaveInvoice($Student, $request){
		$this->InvoiceMaster	=	InvoiceMaster::create(
					[
						'student_id' => $Student->id,
						'gr_no' => $Student->gr_no,

						'late_fee'	=>	$request->input('late_fee'),
						'created_at'	=>	$request->input('issue_date'),
						'date'			=>	$request->input('issue_date'),
						'due_date'	=>	$request->input('due_date'),

						'total_amount'	=>	($request->input('total_amount') + $request->input('arrears')),
						'discount' => $request->input('discount'),
						'net_amount'	=>	$request->input('net_amount'),

/* 						'payment_type' => $request->input('payment_type'),
						'chalan_no' => ($request->input('payment_type') == 'Chalan')? $request->input('chalan_no') : null,
						'date' => $request->input('date'), */
					]
				);
	}

	protected function SaveDetails($request, $AdditionalFee, $Student){
		InvoiceDetail::updateOrCreate(
			[
				'invoice_id'	=>	$this->InvoiceMaster->id,
				'fee_name'		=>	'Tuition Fee'
			],
			[
//				'amount'	=>	($this->Student->tuition_fee * COUNT($request->input('months')))
				'amount'	=>	$request->input('total_tuition_fee'),
			]
		);

		InvoiceDetail::updateOrCreate(
			[
				'invoice_id'	=>	$this->InvoiceMaster->id,
				'fee_name'		=>	'Arrears'
			],
			[
				'amount'	=>	$request->input('arrears')
			]
		);

		foreach ($AdditionalFee as $row) {
			if($row->active){
				InvoiceDetail::updateOrCreate(
					[
						'invoice_id'	=>	$this->InvoiceMaster->id,
						'fee_name'		=>	$row->fee_name
					],
					[
						'amount'	=>	($row->onetime)? $row->amount : ($row->amount * COUNT($request->input('months')))
					]
				);
				if($row->onetime){
					$row->active = 0;
					$row->save();

					$Student->total_amount	-=	$row->amount;
					$Student->net_amount	-=	$row->amount;
					$Student->save();

				}
			}
		}
	}

	protected function SaveMonths($request, $Student){
		foreach ($request->input('months') as $month) {
			InvoiceMonth::updateOrCreate(
				[
					'invoice_id' => $this->InvoiceMaster->id,
					'month' => $month,
				],
				[
					'student_id' => $Student->id,
				]
			);
		}
	}

}
