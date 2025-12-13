<?php

namespace App\Http\Controllers\Api\Docs\Guardian;

/**
 * Guardian Home/Dashboard API Documentation
 */
class HomeDocs
{
    /**
     * @OA\Get(
     *     path="/guardian/home",
     *     summary="Get Guardian Dashboard",
     *     description="Retrieve guardian's home dashboard with profile information, students list, and student images in base64 format",
     *     operationId="guardianHome",
     *     tags={"Guardian"},
     *     security={{"bearerToken": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dashboard data retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="User",
     *                 type="object",
     *                 description="User and profile information",
     *                 @OA\Property(property="User", type="object", description="Authenticated user details"),
     *                 @OA\Property(property="Profile", type="object", description="Guardian profile data")
     *             ),
     *             @OA\Property(
     *                 property="Students",
     *                 type="array",
     *                 description="List of students associated with the guardian",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="gr_no", type="string", example="GR001"),
     *                     @OA\Property(property="class_id", type="integer", example=5),
     *                     @OA\Property(
     *                         property="StdClass",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=5),
     *                         @OA\Property(property="name", type="string", example="Grade 5")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="ImagesBase64",
     *                 type="object",
     *                 description="Student images in base64 format (key: student_id, value: base64 image)",
     *                 example={"1": "data:image/jpeg;base64,/9j/4AAQSkZJRg..."}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated - Invalid or missing token",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function home() {}
}
