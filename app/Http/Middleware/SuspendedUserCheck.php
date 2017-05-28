<?php

namespace App\Http\Middleware;

use Closure;

class SuspendedUserCheck
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
        if (Auth::user()->isSuspended())
        {
            return response()->json([
                'error'   => 'forbidden_request',
                'message' => 'User has been suspended until '. Auth::user()->suspended_until,
            ], 403);
        }

        return $next($request);
    }
}
