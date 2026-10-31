<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Dashboard language is kept in the session and switched from the top bar. */
class SetAdminLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('admin_locale', config('app.locale'));

        app()->setLocale(array_key_exists($locale, locales()) ? $locale : config('app.locale'));

        return $next($request);
    }
}
