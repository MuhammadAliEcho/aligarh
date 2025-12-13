<?php

namespace App\Http\Controllers\Api\Docs\Guardian;

/**
 * Guardian Student API Documentation
 */
class StudentDocs
{
    /**
     * @OA\Post(
     *     path="/guardian/students",
     *     summary="Get Students List",
     *     description="Retrieve list of students associated with the authenticated guardian",
     *     operationId="guardianGetStudents",
     *     tags={"Guardian"},
     *     security={{"bearerToken": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Students list retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="gr_no", type="string", example="GR001")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function getStudents() {}

    /**
     * @OA\Get(
     *     path="/guardian/attendance/{student_id}",
     *     summary="Get Student Attendance",
     *     description="Retrieve attendance records for a specific student",
     *     operationId="guardianGetAttendance",
     *     tags={"Guardian"},
     *     security={{"bearerToken": {}}},
     *     @OA\Parameter(
     *         name="student_id",
     *         in="path",
     *         required=true,
     *         description="Student ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Attendance records retrieved",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="date", type="string", format="date", example="2024-01-15"),
     *                 @OA\Property(property="status", type="string", example="present")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Student not found")
     * )
     */
    public function getAttendance() {}

    /**
     * @OA\Post(
     *     path="/guardian/student-profile",
     *     summary="Get Student Profile",
     *     description="Retrieve detailed profile information for a student",
     *     operationId="guardianGetStudentProfile",
     *     tags={"Guardian"},
     *     security={{"bearerToken": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", description="Student ID", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Student profile retrieved"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function getStudentProfile() {}
}
