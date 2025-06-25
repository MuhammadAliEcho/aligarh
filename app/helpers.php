<?php

use Illuminate\Support\Facades\Route;

//for blade li active class route Active 
if (!function_exists('isActiveRoute')) {
    function isActiveRoute($routes, $output = 'active')
    {
        $current = Route::currentRouteName();

        if (is_array($routes)) {
            return in_array($current, $routes) ? $output : '';
        }

        if (str_ends_with($routes, '*')) {
            return str_starts_with($current, rtrim($routes, '*')) ? $output : '';
        }

        return $current === $routes ? $output : '';
    }
}

if (!function_exists('canAny')) {
    function canAny($permissions) {
        return collect($permissions)->some(fn($permission) => auth()->user()->can($permission));
    }
}

if (!function_exists('canAll')) {
    function canAll($permissions) {
        return collect($permissions)->every(fn($permission) => auth()->user()->can($permission));
    }
}
