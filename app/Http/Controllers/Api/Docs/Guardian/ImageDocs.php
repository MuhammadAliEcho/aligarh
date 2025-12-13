<?php

namespace App\Http\Controllers\Api\Docs\Guardian;

/**
 * Guardian Student Image API Documentation
 */
class ImageDocs
{
    /**
     * @OA\Get(
     *     path="/guardian/students/image/{image}",
     *     summary="Get Student Image",
     *     description="Retrieve student profile image",
     *     operationId="guardianGetStudentImage",
     *     tags={"Guardian"},
     *     security={{"bearerToken": {}}},
     *     @OA\Parameter(
     *         name="image",
     *         in="path",
     *         required=true,
     *         description="Image filename",
     *         @OA\Schema(type="string", example="student_101.jpg")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Image retrieved",
     *         @OA\MediaType(
     *             mediaType="image/jpeg",
     *             @OA\Schema(type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Image not found")
     * )
     */
    public function getImage() {}
}
