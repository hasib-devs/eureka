<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role_id == 3) {

            if (Auth::user()->status == false) {
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
