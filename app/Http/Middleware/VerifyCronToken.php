<?php

namespace App\Http\Middleware;

use Closure;

class VerifyCronToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( ! $request->has('cron_token') || $request->cron_token != config('vars.cron_token')) {
            return response()->json(['status' => 'error', 'message' => 'Petición invalida'], 400);
        }

        return $next($request);
    }
}
