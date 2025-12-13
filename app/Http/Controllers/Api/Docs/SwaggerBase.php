<?php

namespace App\Http\Controllers\Api\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Aligarh API Documentation",
 *     version="1.0.0",
 *     description="Educational Institution Management System - Multi-tenant API"
 * )
 * @OA\Server(url="/", description="Current Tenant API Server")
 * @OA\SecurityScheme(
 *     securityScheme="bearerToken",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class SwaggerBase
{
    // Base configuration for Swagger documentation
}
