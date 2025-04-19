<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AppLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale =
            // Request header
            $request->headers->get('locale') ??

            // User data
            $request->user()->data->locale ??

            // HTTP header
            $this->getLocaleFromHttpHeader($request);

        if ($locale) {
            App::setLocale($locale);
        }

        return $next($request);
    }

    /**
     * Get the locale from the
     */
    private function getLocaleFromHttpHeader(Request $request): ?string
    {
        if (Session::has('locale')) {
            return Session::get('locale');
        }

        $appLocales = config('app.locales');
        $requestLocales = preg_split('/,|;/', $request->server('HTTP_ACCEPT_LANGUAGE'));

        foreach ($requestLocales as $locale) {
            if (in_array($locale, $appLocales)) {
                Session::put('locale', $locale);

                return $locale;
            }
        }

        return null;
    }
}
