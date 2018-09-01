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

	protected $data, $session;

	public function __Construct($Routes){
		$this->data['root'] = $Routes;
	}

	public function Index(){
		$this->data['classes'] = Classe::select('id', 'name')->get();
		foreach ($this->data['classes'] as $key => $class) {
			$this->data['sections']['class_'.$class->id] = Section::select('name', 'id')->where(['class_id' => $class->id])->get();
		}
		return view('admin.fee_collection_report', $this->data);
	}


	public function FeeReceiptStatment(Request $request){

		$this->validate($request, [
			'start'		=>	'required',
			'end'    	=>	'required',
		]);

		$this->data['betweendates']	=	['start' => $request->input('start'), 'end' => $request->input('end')];
		$this->data['statments'] = InvoiceMaster::whereBetween('payment_month', $this->data['betweendates'])->with(['Student' => function($qry){
				$qry->select('id', 'name', 'father_name', 'gr_no', 'class_id');
				$qry->with(['StdClass' => function($qry){
					$qry->select('id', 'name');
				}]);
		}])->get();
		$this->data['summary'] = DB::table('invoice_master')
								->select(DB::raw('sum(`paid_amount`) AS `paid_amount`, `payment_month`'))
								->groupBy('payment_month')
								->whereBetween('payment_month', $this->data['betweendates'])
								->orderBy('payment_month')->get();

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
		$this->data['classes'] = Classe::all();
//		$this->data['session'] = AcademicSession::find(Auth::user()->academic_session);
		return view('admin.printable.list_freeship_students', $this->data);
	}



	public function UnpaidFeeStatment(Request $request){
		
		$this->validate($request, [
						'month' => 'required',
					]);
		$this->data = $request->toArray();

		$this->data['session'] = AcademicSession::find(Auth::user()->academic_session);

		$this->data['betweendates']	=	[
				'start'	=>	$this->data['session']->getOriginal('from'),
				'end'	=>	$request->input('month')
			];

		$qryClasses = new Classe;
		$qrySections = new Section;

		if ($request->has('class')) {
			$qryClasses	=	$qryClasses->where('id', $request->input('class'));
			if($request->has('section')){
				$qrySections	=	$qrySections->where('id', $request->input('section'));
			}
		}

		$classes =	$qryClasses->get();

		$this->data['unpaid_fee_statment'] = [];
		foreach ($classes as $key => $class) {
			$sections	=	$qrySections->where('class_id', $class->id)->get();
			foreach ($sections as $k => $section) {

				$students = Student::where('students.class_id', $class->id)
								->where('students.section_id', $section->id)
								->Active()->get();

				foreach ($students as $key => $student) {
					$this->data['stdinvoices'][$student->id] = InvoiceMaster::where('student_id', $student->id)
											->whereBetween('payment_month', [$this->data['betweendates']['start'], $this->data['betweendates']['end']])
											->get();

					$month = $this->data['betweendates']['start'];

					while ($month <= $this->data['betweendates']['end']) {

						$invoice = $this->data['stdinvoices'][$student->id]->where('payment_month', Carbon::createFromFormat('Y-m-d', $month)->format('M-Y'));
						if ($invoice->count()	<=	0) {

							if (empty($this->data['unpaid_fee_statment'][$class->name.'-'.$section->nick_name])) {
								$this->data['unpaid_fee_statment'][$class->name.'-'.$section->nick_name] = collect();
							}

							$this->data['unpaid_fee_statment'][$class->name.'-'.$section->nick_name]->push([
															'id'	=>	$student->id,
															'gr_no'	=>	$student->gr_no,
															'name'	=>	$student->name,
															'father_name'	=>	$student->father_name,
															'month'	=>	Carbon::createFromFormat('Y-m-d', $month)->format('M-Y'), 
															'amount'	=>	$student->net_amount,
														]);
						}
						$month = Carbon::createFromFormat('Y-m-d', $month)->addMonth()->format('Y-m-d');
					}
				}
			}
		}

		$this->data['unpaid_fee_statment'] = collect($this->data['unpaid_fee_statment']);
		return view('admin.printable.unpaid_fee_statment', $this->data);

	}


	public function YearlyCollectionStatment(Request $request){

		$this->validate($request, [
				'class'	=>	'required',
				'section'	=>	'required',
			]);


		$this->data['session'] = AcademicSession::find(Auth::user()->academic_session);

		$this->data['class'] = Classe::find($request->input('class'));
		$this->data['section'] = Section::find($request->input('section'));

		$this->data['annualfeeses']	=	DB::table('invoice_details')
										->select(DB::raw("`invoice_master`.`student_id`, `invoice_details`.`fee_name`, `invoice_details`.`amount`, `invoice_master`.`payment_month`"))
										->join('invoice_master', 'invoice_details.invoice_id', '=', 'invoice_master.id')
										->join('students', 'invoice_master.student_id', '=', 'students.id')
										->where('invoice_details.fee_name', 'LIKE', 'Annual%')
										->whereBetween('invoice_master.payment_month', [$this->data['session']->getOriginal('from'), $this->data['session']->getOriginal('to')])
										->where('students.class_id', $this->data['class']->id)
										->where('students.section_id', $this->data['section']->id)
										->get();

		$this->data['statment']	= DB::table('students')
									->select(DB::raw("
											`students`.`id`,
											`students`.`gr_no`,
											`students`.`name`,
											`students`.`father_name`,
											SUM( IF( MONTH(`payment_month`) = 4, `invoice_master`.`paid_amount`, 0) ) AS `Apr`,
											SUM( IF( MONTH(`payment_month`) = 5, `invoice_master`.`paid_amount`, 0) ) AS `May`,
											SUM( IF( MONTH(`payment_month`) = 6, `invoice_master`.`paid_amount`, 0) ) AS `Jun`,
											SUM( IF( MONTH(`payment_month`) = 7, `invoice_master`.`paid_amount`, 0) ) AS `Jul`,
											SUM( IF( MONTH(`payment_month`) = 8, `invoice_master`.`paid_amount`, 0) ) AS `Aug`,
											SUM( IF( MONTH(`payment_month`) = 9, `invoice_master`.`paid_amount`, 0) ) AS `Sep`,
											SUM( IF( MONTH(`payment_month`) = 10, `invoice_master`.`paid_amount`, 0) ) AS `Oct`,
											SUM( IF( MONTH(`payment_month`) = 11, `invoice_master`.`paid_amount`, 0) ) AS `Nov`,
											SUM( IF( MONTH(`payment_month`) = 12, `invoice_master`.`paid_amount`, 0) ) AS `Dec`,
											SUM( IF( MONTH(`payment_month`) = 1, `invoice_master`.`paid_amount`, 0) ) AS `Jan`,
											SUM( IF( MONTH(`payment_month`) = 2, `invoice_master`.`paid_amount`, 0) ) AS `Feb`,
											SUM( IF( MONTH(`payment_month`) = 3, `invoice_master`.`paid_amount`, 0) ) AS `Mar`,
											SUM(`invoice_master`.`paid_amount`) AS `total_amount`
										"))
									->join('invoice_master', 'students.id', '=', 'invoice_master.student_id')
									->whereBetween('invoice_master.payment_month', [$this->data['session']->getOriginal('from'), $this->data['session']->getOriginal('to')])
									->where('students.class_id', $this->data['class']->id)
									->where('students.section_id', $this->data['section']->id)
									->groupBy('students.id')
									->orderBy('students.name')
									->get();

		$this->data['months'] = $this->getMonthsFromSession($this->data['session']);

		return view('admin.printable.yearly_collection_statment', $this->data);

	}

}