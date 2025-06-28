<?php
use Illuminate\Support\Facades\Route;

if (!function_exists('isActiveRoute')) {
    function isActiveRoute($routes, $output = 'active')
    {
        $current = Route::currentRouteName();

        if (is_array($routes)) {
            foreach ($routes as $route) {
                if (str_ends_with($route, '*')) {
                    if (str_starts_with($current, rtrim($route, '*'))) {
                        return $output;
                    }
                } else {
                    if ($current === $route) {
                        return $output;
                    }
                }
            }
            return '';
        }

        if (str_ends_with($routes, '*')) {
            return str_starts_with($current, rtrim($routes, '*')) ? $output : '';
        }

        return $current === $routes ? $output : '';
    }
}
