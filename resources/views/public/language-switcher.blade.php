<form method="POST" action="{{ route('locale.update') }}" class="shrink-0">
    @csrf
    @php($locales = $availableLocales ?? app(App\Support\I18n\LocaleCatalog::class)->publicLocales())
    @if($compact ?? false)
        @php($shortCodes = ['en' => 'EN', 'fil' => 'PH', 'hil' => 'HI', 'krb' => 'AK'])
        <details class="public-locale-menu">
            <summary class="locale-trigger" aria-label="{{ __('Change language') }}" title="{{ __('Change language') }}">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M12 3a15 15 0 0 1 0 18m0-18a15 15 0 0 0 0 18"/></svg>
            </summary>
            <div class="locale-options" role="group" aria-label="{{ __('Change language') }}">
                @foreach ($locales as $code => $name)
                    <button type="submit" name="locale" value="{{ $code }}" aria-pressed="{{ app()->getLocale() === $code ? 'true' : 'false' }}" title="{{ $name }}">{{ $shortCodes[$code] ?? strtoupper($code) }}</button>
                @endforeach
            </div>
        </details>
    @else
    <label for="{{ $id ?? 'public-locale' }}" class="sr-only">{{ __('Language') }}</label>
    <select id="{{ $id ?? 'public-locale' }}" name="locale" onchange="this.form.requestSubmit()" aria-label="{{ __('Language') }}" title="Choose the language for public pages." class="max-w-[9rem] rounded-full border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm outline-none transition focus:border-blue-700 focus:ring-2 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/60 sm:max-w-none sm:text-sm">
        @foreach ($locales as $code => $name)
            <option value="{{ $code }}" @selected(app()->getLocale() === $code)>{{ $name }}</option>
        @endforeach
    </select>
    @endif
</form>
