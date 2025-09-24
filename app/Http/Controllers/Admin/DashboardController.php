<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Item;
use App\User;
use App\StudentAttendance;
use App\TeacherAttendance;
use App\EmployeeAttendance;
use App\Student;
use App\Employee;
use App\Teacher;
use App\Guardian;
use App\Book;
use App\Classe;
use App\Expense;
use App\InvoiceMaster;
use App\Vendor;
use App\Voucher;
use App\NoticeBoard;

class DashboardController extends Controller
{
	public $Attendance = [
		'Student' 	=> StudentAttendance::class,
		'Teacher' 	=> TeacherAttendance::class,
		'Employee' 	=> EmployeeAttendance::class,
	];

	public function GetDashboard()
	{
		$data['no_of_items'] 		= Item::count();
		$data['no_of_books'] 		= Book::count();
		$data['no_of_users'] 		= User::count();
		$data['no_of_classes'] 		= Classe::count();
		$data['no_of_vendors'] 		= Vendor::count();
		$data['no_of_vouchers'] 	= Voucher::count();
		$data['no_of_students'] 	= Student::SessionCurrent()->active()->count();
		$data['no_of_teachers'] 	= Teacher::count();
		$data['no_of_employees'] 	= Employee::count();
		$data['no_of_guardians'] 	= Guardian::count();
		$data['daily_attendance'] 	= $this->getDailyAttendance();
		$data['timelines'] 			= $this->getNoticeBoard();
		$data['student_capacity'] 	= tenancy()->tenant->system_info['general']['student_capacity'];
		
		$session = Auth::user()->academicSession()->first();
		if ($session && $session->start && $session->end) {
			$start = Carbon::createFromFormat('d/m/Y', $session->start)->format('Y-m-d');
			$end   = Carbon::createFromFormat('d/m/Y', $session->end)->format('Y-m-d');
		} else {
			$start = now()->startOfYear()->toDateString();
			$end   = now()->endOfYear()->toDateString();
		}

		$data['student_attendance'] = $this->getAttendacne($this->Attendance['Student'], $start, $end);
		$data['teacher_attendance'] = $this->getAttendacne($this->Attendance['Teacher'], $start, $end);
		$data['employee_attendance'] = $this->getAttendacne($this->Attendance['Employee'], $start, $end);
		$data['fee_collections'] = $this->getFeeCollections($start, $end);
		$data['expense'] = $this->getExpense($start, $end);
		
		return view('admin.dashboard', $data);
	}

	protected function getAttendacne($Model, $start, $end)
	{
		
		$attendance = $Model::select(
        DB::raw('MONTH(date) as month'),
        DB::raw('COUNT(*) as total_attendance')
    	)->currentYear($start, $end)
		->groupBy(DB::raw('MONTH(date)'))
        ->orderBy(DB::raw('MONTH(date)'))
        ->pluck('total_attendance', 'month');

		$monthlyData = [];
		for ($i = 1; $i <= 12; $i++) {
			$monthlyData[] = $attendance[$i] ?? 0;
		}

    	return $monthlyData;	
	}

	protected function getFeeCollections($start, $end)
	{
		$collectedData = array_fill(0, 12, 0);
		$pendingData = array_fill(0, 12, 0);

		$fees = InvoiceMaster::select(
				DB::raw('MONTH(date) as month'),
				DB::raw('SUM(paid_amount) as collected'),
				DB::raw('SUM(total_amount - paid_amount) as pending')
			)
			->whereBetween('date', [$start, $end])
			->groupBy(DB::raw('MONTH(date)'))
			->get();

		foreach ($fees as $fee) {
			$index = $fee->month - 1;
			$collectedData[$index] = (float) $fee->collected;
			$pendingData[$index] = (float) $fee->pending;
		}

		return 
		[
			'collected' => $collectedData,
			'pending' => $pendingData
		];
	}


	protected function getExpense($start, $end)
	{
			$expenseTypes = ['Salary', 'Utilities', 'Maintenance', 'Others'];
			$expenseData = [];

			foreach ($expenseTypes as $type) {
			$expenseData[$type] = array_fill(0, 12, 0);
			}

			$expenses = Expense::select(
			DB::raw('MONTH(date) as month'),
			'type',
			DB::raw('SUM(amount) as total')
			)
			->whereBetween('date', [$start, $end])
			->groupBy(DB::raw('MONTH(date)'), 'type')
			->get();

			// Populate the data
			foreach ($expenses as $expense) {
			$monthIndex = ($expense->month - 1);
				if (in_array($expense->type, $expenseTypes)) {
				$expenseData[$expense->type][$monthIndex] = (float) $expense->total;
				}
			}

			return $expenseData;
	}

	protected function getDailyAttendance()
	{
		$today = Carbon::today()->toDateString();

		$studentTotal = Student::SessionCurrent()->active()->count();
		$studentPresent = StudentAttendance::whereDate('date', $today)->AttendanceStateTrue()->count();
		$studentPercent = $studentTotal > 0 ? round(($studentPresent / $studentTotal) * 100) : 0;

		$teacherTotal = Teacher::count();
		$teacherPresent = TeacherAttendance::whereDate('date', $today)->AttendanceStateTrue()->count();
		$teacherPercent = $teacherTotal > 0 ? round(($teacherPresent / $teacherTotal) * 100) : 0;

		$employeeTotal = Employee::count();
		$employeePresent = EmployeeAttendance::whereDate('date', $today)->AttendanceStateTrue()->count();
		$employeePercent = $employeeTotal > 0 ? round(($employeePresent / $employeeTotal) * 100) : 0;

		return [
			'student' => [
				'percent' => $studentPercent,
				'present' => $studentPresent,
			],
			'teacher' => [
				'percent' => $teacherPercent,
				'present' => $teacherPresent,
			],
			'employee' => [
				'percent' => $employeePercent,
				'present' => $employeePresent,
			],
		];
	}

	protected function getNoticeBoard()
	{
		$NoticeBoard = NoticeBoard::limit(5)->get();
		$NoticeBoard = $NoticeBoard->map(fn($item) => 
			tap($item, fn($i) => [
				$i->timeline_date = Carbon::parse($i->till_date)->format('F, D'),
				$i->till_date_formatted = Carbon::parse($i->till_date)->format('F d, Y'),
			])
		);

		return $NoticeBoard;
	}

}
