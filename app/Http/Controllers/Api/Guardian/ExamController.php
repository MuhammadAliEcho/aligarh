<?php

namespace App\Http\Controllers\Api\Guardian;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Student;
use App\Exam;
use App\Grade;
use App\StudentResult;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{

	public function GetExams(Request $request)
	{
		$validator = Validator::make(
			$request->all(),
			[
				'student_id'	=> 'required|exists:students,id',
				'session_id'	=> 'required|exists:academic_sessions,id',
			],
		);

		if ($validator->fails()) {
			return response()->json([
				'message' => 'Validation Error',
				'errors' => $validator->errors()
			], 422);
		}

		$student_id =	$request->input('student_id');
		$session_id =	$request->input('session_id');

		$exams = Exam::where('academic_session_id', $session_id)
			->orderBy('id', 'desc')
			->get();

		$exams->map(function ($exam) use ($student_id) {
			$exam->student_results = StudentResult::with(
				'Subject:id,name',
				'SubjectResultAttribute:id,total_marks'
			)
				->where('exam_id', $exam->id)
				->where('student_id', $student_id)
				->get();

			$total_obtain_marks = 0;
			$total_full_marks = 0;

			$exam->student_results->map(function ($result) use (&$total_obtain_marks, &$total_full_marks) {
				$obtain = $result->total_obtain_marks ?? 0;
				$full = $result->SubjectResultAttribute->total_marks ?? 0;

				$total_obtain_marks += $obtain;
				$total_full_marks += $full;

				$result->percentage = $full > 0 ? round(($obtain / $full) * 100, 2) : 0;
				$result->grade = $this->getExamGrade($result->percentage);

				return $result;
			});

			$exam->total_obtain_marks = $total_obtain_marks;
			$exam->total_marks = $total_full_marks;
			$exam->percentage = $total_full_marks > 0 ? round(($total_obtain_marks / $total_full_marks) * 100, 2) : 0;

			$exam->grade = $this->getExamGrade($exam->percentage);

			return $exam;
		});

		return response()->json($exams);
	}

	private function getExamGrade($percentage)
	{
		$grade = Grade::where('from_percent', '<=', $percentage)
			->where('to_percent', '>=', $percentage)
			->value('name');
		return $grade;
	}
}
