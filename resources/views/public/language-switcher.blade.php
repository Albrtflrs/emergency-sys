<form method="POST" action="{{ route('locale.update') }}" class="shrink-0">
    @csrf
    @php($locales = $availableLocales ?? app(App\Support\I18n\LocaleCatalog::class)->publicLocales())
    <label for="{{ $id ?? 'public-locale' }}" class="sr-only">{{ __('Language') }}</label>
    <select id="{{ $id ?? 'public-locale' }}" name="locale" onchange="this.form.requestSubmit()" aria-label="{{ __('Language') }}" title="Choose the language for public pages." class="max-w-[9rem] rounded-full border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm outline-none transition focus:border-blue-700 focus:ring-2 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/60 sm:max-w-none sm:text-sm">
        @foreach ($locales as $code => $name)
            <option value="{{ $code }}" @selected(app()->getLocale() === $code)>{{ $name }}</option>
        @endforeach
    </select>
</form>
