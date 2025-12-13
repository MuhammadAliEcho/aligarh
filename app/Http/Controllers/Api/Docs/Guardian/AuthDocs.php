<?php

namespace App\Http\Controllers\Api\Docs\Guardian;

/**
 * Guardian Authentication API Documentation
 */
class AuthDocs
{
    /**
     * @OA\Post(
     *     path="/guardian/login",
     *     summary="Guardian Login",
     *     description="Authenticate a guardian user with credentials and receive an access token",
     *     operationId="guardianLogin",
     *     tags={"Guardian Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Guardian login credentials",
     *         @OA\JsonContent(
     *             required={"user_id","password"},
     *             @OA\Property(
     *                 property="user_id",
     *                 type="string",
     *                 description="Guardian username or ID",
     *                 example="guardian_name"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 description="Guardian password",
     *                 example="password123"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful - Returns access token",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 description="Bearer access token",
     *                 example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
     *             ),
     *             @OA\Property(property="name", type="string", example="Guardian Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials or inactive account",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="unauthorized"),
     *             @OA\Property(property="msg", type="string", example="Invalid credentials")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="validation_error")
     *         )
     *     )
     * )
     */
    public function login() {}

    /**
     * @OA\Post(
     *     path="/guardian/logout",
     *     summary="Guardian Logout",
     *     description="Revoke the current access token and logout the guardian user",
     *     operationId="guardianLogout",
     *     tags={"Guardian Authentication"},
     *     security={{"bearerToken": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
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
    public function logout() {}
}
