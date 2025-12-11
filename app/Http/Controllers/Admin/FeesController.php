<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Model\InvoiceMaster;
use App\Model\InvoiceDetail;
use App\Model\InvoiceMonth;
use App\Model\Guardian;
use Auth;
use Carbon\Carbon;
use App\Model\Student;
use App\Model\AdditionalFee;
use DB;
use PDF;
use App\Http\Controllers\Controller;
use App\Model\AcademicSession;
use App\Model\Classe;
use App\Helpers\PrintableViewHelper;
use Illuminate\Support\Facades\Validator;
use Larapack\ConfigWriter\Repository as ConfigWriter;

class FeesController extends Controller
{
	protected $mons = [], $InvoiceMaster;

	public function Index(Request $request, array $data = [], $job = '')
	{
		$data['classes'] = Classe::select('id', 'name')->get();

		if ($request->ajax()) {

			$class_id = $request->input('class_id');
			$query = InvoiceMaster::query()->with('Student.Guardian:id', 'Student.StudentClass:id,name');
			if ($class_id) {
				$query->whereHas('Student.StudentClass', function ($q) use ($class_id) {
					$q->where('id', $class_id);
				});
			}
			return DataTables::eloquent($query)
				->editColumn('created_at', function ($row) {
					return Carbon::parse($row->created_at)->format('Y-m-d');
				})
				->editColumn('guardian_id', function ($row) {
					return $row->Student->Guardian->id ?? '';
				})
				->editColumn('class_id', function ($row) {
					return $row->Student->StudentClass->id ?? '';
				})
				->editColumn('class_name', function ($row) {
					return $row->Student->StudentClass->name ?? '';
				})
				->addColumn('paid_status', function ($row) {
					return $row->paid_amount > 0 ? '1' : '0'; // Important: must match dropdown values
				})
				->addColumn('balance', function ($row) {
					return $row->net_amount - $row->paid_amount;
				})
				->filterColumn('paid_status', function($query, $keyword) {
					if ($keyword == '1') {
						$query->where('paid_amount', '>', 0);
					} elseif ($keyword == '0') {
						$query->where('paid_amount', '=', 0);
					}
				})
				->addColumn('due_status', function ($row) {
					return $row->due_date > now() ? '1' : '0'; // Important: must match dropdown values
				})
				->filterColumn('due_status', function($query, $keyword) {
					if ($keyword == '1') {
						$query->where('due_date', '>=', now());
					} elseif ($keyword == '0') {
						$query->where('due_date', '<', now());
					}
				})
				->filterColumn('class_name', function ($query, $keyword) {
					$query->whereHas('Student.StudentClass', function ($q) use ($keyword) {
						$q->where('name', 'like', "%{$keyword}%");
					});
				})
				->make(true);
		}
		$data['year'] = Carbon::now()->year;
		$data['root'] = $job;
		$data['classes'] = Classe::select('id', 'name')->get();
		$data['session'] = Auth::user()->AcademicSession;

		$data['betweendates']	=	[
			'start'	=>	$data['session']->getRawOriginal('start'),
			//				'start'	=>	$data['student']->getRawOriginal('date_of_enrolled'),
			// 'start'	=>	Carbon::createFromFormat('Y-m-d', $data['student']->getRawOriginal('date_of_enrolled'))->startOfMonth()->toDateString(),
			'end'	=>	$data['session']->getRawOriginal('end')
			// 'end'	=> Carbon::now()->addYear()->startOfMonth()->toDateString()
		];

		$month = $data['betweendates']['start'];
		$data['bulk_months'] = [];
		while ($month <= $data['betweendates']['end']) {
			$data['bulk_months'][] = [
				'selected'	=>	false,
				'title' => Carbon::createFromFormat('Y-m-d', $month)->Format('M-Y'),
				'value' => Carbon::createFromFormat('Y-m-d', $month)->Format('Y-m-d')
			];
			$month = Carbon::createFromFormat('Y-m-d', $month)->addMonth()->format('Y-m-d');
		}

		$data['guardians'] = Guardian::select('id', 'name', 'email', 'phone', 'address')->get();

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
		], [
			'gr_no.required'  	=>  __('validation.gr_no_required'),
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
						'msg' =>  __('modules.fees_invoice_already_exists')
					],
				]);
			}

			if($invoice->getRawOriginal('date_of_payment') > $invoice->getRawOriginal('due_date')){
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
				'title'  =>  __('modules.invoice_title'),
				'msg' =>  __('modules.fees_invoice_updated_success')
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
					'title'  =>  __('modules.invoice_title'),
					'msg' =>  __('modules.fees_invoice_already_exists')
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
				'title'  =>  __('modules.invoice_title'),
				'msg' =>  __('modules.fees_invoice_created_success')
			],
			'invoice_created' => $this->InvoiceMaster->id,
		]);
	}

	function CreateBulkInvoice(Request $request){

		$validator = Validator::make($request->all(), [
			'class_id'	=>	'required|exists:classes,id',
			'months'  			=>  'required|array',
			'months.*'  			=>  'required|date',
			'issue_date'		=>	'required|date',
			'due_date'			=>	'required|date|after_or_equal:issue_date',
		]);

		if($validator->fails()){
			return redirect('fee')
				->withErrors($validator)
				->withInput()
				->with([
					'toastrmsg' => [
						'form' => 'fee.bulk.create.invoice',
						'type' 	=> 'error',
						'title' =>  'Invoice',
						'msg' =>  __('modules.fees_invoice_error_creating')
					]
				]);
		}

		$classe = Classe::with('ActiveStudents', 'ActiveStudents.AdditionalFee')->find($request->input('class_id'));
		$monthsCount = collect($request->input('months'))->count();
		$createdInvoices = [];

		foreach ($classe->ActiveStudents as $key => $student) {

			$invoiceMonthCount = InvoiceMonth::where('student_id', $student->id)
				->whereIn('month', $request->input('months'))->count();

			if($invoiceMonthCount){
				continue;	// continue to next
			}

			$previousInvoice =	InvoiceMaster::where('student_id', $student->id)->orderBy('id', 'desc')->first();

			if($previousInvoice && $previousInvoice->getRawOriginal('due_date') >= now()->toDateString()){
				continue; // continue to next
			}

			// Normalize dates
			$issueDate = Carbon::parse($request->input('issue_date'))->startOfDay();
			$dueDate = Carbon::parse($request->input('due_date'))->toDateString(); // Assuming DATE column

			// Months
			$months = $request->input('months', []);
			$monthsCount = count($months);

			// Calculate arrears
			$arrears = 0;
			if ($previousInvoice) {
					$paidAfterDue = $previousInvoice->getRawOriginal('date_of_payment') > $previousInvoice->getRawOriginal('due_date');
					$arrears = ($previousInvoice->net_amount + ($paidAfterDue ? $previousInvoice->late_fee : 0)) - $previousInvoice->paid_amount;
			}

			// Financial calculations
			$tuitionFeeTotal = $student->tuition_fee * $monthsCount;
			$discountTotal = $student->discount * $monthsCount;
			$totalAmount = ($student->total_amount * $monthsCount) + $arrears;
			$netAmount = $totalAmount - $discountTotal;

			// Final invoice data
			$data = [
					'student_id'         => $student->id,
					'gr_no'              => $student->gr_no,
					'late_fee'           => $student->late_fee,
					'months'             => $months,

					'created_at'         => $issueDate,
					'date'               => $issueDate->toDateString(),
					'issue_date'         => $issueDate->toDateString(),
					'due_date'           => $dueDate,

					'arrears'            => $arrears,
					'total_tuition_fee'  => $tuitionFeeTotal,
					'total_amount'       => $totalAmount,
					'discount'           => $discountTotal,
					'net_amount'         => $netAmount,
			];

			$invoice = $this->SaveInvoice($student, $data);

			$this->SaveDetails($data, $student->AdditionalFee?? [], $student);

			$this->SaveMonths($data, $student);

			$createdInvoices[] = [
				'invoice_id'	=>	$invoice->id,
				'student_id'	=>	$student->id
			];

		}

		$no_of_invoices = collect($createdInvoices)->count();

		return redirect('fee')->with([
			'toastrmsg' => [
				'type' => 'success',
				'title'  =>  'Invoice',
				'msg' =>  $no_of_invoices . ' Invoices were created successfully'
			],
			'no_of_invoices'	=>	collect($createdInvoices)->count(),
			'created_invoices' => $createdInvoices,
			'print_voucher' => route('fee.bulk.print.invoice', ['class_id' => $request->input('class_id'), 'paid' => 0, 'due' => 1])
		]);

	}

		function CreateGroupInvoice(Request $request){

		$validator = Validator::make($request->all(), [
			'guardian'	=>	'required|exists:guardians,id',
			'months'  			=>  'required|array',
			'months.*'  			=>  'required|date',
			'issue_date'		=>	'required|date',
			'due_date'			=>	'required|date|after_or_equal:issue_date',
		]);

		if($validator->fails()){
			return redirect('fee')
			->withErrors($validator)
			->withInput()
			->with([
				'toastrmsg' => [
					'form' => 'fee.bulk.create.invoice',
					'type' 	=> 'error',
					'title' =>  __('modules.fees_invoice'),
					'msg' 	=>  __('modules.fees_invoice_error_creating')
				]
			]);
	}		// $classe = Classe::with('ActiveStudents', 'ActiveStudents.AdditionalFee')->find($request->input('class_id'));
		$guardian = Guardian::with('ActiveStudents', 'ActiveStudents.AdditionalFee')->find($request->input('guardian'));
		$monthsCount = collect($request->input('months'))->count();
		$createdInvoices = [];

		foreach ($guardian->ActiveStudents as $key => $student) {

			$invoiceMonthCount = InvoiceMonth::where('student_id', $student->id)
				->whereIn('month', $request->input('months'))->count();

			if($invoiceMonthCount){
				continue;	// continue to next
			}

			$previousInvoice =	InvoiceMaster::where('student_id', $student->id)->orderBy('id', 'desc')->first();

			if($previousInvoice && $previousInvoice->getRawOriginal('due_date') >= now()->toDateString()){
				continue; // continue to next
			}

			// Normalize dates
			$issueDate = Carbon::parse($request->input('issue_date'))->startOfDay();
			$dueDate = Carbon::parse($request->input('due_date'))->toDateString(); // Assuming DATE column

			// Months
			$months = $request->input('months', []);
			$monthsCount = count($months);

			// Calculate arrears
			$arrears = 0;
			if ($previousInvoice) {
					$paidAfterDue = $previousInvoice->getRawOriginal('date_of_payment') > $previousInvoice->getRawOriginal('due_date');
					$arrears = ($previousInvoice->net_amount + ($paidAfterDue ? $previousInvoice->late_fee : 0)) - $previousInvoice->paid_amount;
			}

			// Financial calculations
			$tuitionFeeTotal = $student->tuition_fee * $monthsCount;
			$discountTotal = $student->discount * $monthsCount;
			$totalAmount = ($student->total_amount * $monthsCount) + $arrears;
			$netAmount = $totalAmount - $discountTotal;

			// Final invoice data
			$data = [
					'student_id'         => $student->id,
					'gr_no'              => $student->gr_no,
					'late_fee'           => $student->late_fee,
					'months'             => $months,

					'created_at'         => $issueDate,
					'date'               => $issueDate->toDateString(),
					'issue_date'         => $issueDate->toDateString(),
					'due_date'           => $dueDate,

					'arrears'            => $arrears,
					'total_tuition_fee'  => $tuitionFeeTotal,
					'total_amount'       => $totalAmount,
					'discount'           => $discountTotal,
					'net_amount'         => $netAmount,
			];

			$invoice = $this->SaveInvoice($student, $data);

			$this->SaveDetails($data, $student->AdditionalFee?? [], $student);

			$this->SaveMonths($data, $student);

			$createdInvoices[] = [
				'invoice_id'	=>	$invoice->id,
				'student_id'	=>	$student->id
			];

		}

		$no_of_invoices = collect($createdInvoices)->count();

		return redirect('fee')->with([
			'toastrmsg' => [
				'type' => 'success',
				'title'  =>  'Invoice',
				'msg' =>  $no_of_invoices . ' Invoices were created successfully'
			],
			'no_of_invoices'	=>	collect($createdInvoices)->count(),
			'created_invoices' => $createdInvoices,
			'print_voucher' => route('fee.group.chalan.print', ['guardian_id' => $request->input('guardian')])
		]);

	}

	public function CollectInvoice(Request $request){

		if($request->ajax() == false){
			return redirect('fee')->with([
				'toastrmsg' => [
					'type'	=> 'warning',
					'title'	=>  __('modules.fees_title'),
					'msg'	=>  __('modules.students_error_validation')
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
			'title'	=>  __('modules.fees_title'),
			'msg'	=>  __('modules.fees_invoice_error_posting')
		], 422);
	}		$invoice = InvoiceMaster::findOrfail($request->input('invoice_no'));
		$invoice->date_of_payment = $request->input('date_of_payment');
		$invoice->paid_amount = $request->input('paid_amount');
		$invoice->payment_type = $request->input('payment_type');
		$invoice->save();

	return response([
		'type'	=> 'success',
		'title'	=>  __('modules.fees_title'),
		'msg'	=>  __('modules.fees_invoice_paid_success')
	], 200);	}

	public function GetInvoice(Request $request){

		if($request->ajax() == false){
			return redirect('fee')->with([
				'toastrmsg' => [
					'type'	=> 'warning',
					'title'	=>  __('modules.fees_title'),
					'msg'	=>  __('modules.students_error_validation')
				]
			]);
		}

		$validator = Validator::make($request->all(), [
			'invoice_no'  	=>  'required|integer|exists:invoice_master,id',
		]);

		if ($validator->fails()) {
			return response([
				'type'	=> 'error',
				'title'	=>  __('modules.fees_title'),
				'msg'	=>  __('modules.fees_invoice_not_exists')
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
		return view(PrintableViewHelper::resolve('view_invoice'), $data);
	}

	public function PrintChalan($id){

		$data['invoice'] = InvoiceMaster::with('InvoiceDetail')->with('InvoiceMonths')->findOrfail($id);
		$data['student']	= Student::find($data['invoice']->student_id);
		return view(PrintableViewHelper::resolve('view_chalan'), $data);
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

    // Filter students with due invoices
    $studentsWithInvoices = $groupInvoice->filter(function ($student) {
        return $student->dueInvoice !== null;
    });

    // Abort with 404 if no invoices found
    if ($studentsWithInvoices->isEmpty()) {
        abort(404, 'No due invoices found for this guardian.');
    }

    $studentNames = $studentsWithInvoices->pluck('name')->all();
    $classNames = $studentsWithInvoices->pluck('std_class.name')->map(function ($name) {
        return $name ?? 'N/A';
    })->all();

    $totalAmount = 0;
    $totalLateFee = 0;
    $consolidatedFees = [];
    $uniqueMonths = [];
    $totalDiscount = 0;
    $dueDate = null;

    $studentsWithInvoices->each(function ($student) use (
        &$totalAmount,
				&$totalLateFee,
        &$consolidatedFees,
        &$uniqueMonths,
        &$totalDiscount,
        &$dueDate
    ) {
        $invoice = $student->dueInvoice;

        $totalAmount += $invoice->net_amount;
        $totalLateFee += $invoice->totalLateFee;
        $totalDiscount += $invoice->discount ?? 0;

        if (!$dueDate) {
            $dueDate = $invoice->due_date;
        }

        // Unique months
        collect($invoice->InvoiceMonths ?? [])
            ->pluck('month')
            ->each(function ($month) use (&$uniqueMonths) {
                if (!in_array($month, $uniqueMonths)) {
                    $uniqueMonths[] = $month;
                }
            });

        // Consolidated fees
        collect($invoice->InvoiceDetail ?? [])->each(function ($detail) use (&$consolidatedFees) {
            $consolidatedFees[$detail->fee_name] = ($consolidatedFees[$detail->fee_name] ?? 0) + $detail->amount;
        });
    });

    $data = [
        'dueDate' => $dueDate,
        'groupInvoice' => $studentsWithInvoices->values(),
        'guardian' => $guardian,
        'totalAmount' => $totalAmount,
        'totalLateFee' => $totalLateFee,
        'totalDiscount' => $totalDiscount,
        'consolidatedFees' => $consolidatedFees,
        'uniqueMonths' => $uniqueMonths,
        'studentNames' => array_unique($studentNames),
        'classNames' => array_unique($classNames),
        'invoiceCount' => $studentsWithInvoices->count()
    ];

		// ddd($data);
		return view(PrintableViewHelper::resolve('view_group_chalan'), $data);
	}

	public function BulkPrintInvoice(Request $request)
	{

		// Validate request inputs
		$validator = Validator::make($request->all(),[
			'ids' => 'required_without:class_id|array',
			'ids.*' => 'integer|exists:invoice_master,id',
			'class_id' => 'required_without:ids|integer|exists:classes,id',
		], [
			'ids.required_without' => 'Please select at least one invoice or provide a class.',
			'class_id.required_without' => 'Please select a class or provide invoice IDs.',
			'ids.*.exists' => 'One or more selected invoices do not exist.',
			'class_id.exists' => 'The selected class does not exist.',
		]);

		// ✅ Redirect to 'fee' on validation failure with custom toastr message
		if ($validator->fails()) {
			return redirect('fee')->with([
				'toastrmsg' => [
					'type'  => 'error',
					'title' => __('modules.bulk_print_invoices_title'),
					'msg'   => $validator->errors()->first(), // Show first validation error
				]
			]);
		}

		$ids = $request->input('ids');
		if($ids){
		if (empty($ids) || !is_array($ids)) {
			return redirect('fee')->with([
				'toastrmsg' => [
					'type'	=> 'error',
					'title'	=>  __('modules.fees_invoices'),
					'msg'	=>  __('modules.fees_invoice_select_required')

				]
			]);
		}			if (count($ids) > 50) {
				return redirect('fee')->with([
					'toastrmsg' => [
						'type'	=> 'error',
						'title'	=>  __('modules.fees_invoices'),
						'msg'	=>  __('modules.fees_invoice_print_limit')
					]
				]);
			}
		} else {
			$classe = Classe::findOrFail($request->input('class_id'));
		}

		$invoices = InvoiceMaster::with(['InvoiceDetail', 'InvoiceMonths']);
		if($ids){
			$invoices->whereIn('id', $ids);
		} else {
			$invoices->whereHas('Student', function($q) use ($classe) {
				return $q->where('class_id', $classe->id);
			});
		}

		if($request->filled('paid')) {
			if($request->input('paid') == 1){
				$invoices->paid();
			} else {
				$invoices->unPaid();
			}
		}

		if($request->filled('due')) {
			if($request->input('due') == 1){
				$invoices->due();
			} else {
				$invoices->overDue();
			}
		}

		$invoices =	$invoices->get();

		if ($invoices->isEmpty()) {
			abort(404, 'No invoices found.');
		}

		$studentIds = $invoices->pluck('student_id')->unique();
		$studentsCollection = Student::whereIn('id', $studentIds)->get()->keyBy('id');

		$students = [];
		foreach ($invoices as $invoice) {
			$students[] = $studentsCollection->get($invoice->student_id);
		}

		return view(PrintableViewHelper::resolve('view_bulk_invoice'), [
			'invoices' => $invoices,
			'students' => $students
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
			'title'	=>  __('modules.fees_title'),
			'msg'	=>  __('modules.students_error_validation')
		]
	]);

}	public function UpdateFee(Request $request){

		if($request->ajax()){
			$validator = Validator::make($request->all(), [
				'id' => 'required',
				'tuition_fee' => 'required',
				'fee'	=>	'sometimes|required'
			]);

		if ($validator->fails()) {
			return  [
				'type'	=> 'error',
				'title'	=>  __('modules.fees_title'),
				'msg'	=>  __('modules.fees_validation_error')
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
			'title'	=>  __('modules.fees_title'),
			'msg'	=>  __('modules.fees_update_success')
		];
	}

	return redirect('fee')->with([
		'toastrmsg' => [
			'type'	=> 'warning',
			'title'	=>  __('modules.fees_title'),
			'msg'	=>  __('modules.fees_validation_error')
		]
	]);
}

	protected function UpdateAdditionalFee($Student, $request){
		AdditionalFee::where(['student_id' => $Student->id])->delete();
		if ($request->input('fee') && count($request->input('fee')) >= 1) {
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

						'late_fee'	=>	$request['late_fee']?? 0,
						'created_at'	=>	$request['issue_date']?? 0,
						'date'			=>	$request['issue_date']?? 0,
						'due_date'	=>	$request['due_date']?? 0,

						'total_amount'	=>	(($request['total_amount']?? 0) + ($request['arrears']?? 0)),
						'discount' => $request['discount']?? 0,
						'net_amount'	=>	$request['net_amount']?? 0,

				/* 						'payment_type' => $request->input('payment_type'),
						'chalan_no' => ($request->input('payment_type') == 'Chalan')? $request->input('chalan_no') : null,
						'date' => $request->input('date'), */
			]
		);
		return $this->InvoiceMaster;
	}

	protected function SaveDetails($request, $AdditionalFee, $Student){
		InvoiceDetail::updateOrCreate(
			[
				'invoice_id'	=>	$this->InvoiceMaster->id,
				'fee_name'		=>	'Tuition Fee'
			],
			[
				//				'amount'	=>	($this->Student->tuition_fee * COUNT($request->input('months')))
				'amount'	=>	$request['total_tuition_fee']?? 0,
			]
		);

		InvoiceDetail::updateOrCreate(
			[
				'invoice_id'	=>	$this->InvoiceMaster->id,
				'fee_name'		=>	'Arrears'
			],
			[
				'amount'	=>	$request['arrears']?? 0
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
						'amount'	=>	($row->onetime)? $row->amount : ($row->amount * collect($request['months']?? null)->count())
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
		$months = $request['months']?? [];
		foreach ($months as $month) {
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
