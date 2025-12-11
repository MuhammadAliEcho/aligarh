<?php

namespace App\Http\Controllers\Api\Guardian;

use Carbon\Carbon;
use App\Model\StudentAttendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    public function getAttendance(Request $request, $id)
    {
        try {
            $attendances = StudentAttendance::select('date', 'status')->where('student_id', $id)
                ->whereMonth('date', Carbon::now()->month)
                ->whereYear('date', Carbon::now()->year)
                ->get();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Attendance fetched successfully.',
            'data' => $attendances
        ], 200);
    }
}
