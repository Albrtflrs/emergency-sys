@php($placement = $placement ?? 'fixed')
<button type="button" data-theme-toggle aria-label="Switch to dark mode" class="{{ $placement === 'inline' ? 'inline-flex' : 'fixed bottom-5 right-5 z-50 inline-flex' }} items-center gap-2 rounded-full border border-slate-300 bg-white/95 px-3 py-2 text-xs font-bold text-slate-700 shadow-sm backdrop-blur transition hover:border-blue-400 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-700 dark:border-slate-700 dark:bg-slate-900/95 dark:text-slate-100 dark:shadow-black/30 dark:hover:border-blue-400 dark:hover:text-blue-300">
    <svg data-theme-moon aria-hidden="true" class="h-4 w-4 dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.5 15.5A8.5 8.5 0 0 1 8.5 3.5 8.5 8.5 0 1 0 20.5 15.5Z"/></svg>
    <svg data-theme-sun aria-hidden="true" class="hidden h-4 w-4 dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="4"/><path stroke-linecap="round" d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"/></svg>
    <span data-theme-label class="hidden sm:inline">Dark mode</span>
</button>
<script>
    (() => {
        const root = document.documentElement;
        const button = document.querySelector('[data-theme-toggle]');
        const label = button?.querySelector('[data-theme-label]');
        if (!button || button.dataset.ready) return;
        button.dataset.ready = 'true';
        const sync = () => {
            const dark = root.classList.contains('dark');
            button.setAttribute('aria-label', dark ? 'Switch to light mode' : 'Switch to dark mode');
            button.setAttribute('aria-pressed', dark ? 'true' : 'false');
            if (label) label.textContent = dark ? 'Light mode' : 'Dark mode';
        };
        sync();
        button.addEventListener('click', () => {
            const dark = !root.classList.contains('dark');
            root.classList.toggle('dark', dark);
            root.style.colorScheme = dark ? 'dark' : 'light';
            localStorage.setItem('theme', dark ? 'dark' : 'light');
            sync();
        });
    })();
</script>
