<?php

namespace App\Http\Controllers\Api\Docs\TMS;

/**
 * TMS Authentication API Documentation
 */
class AuthDocs
{
    /**
     * @OA\Post(
     *     path="/tms/login",
     *     summary="TMS Login",
     *     description="Authenticate a TMS (Teacher Management System) user with credentials",
     *     operationId="tmsLogin",
     *     tags={"TMS Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="TMS login credentials",
     *         @OA\JsonContent(
     *             required={"user_id","password"},
     *             @OA\Property(
     *                 property="user_id",
     *                 type="string",
     *                 description="Teacher/Employee username",
     *                 example="teacher_name"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 description="User password",
     *                 example="password123"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGc...")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login() {}

    /**
     * @OA\Post(
     *     path="/tms/logout",
     *     summary="TMS Logout",
     *     description="Logout the TMS user and revoke access token",
     *     operationId="tmsLogout",
     *     tags={"TMS Authentication"},
     *     security={{"bearerToken": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function logout() {}
}
