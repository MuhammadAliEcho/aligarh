<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if($request->user()->active == false){
            if ($request->ajax() || $request->wantsJson()) {
                $request->user()->token()->delete();
                return response()->json(['error'=>'unauthorized', 'msg' => 'You must be Active to login'], 401);
            } else {
                Auth::logout();
                return redirect('login')
                ->withErrors([
                    'invalid' => 'You must be Active to login',
                    ]);
            }
        }

        return $next($request);
    }
}
