<?php

namespace App\Http\Controllers\Api\Guardian;

use App\Student;
use App\StudentAttendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function getStudents(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'guardian_id' => 'required|exists:guardians,id',
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $students = Student::where('guardian_id', $request->input('guardian_id'))->get();
        
        // Dynamically append only the attributes you need
        $students->each->append(['current_month_fee', 'attendance_percentage', 'last_exam_grade']);

        return response()->json([
            'students' => $students,
        ]);
    }
}
