<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Emergency Response Portal') }}</title>
    @vite('resources/css/app.css')
    <script>document.documentElement.classList.toggle('dark', localStorage.getItem('theme') ? localStorage.getItem('theme') === 'dark' : matchMedia('(prefers-color-scheme: dark)').matches);</script>
    
    <style>
        /* Toast animation classes */
        .landing-theme-overlay {
            background-image: linear-gradient(rgba(248, 250, 252, 0.68), rgba(226, 232, 240, 0.78));
            transition: background-image 200ms ease;
        }

        .dark .landing-theme-overlay {
            background-image: linear-gradient(rgba(8, 12, 20, 0.68), rgba(8, 12, 20, 0.8));
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased transition-colors duration-200 dark:bg-[#111315] dark:text-slate-100" style="background-image: url('{{ asset('assets/lgu kalibo.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="landing-theme-overlay min-h-screen">
        <header class="sticky top-0 z-50 border-b border-slate-200/90 bg-white/95 shadow-sm backdrop-blur-xl dark:border-slate-700 dark:bg-slate-950/95">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3 rounded-full border border-slate-200 bg-white px-2.5 py-2 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-full border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <img src="{{ asset('assets/kalibo-logo.png') }}" alt="Kalibo logo" class="h-10 w-10 object-contain drop-shadow-[0_2px_3px_rgba(255,255,255,0.7)]" />
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.32em] text-blue-800 dark:text-blue-300">MDRRMO</p>
                        <h1 class="text-sm font-black text-slate-900 sm:text-base dark:text-slate-100">{{ __('Emergency Response Portal') }}</h1>
                    </div>
                </div>

                <nav class="hidden items-center gap-2 md:flex">
                    <a href="{{ route('track') }}" class="rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-400 hover:text-blue-800 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:hover:border-blue-400 dark:hover:text-blue-300">{{ __('Track your report') }}</a>
                    <a href="{{ route('public.report') }}" class="rounded-full bg-blue-800 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900 dark:bg-blue-400 dark:text-slate-950 dark:hover:bg-blue-300">{{ __('Report an incident') }}</a>
                    @include('public.language-switcher', ['availableLocales' => app(App\Support\I18n\LocaleCatalog::class)->all(), 'id' => 'public-locale-desktop'])
                </nav>

                <div class="flex items-center gap-2">
                    @include('components.theme-toggle', ['placement' => 'inline'])
                    <div class="flex items-center gap-2 md:hidden">
                        <a href="{{ route('track') }}" class="rounded-full border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ __('Track') }}</a>
                        <a href="{{ route('public.report') }}" class="rounded-full bg-slate-900 px-3 py-2 text-xs font-bold text-white dark:bg-white dark:text-slate-900">{{ __('Report') }}</a>
                    </div>
                </div>
            </div>
            <div class="mx-auto flex max-w-7xl justify-end px-4 pb-3 md:hidden sm:px-6">
                @include('public.language-switcher', ['availableLocales' => app(App\Support\I18n\LocaleCatalog::class)->all(), 'id' => 'public-locale-mobile'])
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-10">
            @include('public.hotline-notice')
            <section class="grid items-center gap-6 lg:grid-cols-[1.15fr_0.85fr] lg:gap-8">
                <div class="space-y-6 rounded-[2rem] bg-white/95 p-5 shadow-[0_20px_60px_rgba(15,23,42,0.18)] backdrop-blur-xl sm:p-7 lg:p-9 dark:bg-slate-950/95">
                            <span class="inline-flex items-center rounded-full border border-blue-100 bg-blue-50 px-4 py-1.5 text-[11px] font-black uppercase tracking-[0.24em] text-blue-800 dark:border-blue-900 dark:bg-blue-950/70 dark:text-blue-200">
                        {{ __('Public safety') }}
                    </span>

                    <div class="space-y-4">
                        <h2 class="max-w-xl text-4xl font-black leading-[1.05] tracking-[-0.045em] text-slate-950 sm:text-5xl lg:text-6xl xl:text-7xl dark:text-white">
                            {{ __('Report incidents quickly and stay informed.') }}
                        </h2>
                        <p class="max-w-xl text-base leading-7 text-slate-600 sm:text-lg sm:leading-8 dark:text-slate-300">
                            {{ __('Residents can submit a report and follow its status online.') }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                        <a href="{{ route('public.report') }}" class="inline-flex items-center justify-center rounded-full bg-blue-800 px-6 py-3 text-sm font-black text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-blue-900 dark:bg-blue-400 dark:text-slate-950 dark:hover:bg-blue-300">
                            {{ __('Report an incident') }}
                        </a>
                        <a href="{{ route('track') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-6 py-3 text-sm font-black text-slate-700 shadow-sm transition hover:border-blue-400 hover:text-blue-800 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300">
                            {{ __('Track your report') }}
                        </a>
                    </div>
                </div>

                <div class="rounded-[2rem] bg-white/95 p-5 shadow-[0_20px_60px_rgba(15,23,42,0.18)] backdrop-blur-xl sm:p-6 lg:p-7 dark:bg-slate-950/95">
                    <div class="mb-5 flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">How it works</p>
                            <h3 class="mt-2 text-xl font-black text-slate-900 dark:text-slate-100">Simple workflow</h3>
                        </div>
                        <div class="rounded-full bg-amber-100/90 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-amber-900 dark:bg-amber-500/15 dark:text-amber-300">Public</div>
                    </div>

                    <div class="space-y-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-800 text-sm font-black text-white">1</span>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Step 1</p>
                                    <p class="text-base font-black text-slate-900 dark:text-slate-100">Report</p>
                                </div>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">Submit the incident and provide the essentials for fast triage.</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-400 text-sm font-black text-slate-900">2</span>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Step 2</p>
                                    <p class="text-base font-black text-slate-900 dark:text-slate-100">Track</p>
                                </div>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">Follow updates using your reference code and tracking link.</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-900 text-sm font-black text-white dark:bg-slate-50 dark:text-slate-900">3</span>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Step 3</p>
                                    <p class="text-base font-black text-slate-900 dark:text-slate-100">Reviewed / Accomplished</p>
                                </div>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">Receive clear updates and final resolution once the case is reviewed.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-16 border-t border-slate-200 py-10 dark:border-slate-800">
                <div class="mb-8 max-w-2xl">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-blue-800 dark:text-blue-300">{{ __('Built for residents') }}</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl dark:text-white">{{ __('A clearer path from report to response') }}</h2>
                    <p class="mt-3 text-base leading-7 text-slate-600 dark:text-slate-300">{{ __('The system helps residents share useful details, understand what happens next, and stay connected to the response.') }}</p>
                </div>
                <div class="grid gap-x-10 gap-y-9 md:grid-cols-3">
                    <article class="group relative border-l-2 border-amber-400 pl-6 transition hover:translate-x-1">
                        <span class="absolute -left-[1.15rem] top-0 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-700 ring-4 ring-slate-50 dark:bg-[#111315] dark:text-amber-300 dark:ring-[#111315]"><svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/></svg></span>
                        <h3 class="text-xl font-black tracking-tight text-slate-900 dark:text-white">{{ __('Quick reporting') }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ __('Guided prompts help residents send the key details responders need, reducing back and forth during initial triage.') }}</p>
                    </article>
                    <article class="group relative border-l-2 border-blue-700 pl-6 transition hover:translate-x-1 dark:border-blue-400">
                        <span class="absolute -left-[1.15rem] top-0 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-800 ring-4 ring-slate-50 dark:bg-[#111315] dark:text-blue-300 dark:ring-[#111315]"><svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/><path stroke-linecap="round" d="M8 7h8M8 11h6"/></svg></span>
                        <h3 class="text-xl font-black tracking-tight text-slate-900 dark:text-white">{{ __('Track progress') }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ __('A single reference link keeps status changes and public updates together, so residents know their report has not disappeared.') }}</p>
                    </article>
                    <article class="group relative border-l-2 border-amber-400 pl-6 transition hover:translate-x-1">
                        <span class="absolute -left-[1.15rem] top-0 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-700 ring-4 ring-slate-50 dark:bg-[#111315] dark:text-amber-300 dark:ring-[#111315]"><svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4.5 8-11a8 8 0 1 0-16 0c0 6.5 8 11 8 11Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 11.5 11 13.5l4-4"/></svg></span>
                        <h3 class="text-xl font-black tracking-tight text-slate-900 dark:text-white">{{ __('Clear guidance') }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ __('Plain status updates explain whether a report is being reviewed, acted on, or resolved, helping residents decide what to do next.') }}</p>
                    </article>
            </section>
            <footer class="mt-10 flex flex-wrap items-center justify-center gap-3 border-t border-slate-300/70 py-6 text-sm font-bold text-slate-600 dark:border-slate-700 dark:text-slate-300">
                <a href="{{ route('staff.login') }}" class="rounded-full px-4 py-2 hover:bg-white/70 hover:text-blue-800 dark:hover:bg-slate-900 dark:hover:text-blue-300">{{ __('Staff login') }}</a>
                <a href="{{ route('admin.login') }}" class="rounded-full px-4 py-2 hover:bg-white/70 hover:text-blue-800 dark:hover:bg-slate-900 dark:hover:text-blue-300">{{ __('Admin login') }}</a>
            </footer>
        </main>
    </div>

</body>
</html>
