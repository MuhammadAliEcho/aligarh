<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenancy()->tenant;

        if ($tenant && $tenant->active == 1 && $request->routeIs('tenant.inactive')) {
            return redirect()->route('login');
        }

        if ($request->routeIs('tenant.inactive')) {
            return $next($request);
        }

        if (!$tenant) {
            return $this->denyAccess($request, 'No tenant found.');
        }

        $isActive = isset($tenant->active) && ($tenant->active == 1);

        if (!$isActive) {
            return $this->denyAccess($request, 'Tenant is inactive.');
        }

        return $next($request);
    }

    protected function denyAccess(Request $request, string $message): Response
    {
        Auth::logout();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $message,
                'status' => 'error',
                'code' => 403,
            ], 403);
        }

        return redirect()->route('tenant.inactive');
    }
}
