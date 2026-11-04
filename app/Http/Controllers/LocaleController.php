<?php

namespace App\Http\Controllers;

use App\Support\I18n\LocaleCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function update(Request $request, LocaleCatalog $locales): RedirectResponse
    {
        $data = $request->validate([
            'locale' => ['required', 'string', 'in:' . implode(',', array_keys($locales->publicLocales()))],
        ]);

        $request->session()->put('locale', $data['locale']);

        $previous = (string) $request->headers->get('referer', '');
        $previousHost = parse_url($previous, PHP_URL_HOST);
        $previousPath = parse_url($previous, PHP_URL_PATH);

        if (
            $previousHost === $request->getHost()
            && is_string($previousPath)
            && str_starts_with($previousPath, '/')
            && ! str_starts_with($previousPath, '//')
            && ! str_starts_with($previousPath, '/\\')
        ) {
            $query = parse_url($previous, PHP_URL_QUERY);

            return redirect()->to($previousPath . ($query ? '?' . $query : ''));
        }

        return redirect()->route('landing');
    }
}
