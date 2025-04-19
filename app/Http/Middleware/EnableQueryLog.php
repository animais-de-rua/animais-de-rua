<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class EnableQueryLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (debugMode()) {
            DB::enableQueryLog();
        }

        return $next($request);
    }
}
