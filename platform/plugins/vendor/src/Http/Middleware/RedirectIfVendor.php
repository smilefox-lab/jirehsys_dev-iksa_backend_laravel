<?php

namespace Botble\Vendor\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfVendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'vendor')
    {
        if (Auth::guard($guard)->check()) {
            return redirect(route('public.vendor.dashboard'));
        }

        return $next($request);
    }
}
