<?php

namespace App\Http\Middleware;

use Closure;

class SetEn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        app()->setLocale("en");
        return $next($request);
    }
}
