@php($placement = $placement ?? 'fixed')
@php($iconOnly = $iconOnly ?? false)
<button type="button" data-theme-toggle aria-label="Switch to dark mode" title="Switch color theme" class="{{ $iconOnly ? 'inline-flex h-10 w-10 items-center justify-center p-0' : ($placement === 'inline' ? 'inline-flex' : 'fixed bottom-5 right-5 z-50 inline-flex') . ' gap-2 px-3 py-2 text-xs' }} rounded-full border border-slate-300 bg-white/95 font-bold text-slate-700 shadow-sm backdrop-blur transition hover:border-blue-400 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-700 dark:border-slate-700 dark:bg-slate-900/95 dark:text-slate-100 dark:shadow-black/30 dark:hover:border-blue-400 dark:hover:text-blue-300">
    <svg data-theme-moon aria-hidden="true" class="h-4 w-4 dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.5 15.5A8.5 8.5 0 0 1 8.5 3.5 8.5 8.5 0 1 0 20.5 15.5Z"/></svg>
    <svg data-theme-sun aria-hidden="true" class="hidden h-4 w-4 dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="4"/><path stroke-linecap="round" d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"/></svg>
    <span data-theme-label class="{{ $iconOnly ? 'sr-only' : 'hidden sm:inline' }}">Dark mode</span>
</button>
@once
    <script>
        (() => {
            const themeKey = 'emergency-sys-theme';
            const controls = document.querySelectorAll('[data-theme-toggle]');
            const applyTheme = (theme) => {
                const isDark = theme === 'dark';
                document.documentElement.classList.toggle('dark', isDark);
                controls.forEach((control) => {
                    const label = control.querySelector('[data-theme-label]');
                    if (label) label.textContent = isDark ? 'Light mode' : 'Dark mode';
                    control.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
                });
            };

            let savedTheme = null;
            try {
                savedTheme = localStorage.getItem(themeKey);
            } catch {}

            const initialTheme = savedTheme === 'dark' || savedTheme === 'light'
                ? savedTheme
                : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            applyTheme(initialTheme);

            controls.forEach((control) => control.addEventListener('click', () => {
                const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
                applyTheme(nextTheme);
                try {
                    localStorage.setItem(themeKey, nextTheme);
                } catch {}
            }));
        })();
    </script>
@endonce
