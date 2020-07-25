<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\CalcMonths;
use Illuminate\Http\Request;
use App\AcademicSession;
use App\InvoiceMaster;
use Carbon\Carbon;
use App\Student;
use App\Section;
use App\Classe;
use Auth;
use DB;

class FeeCollectionReportController extends Controller
{

	use CalcMonths;

	protected $data, $session, $Request;

	public function __Construct($Routes, $Request){
		$this->Request = $Request;
		$this->data['root'] = $Routes;
	}

	public function Index(){
		$this->data['classes'] = Classe::select('id', 'name')->get();
		$this->data['sections']	= Section::select('id', 'class_id', 'name')->get();
		return view('admin.fee_collection_report', $this->data);
	}


	public function FeeReceiptStatment(Request $request){

		$this->validate($request, [
			'start'		=>	'required',
			'end'    	=>	'required',
		]);

		$this->data['betweendates']	=	['start' => $request->input('start'), 'end' => Carbon::createFromFormat('Y-m-d', $request->input('end'))->endOfMonth()->toDateString()];
		$this->data['statments'] = InvoiceMaster::whereBetween('due_date', $this->data['betweendates'])->with(['Student' => function($qry){
				$qry->select('id', 'name', 'father_name', 'gr_no', 'class_id');
				$qry->with(['StdClass' => function($qry){
					$qry->select('id', 'name', 'numeric_name');
				}]);
		}])->with('InvoiceMonths')->get();
/* 		$this->data['summary'] = DB::table('invoice_master')
								->select(DB::raw('sum(`paid_amount`) AS `paid_amount`, sum(`net_amount`) AS `net_amount`, `due_date`'))
								->groupBy(DB::raw('YEAR(`due_date`), MONTH(`due_date`)'))
								->whereBetween('due_date', $this->data['betweendates'])
								->orderBy('due_date')->get(); */

		return view('admin.printable.fee_receipt_statment', $this->data);
	}

