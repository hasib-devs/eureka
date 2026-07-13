<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role_id == 2) {

            if (Auth::user()->is_approved == false) {

                if (auth()->user()->shop_info->name == 'null_wait') {
                    return $next($request);
                } else {
                    Auth::logout();

                    notify()->error('Your seller account is pending, admin check your information', 'warning');

                    return back();

                }

            } elseif (Auth::user()->status == false) {
                Auth::logout();
                notify()->warning('Your account is blocked, please contact admin');

                return back();
            } else {
                return $next($request);
            }

        } else {
            return Redirect()->route('login');
        }
    }
}
