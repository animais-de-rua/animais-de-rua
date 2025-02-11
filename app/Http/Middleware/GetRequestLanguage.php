<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class GetRequestLanguage
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } else {
            /** @var string */
            $requestLang = $request->server('HTTP_ACCEPT_LANGUAGE');
            $availableLangs = array_keys(config('backpack.crud.locales'));

            $userLangs = preg_split('/,|;/', $requestLang);
            if (! $userLangs) {
                return null;
            }

            foreach ($userLangs as $lang) {
                if (in_array($lang, $availableLangs)) {
                    Session::put('locale', $lang);
                    App::setLocale($lang);
                    break;
                }
            }
        }

        return $next($request);
    }
}
