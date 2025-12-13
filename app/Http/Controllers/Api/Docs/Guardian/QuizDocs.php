<?php

namespace App\Http\Controllers\Api\Docs\Guardian;

/**
 * Guardian Quiz API Documentation
 */
class QuizDocs
{
    /**
     * @OA\Get(
     *     path="/guardian/quiz/{student_id}",
     *     summary="Get Student Quiz",
     *     description="Get quiz information for a specific student",
     *     operationId="guardianGetQuiz",
     *     tags={"Guardian"},
     *     security={{"bearerToken": {}}},
     *     @OA\Parameter(
     *         name="student_id",
     *         in="path",
     *         required=true,
     *         description="Student ID",
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quiz data retrieved",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="quizzes",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=10),
     *                     @OA\Property(property="subject", type="string", example="Mathematics"),
     *                     @OA\Property(property="date", type="string", format="date", example="2024-12-14"),
     *                     @OA\Property(property="score", type="integer", example=18),
     *                     @OA\Property(property="total", type="integer", example=20),
     *                     @OA\Property(property="percentage", type="number", format="float", example=90)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function getQuiz() {}
}
