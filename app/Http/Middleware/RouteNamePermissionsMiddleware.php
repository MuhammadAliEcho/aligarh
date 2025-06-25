<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;


class RouteNamePermissionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
			throw UnauthorizedException::notLoggedIn();
		}

        $routeName = $request->route()->getName();
        $permission = Permission::where('name', $routeName)->first();
        
        if (!$permission || Auth::user()->hasPermissionTo($permission)) {
            return $next($request);
        }

        return response()->view('errors.403', [], 403);
    }
}