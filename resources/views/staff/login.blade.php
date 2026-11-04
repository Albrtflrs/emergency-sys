<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Staff login') }}</title>
    @vite('resources/css/app.css')
    <script>document.documentElement.classList.toggle('dark', localStorage.getItem('theme') ? localStorage.getItem('theme') === 'dark' : matchMedia('(prefers-color-scheme: dark)').matches);</script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased transition-colors duration-200 dark:bg-[#111315] dark:text-slate-100" style="background-image:url('{{ asset('assets/lgu kalibo.jpg') }}');background-size:cover;background-position:center;background-attachment:fixed">
    <div class="app-photo-canvas flex min-h-screen items-center justify-center px-4 py-10">
        <main class="w-full max-w-md rounded-[28px] border border-slate-200 bg-white p-7 shadow-xl shadow-slate-900/5 sm:p-9 dark:border-slate-700 dark:bg-slate-900">
            <a href="{{ route('landing') }}" class="text-xs font-black uppercase tracking-[0.24em] text-blue-800 dark:text-blue-300">MDRRMO · {{ __('Staff portal') }}</a>
            <div class="mb-3 flex justify-end">@include('components.theme-toggle', ['placement' => 'inline'])</div>
            <div class="mb-5 flex justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-800">
                    <img src="{{ asset('assets/kalibo-logo.png') }}" alt="Kalibo logo" class="h-12 w-12 object-contain" />
                </div>
            </div>
            <h1 class="mt-4 text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ __('Staff sign in') }}</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ __('Sign in to review and update emergency reports.') }}</p>
            @if ($errors->any())
                <div class="mt-5 rounded-2xl border border-amber-300 bg-amber-50 p-3 text-sm text-amber-900 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('staff.login') }}" class="mt-7 space-y-5">
                @csrf
                <div><label for="email" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">{{ __('Email') }}</label><input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" title="Enter your staff account email address." required autofocus class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-800 focus:ring-2 focus:ring-blue-800/15 dark:border-slate-600 dark:bg-slate-800 dark:text-white"></div>
                <div><label for="password" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">{{ __('Password') }}</label><input id="password" type="password" name="password" placeholder="Enter your password" title="Enter the password for your staff account." required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-800 focus:ring-2 focus:ring-blue-800/15 dark:border-slate-600 dark:bg-slate-800 dark:text-white"></div>
                <button type="submit" class="w-full rounded-full bg-blue-800 px-5 py-3 font-black text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-800/20 dark:bg-blue-400 dark:text-slate-950 dark:hover:bg-blue-300">{{ __('Sign in') }}</button>
            </form>
            <a href="{{ route('landing') }}" class="mt-6 inline-block text-sm font-bold text-blue-800 hover:underline dark:text-blue-300">← {{ __('Back to home') }}</a>
        </main>
    </div>
</body>
</html>
