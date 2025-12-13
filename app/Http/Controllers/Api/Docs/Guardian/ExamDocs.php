<?php

namespace App\Http\Controllers\Api\Docs\Guardian;

/**
 * Guardian Exam API Documentation
 */
class ExamDocs
{
    /**
     * @OA\Post(
     *     path="/guardian/exams",
     *     summary="Get Exams",
     *     description="Retrieve exam information and results for guardian's students",
     *     operationId="guardianGetExams",
     *     tags={"Guardian"},
     *     security={{"bearerToken": {}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="student_id", type="integer", description="Filter by student ID", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exam data retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="exam_id", type="integer", example=1),
     *                 @OA\Property(property="exam_name", type="string", example="Mid Term"),
     *                 @OA\Property(property="student_results", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function getExams() {}
}
