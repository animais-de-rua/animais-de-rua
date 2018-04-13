<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class GetRequestLanguage
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
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } else {
            $availableLangs = ['pt', 'en'];
            $userLangs = preg_split('/,|;/', $request->server('HTTP_ACCEPT_LANGUAGE'));

            foreach ($availableLangs as $lang) {
                if(in_array($lang, $userLangs)) {
                    Session::push('locale', $lang);
                    App::setLocale($lang);
                    break;
                }
            }
        }

        return $next($request);
    }
}
