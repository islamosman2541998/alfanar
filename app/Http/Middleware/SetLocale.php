<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/** Site locale comes from the first URL segment: /ar/... or /en/... */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale') ?? config('app.locale');

        if (! array_key_exists($locale, locales())) {
            abort(404);
        }

        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);
        $request->route()?->forgetParameter('locale');

        return $next($request);
    }
}
