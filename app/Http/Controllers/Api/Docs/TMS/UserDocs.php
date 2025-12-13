<?php

namespace App\Http\Controllers\Api\Docs\TMS;

/**
 * TMS User API Documentation
 */
class UserDocs
{
    /**
     * @OA\Get(
     *     path="/tms/user",
     *     summary="Get TMS User Info",
     *     description="Get authenticated TMS user information",
     *     operationId="tmsGetUser",
     *     tags={"TMS"},
     *     security={{"bearerToken": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User information retrieved",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="User",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=25),
     *                 @OA\Property(property="name", type="string", example="Ali Hassan"),
     *                 @OA\Property(property="email", type="string", example="ali.hassan@school.com"),
     *                 @OA\Property(property="user_type", type="string", example="teacher"),
     *                 @OA\Property(property="foreign_id", type="integer", example=5)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function user() {}
}
