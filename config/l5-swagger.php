<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Aligarh API Documentation',
                'description' => 'Educational Institution Management System - API Documentation',
                'version' => '1.0.0',
                'terms_of_service' => '',
                'contact' => [
                    'email' => 'support@aligarh.test',
                ],
                'license' => [
                    'name' => 'Apache 2.0',
                    'url' => 'http://www.apache.org/licenses/LICENSE-2.0.html',
                ],
            ],
            'routes' => [
                [
                    'url' => '/api',
                    'prefix' => 'api',
                    'name' => 'API Routes',
                ],
            ],
            'paths' => [
                'docs' => storage_path('api-docs/swagger.json'),
                'docs_yaml' => storage_path('api-docs/swagger.yaml'),
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),
                'inflect_endpoints' => true,
                'swagger_ui_path' => '/api/documentation',
                'annotations' => [
                    base_path('app/Http/Controllers/Api/Docs'),
                ],
            ],
            'servers' => [
                [
                    'url' => env('APP_URL', 'http://localhost'),
                    'description' => 'Development Server',
                ],
            ],
            'security' => [
                'bearerToken' => [
                    'type' => 'http',
                    'description' => 'Login with username and password to get the authentication token',
                    'name' => 'Token',
                    'scheme' => 'bearer',
                    'bearerFormat' => 'JWT',
                ],
            ],
            'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
            'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),
            'proxy' => false,
            'swagger_version' => '3.0',
            'constants' => [
                'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://my-default-host.com'),
            ],
        ],
    ],
    'defaults' => [
        'migrations' => true,
        'metrics' => true,
        'register_model_bindings' => true,
        'models' => [
            'namespace' => 'App\\Model',
        ],
    ],
];
