<?php

namespace App\Http\Middleware;

use App\Support\I18n\LocaleCatalog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetPublicLocale
{
    public function __construct(private readonly LocaleCatalog $locales) {}

    public function handle(Request $request, Closure $next): Response
    {
        $locale = (string) $request->session()->get('locale', config('app.locale'));

        app()->setLocale($this->locales->supportsPublic($locale) ? $locale : config('app.fallback_locale'));

        return $next($request);
    }
}
