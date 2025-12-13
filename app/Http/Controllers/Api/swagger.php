<?php
namespace App\Http\Controllers\Api;

/**
 * @OA\OpenApi(
 *   openapi="3.0.0",
 *   @OA\Info(
 *     title="Aligarh API",
 *     version="1.0.0",
 *     description="Educational Institution Management System API - Multi-tenant platform"
 *   ),
 *   @OA\Server(
 *     url="http://localhost",
 *     description="Development Server"
 *   ),
 *   @OA\Components(
 *     @OA\SecurityScheme(
 *       type="http",
 *       description="Bearer token authentication",
 *       name="Token",
 *       in="header",
 *       scheme="bearer",
 *       bearerFormat="JWT",
 *       securityScheme="bearerToken"
 *     )
 *   )
 * )
 */
