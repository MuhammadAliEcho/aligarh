<?php

namespace App\Http\Controllers\Api\Guardian;

use App\Model\Student;
use App\Model\StudentAttendance;
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

        $students = Student::with('StdClass:id,name')->where('guardian_id', $request->input('guardian_id'))->get();
        
        // Dynamically append only the attributes you need
        $students->each->append(['current_month_fee', 'attendance_percentage', 'last_exam_grade']);

        // for flatten the nested relationship
        // $students = $students->map(function ($student) {
        //     $data = $student->toArray();
        //     // Flatten class name
        //     $data['class_name'] = $data['std_class']['name'] ?? null;
        //     // Remove nested StdClass
        //     unset($data['std_class']);
        //     return $data;
        // });

        return response()->json([
            'students' => $students,
        ]);
    }
}
