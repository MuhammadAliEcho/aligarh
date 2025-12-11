<?php

namespace App\Http\Controllers\Api\Guardian;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use App\Model\Guardian;
use App\Model\Student;
use App\Model\AcademicSession;
use App\Model\AcademicSessionHistory;
use App\Model\StudentAttendance;
use App\Model\AdditionalFee;
use App\Model\InvoiceMaster;
use App\Model\Exam;
use App\Model\ExamRemark;
use App\Model\Grade;

class StudentProfileController extends Controller
{

    protected $academic_session, $student, $attendance, $week_attend, $fees, $additional_fee, $tot_fee = 0, $exam_short_result;

    public function GetShortProfile(Request $request){

        $this->student = Student::where('id', $request->input('id'))->with(['AdditionalFee' => function($qry){
            $qry->Active();
        }])->first();

        $this->academic_session = AcademicSession::where('start', '<=', Carbon::now()->toDateString())
                                ->where('end', '>=', Carbon::now()->toDateString())->first();

        // Attendance
        $this->LoadShortAttendance();

        // Week Attendance
        $this->LoadWeekAttendance();

        // CalculateFee
        $this->LoadFeeCalculation();

        //ExamResult
        $this->LoadExamShortResult();

        return response()->json([
            'Profile' => [
                'Attendance' => $this->attendance,
                'WeekAttendance' => $this->week_attend,
                'Fees' => [
                    'Amount' => $this->tot_fee? $this->tot_fee : $this->student->net_amount,
                    'Paid' => $this->tot_fee? false : true
                ],
            ],
            'ExamResults' => $this->exam_short_result,
            'ExamGrades'    =>  Grade::all()
        ], 200, ['Content-Type' => 'application/json'], JSON_NUMERIC_CHECK);
    }

	private function CalculateFee($student, $repeatStd){
		if ($repeatStd) {
			$tot = $student->tuition_fee;
			$tot += $student->AdditionalFee->where('onetime', 0)->where('active', 1)->SUM('amount');
			$tot -= $student->discount;
			return $tot;
		}
		return $student->net_amount;
    }
 
    protected function LoadShortAttendance(){

//        $this->attendance = StudentAttendance::whereBetween('date', [$this->academic_session->getRawOriginal('start'), $this->academic_session->getRawOriginal('end')])
        $this->attendance = StudentAttendance::where('date', '>', '2018-01-01')
                            ->select('id', 'student_id', 'status', 'date')
                            ->where('student_id', $this->student->id)
                            ->orderBy('date', 'desc')
                            ->get();
    }

    protected function LoadWeekAttendance(){

        $last_attendance = Carbon::createFromFormat('Y-m-d', $this->attendance[0]->date);

        if($last_attendance->dayOfWeek >= 5){
            $week_start = $last_attendance->subDays(($last_attendance->dayOfWeek==5)? 4 : 5);
        } else {
            $week_start = $last_attendance->subDays($last_attendance->dayOfWeek+6);
        }

//        echo $week_start->toDateString();
//        $week_start = $last_attendance->parse('last friday')->subDays(5);
//        $week_end = Carbon::now()->parse('last friday');
//        echo $week_start->addDay()->toDateString();

        $this->week_attend = [
            collect($this->attendance->where('date', $week_start->toDateString())->first())->put('day', 'mon'),
            collect($this->attendance->where('date', $week_start->addDay()->toDateString())->first())->put('day', 'tue'),
            collect($this->attendance->where('date', $week_start->addDay()->toDateString())->first())->put('day', 'wed'),
            collect($this->attendance->where('date', $week_start->addDay()->toDateString())->first())->put('day', 'thu'),
            collect($this->attendance->where('date', $week_start->addDay()->toDateString())->first())->put('day', 'fri'),
        ];
    }

    protected function LoadFeeCalculation(){
        $last_invoice = InvoiceMaster::where('student_id', $this->student->id)->orderBy('id', 'desc')->first();
        $betweendates	=	[
            'start'	=>	Carbon::createFromFormat('Y-m-d', $last_invoice->getRawOriginal('payment_month'))->endOfMonth()->toDateString(),
            'end'	=>	Carbon::now()->endOfMonth()->toDateString()
        ];

        $month = Carbon::createFromFormat('Y-m-d', $betweendates['start'])->addMonth()->toDateString();
        $repeatStd = false;
        while ($month <= $betweendates['end']) {
            $this->tot_fee += $this->CalculateFee($this->student, $repeatStd);
            $repeatStd = true;
            $month = Carbon::createFromFormat('Y-m-d', $month)->addMonth()->toDateString();
        }
    }

    protected function LoadExamShortResult(){

//		$exams	=	Exam::where('academic_session_id', 1)->get();

        $this->exam_short_result		=	ExamRemark::join('exams', 'exam_remarks.exam_id', '=', 'exams.id')
                                            ->join('academic_sessions', 'exams.academic_session_id', '=', 'academic_sessions.id')
                                            ->select('exam_remarks.*', 'exams.name', 'academic_sessions.title')
//                                            ->wherein('exam_id', $exams->pluck('id'))
                                            ->where('student_id', $this->student->id)
                                            ->with(['StudentResult'	=>	function($qry){
                                                $qry->with('Subject')->with('SubjectResultAttribute');
                                                }]
                                            )->with('Classe')
                                            ->orderBy('exam_id', 'desc')
                                            ->first();
    }

	public function GetImage($image){
        $student  = Student::findorfail($image);
        return response()->download(storage_path('app\\'.str_replace('/', '\\', $student->image_dir)))->header('Content-Type', 'image');
        //        $type = pathinfo($student->image_dir, PATHINFO_EXTENSION);
        //		$image = Storage::get($student->image_dir);
//        return Response('data:image/' . $type . ';base64,' . base64_encode($image))->header('Content-Type', 'image');
//		return Response($image, 200)->header('Content-Type', 'image');
	}

}
