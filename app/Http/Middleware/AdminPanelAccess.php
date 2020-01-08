<?php

namespace App\Http\Middleware;

use Closure;

class AdminPanelAccess
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
        if (!is(['admin', 'volunteer', 'store', 'translator', 'friend card'])) {

            // Friend Card users
            if (backpack_user() && backpack_user()->friend_card_modality()->first()) {
                session(['login' => true]);
                return redirect('/friends');
            }

            abort(401);
        }

        return $next($request);
    }
}
