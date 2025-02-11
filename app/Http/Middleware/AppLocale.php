<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class AppLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = $request->headers->get('locale') ?? config('app.locale');
        App::setLocale($locale);

        return $next($request);
    }
}
