<?php

namespace App\Support\I18n;

use Illuminate\Support\Facades\File;

class LocaleCatalog
{
    /** @return array<string, string> Locale code => display name */
    public function all(): array
    {
        $locales = [];

        foreach (File::files(lang_path()) as $file) {
            if ($file->getExtension() !== 'json') {
                continue;
            }

            $translations = json_decode(File::get($file->getPathname()), true);
            $locale = $file->getFilenameWithoutExtension();
            $name = is_array($translations) ? ($translations['__language_name'] ?? null) : null;

            if (is_string($name) && $name !== '') {
                $locales[$locale] = $name;
            }
        }

        asort($locales, SORT_NATURAL | SORT_FLAG_CASE);

        return $locales;
    }

    public function supports(string $locale): bool
    {
        return array_key_exists($locale, $this->all());
    }

    /** @return array<string, string> Locale code => name exposed in the public UI */
    public function publicLocales(): array
    {
        return $this->all();
    }

    public function supportsPublic(string $locale): bool
    {
        return array_key_exists($locale, $this->publicLocales());
    }
}
