<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyAdminAccess
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
        if ( !Auth::user()->hasRole('admin'))
        {
            return response()->json([
                'error'   => 'forbidden_request',
                'message' => 'User cannot access this item.',
            ], 403);
        }

        return $next($request);
    }
}
