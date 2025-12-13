<?php

namespace App\Http\Controllers\Api\Docs\TMS;

/**
 * TMS Attendance API Documentation
 */
class AttendanceDocs
{
    /**
     * @OA\Post(
     *     path="/tms/attendance",
     *     summary="Record Attendance",
     *     description="Record student attendance via Teacher Management System",
     *     operationId="tmsRecordAttendance",
     *     tags={"TMS"},
     *     security={{"bearerToken": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"attendance_data"},
     *             @OA\Property(
     *                 property="attendance_data",
     *                 type="array",
     *                 description="Array of attendance records",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="student_id", type="integer", example=1),
     *                     @OA\Property(property="status", type="string", enum={"present", "absent", "leave"}, example="present"),
     *                     @OA\Property(property="date", type="string", format="date", example="2024-01-15")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Attendance recorded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Attendance recorded")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function recordAttendance() {}

    /**
     * @OA\Post(
     *     path="/tms/cachedata",
     *     summary="Cache Attendance Data",
     *     description="Cache attendance data for offline sync",
     *     operationId="tmsCacheData",
     *     tags={"TMS"},
     *     security={{"bearerToken": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", description="Data to cache")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Data cached successfully"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function cacheData() {}
}
