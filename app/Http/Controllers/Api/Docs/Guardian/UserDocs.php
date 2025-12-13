<?php

namespace App\Http\Controllers\Api\Docs\Guardian;

/**
 * Guardian User API Documentation
 */
class UserDocs
{
    /**
     * @OA\Get(
     *     path="/guardian/user",
     *     summary="Get Guardian User Info",
     *     description="Get authenticated guardian user and profile information",
     *     operationId="guardianGetUser",
     *     tags={"Guardian"},
     *     security={{"bearerToken": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User information retrieved",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="User",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=15),
     *                 @OA\Property(property="name", type="string", example="Ahmed Khan"),
     *                 @OA\Property(property="email", type="string", example="ahmed@example.com"),
     *                 @OA\Property(property="user_type", type="string", example="guardian"),
     *                 @OA\Property(property="foreign_id", type="integer", example=10)
     *             ),
     *             @OA\Property(
     *                 property="Profile",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="name", type="string", example="Ahmed Khan"),
     *                 @OA\Property(property="phone", type="string", example="03001234567"),
     *                 @OA\Property(property="address", type="string", example="House 123, Block A, Aligarh")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function user() {}
}