	public function DailyFeeCollection(Request $request){

		$this->validate($request, [
			'start'		=>	'required',
			'end'    	=>	'required',
		]);

		$this->data['betweendates']	=	['start' => $request->input('start'), 'end' => $request->input('end')];
		$this->data['invoice_dates'] = DB::table('invoice_master')
											->select(DB::raw(" `date`, SUM(`discount`) AS `discount` "))
											->whereBetween('date', $this->data['betweendates'])
											->groupBy('date')
											->get();

		$this->data['daily_fee_collection'] = [];
		foreach ($this->data['invoice_dates'] as $key => $date) {

/*			$this->data['daily_fee_collection'][$date->date] = DB::table('invoice_master')
														->select(DB::raw('SUM(`invoice_details`.`amount`) AS amount, `invoice_details`.`fee_name`, `invoice_master`.`payment_type`'))
														->leftJoin('invoice_details', 'invoice_master.id', '=', 'invoice_details.invoice_id')
														->where(['date' => $date->date])
														->groupBy('invoice_details.fee_name')
														->groupBy('invoice_master.payment_type')
														->get();
*/
			$this->data['daily_fee_collection'][$date->date] = DB::table('invoice_master')
														->select(DB::raw(" SUM(`invoice_details`.`amount`) AS `amount`,
															SUM(CASE WHEN `invoice_master`.`payment_type` = 'Cash' THEN `invoice_details`.`amount` ELSE 0 END) AS `cash`,
															SUM(CASE WHEN `invoice_master`.`payment_type` = 'Chalan' THEN `invoice_details`.`amount` ELSE 0 END) AS `chalan`, 
															`invoice_details`.`fee_name`"))
														->leftJoin('invoice_details', 'invoice_master.id', '=', 'invoice_details.invoice_id')
														->where(['date' => $date->date])
														->groupBy('invoice_details.fee_name')
														->get();

		}

		$collectflatten =	collect($this->data['daily_fee_collection'])->flatten(1);

		$this->data['total_cash_amount']	=	$collectflatten->sum('cash');
		$this->data['total_chalan_amount']	=	$collectflatten->Sum('chalan');
		$this->data['total_discount_amount']	=	$this->data['invoice_dates']->sum('discount');
		$this->data['net_total_amount']	=	($collectflatten->sum('amount') - $this->data['total_discount_amount']);


		return view('admin.printable.daily_fee_collection', $this->data);
	}


	public function FreeshipStudents(Request $Request){
		$this->data['classes'] = Classe::with(['Section'	=>	function($qry){
			$qry->with(['Students'	=>	function($qry){
				$qry->WithDiscount()->Active()->OrderBy('name');
			}]);
		}])->get();
//		$this->data['session'] = AcademicSession::find(Auth::user()->academic_session);
		return view('admin.printable.list_freeship_students', $this->data);
	}



	public function UnpaidFeeStatment(Request $request){
		
		$this->validate($request, [
						'month' => 'required',
						'class' => 'required',
					]);
		$this->data = $request->toArray();

		$this->data['session'] = AcademicSession::find(Auth::user()->academic_session);

		$this->data['betweendates']	=	[
				'start'	=>	$this->data['session']->getOriginal('start'),
//				'end'	=>	$request->input('month')
				'end'	=>	Carbon::createFromFormat('Y-m-d', $request->input('month'))->endOfMonth()->toDateString()
			];
		
		if($this->data['session']->getOriginal('end') < $this->data['betweendates']['end'] || $this->data['session']->getOriginal('start') > $this->data['betweendates']['end']){
			return redirect('student-attendance')->withInput()->with([
				'toastrmsg' => [
					'type' => 'error',
					'title'  =>  'Student Unpaid Statment',
					'msg' =>  'Selected Date is Invalid'
					]
			]);
		}
/*
		$qryClasses = new Classe;

		if ($request->has('class')) {
			$qryClasses	=	$qryClasses->where('id', $request->input('class'));
		}

		$classes =	$qryClasses->with(['Section'	=>	function($qry){
			if($this->Request->has('section')){
				$qry->where('id', $this->Request->input('section'));
			}
			$qry->with(['Students'	=>	function($qry){
				$qry->Active();
				$qry->WithOutFullDiscount();
				$qry->with(['Invoices'	=>	function($qry){
					$qry->whereBetween('payment_month', [$this->data['betweendates']['start'], $this->data['betweendates']['end']]);
				}]);
				$qry->with(['AdditionalFee'	=>	function($qry){
					$qry->Active();
				}]);
			}]);
		}])->get();
*/
		$class = Classe::findOrfail($request->input('class'));

		$Students	=	Student::join('academic_session_history', 'students.id', '=', 'academic_session_history.student_id')
//									->select('students.id', 'students.name', 'students.father_name', 'students.gr_no', 'students.tuition_fee', 'students.discount', 'students.net_amount', 'students.date_of_enrolled', 'students.date_of_leaving', 'academic_session_history.class_id AS session_history_class_id', 'students.class_id AS current_class_id')
									->select('students.*', 'academic_session_history.class_id AS session_history_class_id', 'students.class_id AS current_class_id')
									->where([
										'academic_session_history.class_id' => $request->input('class'),
										'academic_session_history.academic_session_id' => Auth::user()->academic_session
										])
									->where('students.date_of_enrolled', '<=', Carbon::createFromFormat('Y-m-d', $this->data['session']->getOriginal('start'))->endOfMonth()->toDateString())
									->Active()
									->WithOutFullDiscount()
									->with(['InvoiceMonths'	=>	function($qry){
										$qry->whereBetween('month', [$this->data['betweendates']['start'], $this->data['betweendates']['end']]);
									}])
									->with(['AdditionalFee'	=>	function($qry){
										$qry->Active();
									}])
									->get();

		$this->data['unpaid_fee_statment'] = [];
//		foreach ($classes as $key => $class) {

//			foreach ($class->Section as $k => $section) {

//				foreach ($section->Students as $key => $student) {
				foreach ($Students as $key => $student) {

					if ($student->getOriginal('date_of_enrolled') > $this->data['session']->getOriginal('start')) {
						$month = Carbon::createFromFormat('Y-m-d', $student->getOriginal('date_of_enrolled'))->startOfMonth()->toDateString();
					} else {
						$month = $this->data['betweendates']['start'];
					}
					$repetStd = false;
//					if($this->data['betweendates']['end'] > $student->getOriginal('date_of_leaving') && $student->getOriginal('date_of_leaving')){
//						$endmonth = Carbon::createFromFormat('Y-m-d', $student->getOriginal('date_of_leaving'))->startOfMonth()->toDateString();
//					} else {
						$endmonth = $this->data['betweendates']['end'];
//					}
					while ($month <= $endmonth) {

						$invoice = $student->InvoiceMonths->where('month', Carbon::createFromFormat('Y-m-d', $month)->format('M-Y'));
//						if (empty($this->data['unpaid_fee_statment'][$class->name.'-'.$section->nick_name])) {
						if (empty($this->data['unpaid_fee_statment'][$class->name])) {
//							$this->data['unpaid_fee_statment'][$class->name.'-'.$section->nick_name] = collect();
							$this->data['unpaid_fee_statment'][$class->name] = collect();
						}
						if ($invoice->count()	<=	0) {

//							$this->data['unpaid_fee_statment'][$class->name.'-'.$section->nick_name]->push([
							$this->data['unpaid_fee_statment'][$class->name]->push([
															'id'	=>	$student->id,
															'gr_no'	=>	$student->gr_no,
															'name'	=>	$student->name,
															'father_name'	=>	$student->father_name,
															'month'	=>	Carbon::createFromFormat('Y-m-d', $month)->format('M-Y'), 
															'amount'	=>	$this->CalculateFee($student, $repetStd),
														]);
							$repetStd = true;
						}
						$month = Carbon::createFromFormat('Y-m-d', $month)->addMonth()->format('Y-m-d');
					}
				}
//			}
//		}

		$this->data['unpaid_fee_statment'] = collect($this->data['unpaid_fee_statment']);
		return view('admin.printable.unpaid_fee_statment', $this->data);

	}

	private function CalculateFee($student, $repeatStd){
		if ($repeatStd) {
			$tot = $student->tuition_fee;
			$tot += $student->AdditionalFee->where('onetime', 0)->SUM('amount');
			$tot -= $student->discount;
			return $tot;
		}
		return $student->net_amount;
	}


	public function YearlyCollectionStatment(Request $request){

		$this->validate($request, [
				'class'	=>	'required',
//				'section'	=>	'required',
			]);


		$this->data['session'] = AcademicSession::find(Auth::user()->academic_session);

		$this->data['class'] = Classe::find($request->input('class'));
//		$this->data['section'] = Section::find($request->input('section'));

		$this->data['annualfeeses']	=	DB::table('invoice_details')
										->select(DB::raw("`invoice_master`.`student_id`, `invoice_details`.`fee_name`, `invoice_details`.`amount`, `invoice_master`.`payment_month`"))
										->join('invoice_master', 'invoice_details.invoice_id', '=', 'invoice_master.id')
										->join('students', 'invoice_master.student_id', '=', 'students.id')
										->join('academic_session_history', 'students.id', '=', 'academic_session_history.student_id')
										->where('invoice_details.fee_name', 'LIKE', 'Annual%')
										->whereBetween('invoice_master.payment_month', [$this->data['session']->getOriginal('start'), $this->data['session']->getOriginal('end')])
										->where([
											'academic_session_history.class_id' => $this->data['class']->id,
											'academic_session_history.academic_session_id' => Auth::user()->academic_session
											])
//										->where('students.section_id', $this->data['section']->id)
										->get();

		$this->data['statment']	= DB::table('students')
									->select(DB::raw("
											`students`.`id`,
											`students`.`gr_no`,
											`students`.`name`,
											`students`.`father_name`,
											SUM( IF( MONTH(`due_date`) = 4, `invoice_master`.`paid_amount`, 0) ) AS `Apr`,
											SUM( IF( MONTH(`due_date`) = 5, `invoice_master`.`paid_amount`, 0) ) AS `May`,
											SUM( IF( MONTH(`due_date`) = 6, `invoice_master`.`paid_amount`, 0) ) AS `Jun`,
											SUM( IF( MONTH(`due_date`) = 7, `invoice_master`.`paid_amount`, 0) ) AS `Jul`,
											SUM( IF( MONTH(`due_date`) = 8, `invoice_master`.`paid_amount`, 0) ) AS `Aug`,
											SUM( IF( MONTH(`due_date`) = 9, `invoice_master`.`paid_amount`, 0) ) AS `Sep`,
											SUM( IF( MONTH(`due_date`) = 10, `invoice_master`.`paid_amount`, 0) ) AS `Oct`,
											SUM( IF( MONTH(`due_date`) = 11, `invoice_master`.`paid_amount`, 0) ) AS `Nov`,
											SUM( IF( MONTH(`due_date`) = 12, `invoice_master`.`paid_amount`, 0) ) AS `Dec`,
											SUM( IF( MONTH(`due_date`) = 1, `invoice_master`.`paid_amount`, 0) ) AS `Jan`,
											SUM( IF( MONTH(`due_date`) = 2, `invoice_master`.`paid_amount`, 0) ) AS `Feb`,
											SUM( IF( MONTH(`due_date`) = 3, `invoice_master`.`paid_amount`, 0) ) AS `Mar`,
											SUM(`invoice_master`.`paid_amount`) AS `total_amount`
										"))
									->join('invoice_master', 'students.id', '=', 'invoice_master.student_id')
									->join('academic_session_history', 'students.id', '=', 'academic_session_history.student_id')
									->whereBetween('invoice_master.due_date', [$this->data['session']->getOriginal('start'), $this->data['session']->getOriginal('end')])
									->where([
											'academic_session_history.class_id' => $this->data['class']->id,
											'academic_session_history.academic_session_id' => Auth::user()->academic_session
											])
//									->where('students.section_id', $this->data['section']->id)
									->groupBy('students.id')
									->orderBy('students.name')
									->get();

		$this->data['months'] = $this->getMonthsFromSession($this->data['session']);

		return view('admin.printable.yearly_collection_statment', $this->data);

	}

}