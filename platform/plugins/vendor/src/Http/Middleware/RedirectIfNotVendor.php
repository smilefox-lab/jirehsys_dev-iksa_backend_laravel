<?php

namespace Botble\Vendor\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotVendor
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
        if (!Auth::guard($guard)->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }

            return redirect()->guest(route('public.vendor.login'));
        }

        return $next($request);
    }
}
