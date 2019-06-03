<?php

namespace App\Http\Middleware;

use Closure;

class RapidAPIValidation
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
        $rapidAPI = $_SERVER['RAPID_API_SECRET'] ?? null;

        if (!$rapidAPI || $rapidAPI !== config('app.rapid_api_secret')) {
            return \Response::json([
                'error' => 'If you wish to use this API go to https://rapidapi.com/promatik/api/animais-de-rua',
            ]);
        }

        return $next($request);
    }
}
