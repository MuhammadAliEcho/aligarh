<?php

namespace App\Http\Controllers\Api\Guardian;

use App\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuizController extends Controller
{
    public function GetQuiz($student_id)
    {

        $student = Student::with('quizResults')->findOrFail($student_id);

        $quizzes = $student->quizResults;

        return response()->json(['data' => $quizzes]);
    }
}
