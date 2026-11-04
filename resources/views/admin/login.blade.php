<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    @vite('resources/css/app.css')
    <script>document.documentElement.classList.toggle('dark', localStorage.getItem('theme') ? localStorage.getItem('theme') === 'dark' : matchMedia('(prefers-color-scheme: dark)').matches);</script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased transition-colors duration-200 dark:bg-[#111315] dark:text-slate-100" style="background-image:url('{{ asset('assets/lgu kalibo.jpg') }}');background-size:cover;background-position:center;background-attachment:fixed">
    <div class="app-photo-canvas flex min-h-screen items-center justify-center px-4 py-10">
        <div class="grid w-full max-w-5xl overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-2xl shadow-slate-900/10 md:grid-cols-2 dark:border-slate-700 dark:bg-slate-900">
            <div class="bg-slate-100 p-8 text-slate-800 md:p-12 dark:bg-slate-800 dark:text-slate-100">
                <div class="mb-7 flex h-16 w-16 items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-900">
                    <img src="{{ asset('assets/kalibo-logo.png') }}" alt="Kalibo logo" class="h-12 w-12 object-contain" />
                </div>
                <p class="text-xs font-black uppercase tracking-[0.25em] text-blue-800 dark:text-blue-300">Administrator Portal</p>
                <h1 class="mt-6 text-3xl font-black tracking-tight text-slate-950 dark:text-white">Emergency operations access</h1>
                <p class="mt-4 leading-7 text-slate-600 dark:text-slate-300">This area is restricted to authorized administrators. Please sign in with your administrator account.</p>
                <div class="mt-8 space-y-4 text-sm text-slate-700 dark:text-slate-200">
                    <div class="rounded-2xl bg-white/80 p-4 dark:bg-slate-900/60">Incident workflow oversight</div>
                    <div class="rounded-2xl bg-white/80 p-4 dark:bg-slate-900/60">System health and audit visibility</div>
                    <div class="rounded-2xl bg-white/80 p-4 dark:bg-slate-900/60">Escalation and reporting controls</div>
                </div>
            </div>
            <div class="bg-white p-8 md:p-12 dark:bg-slate-900">
                <div class="mb-8 flex items-center justify-between">
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white">Admin Sign In</h2>
                    <div class="flex items-center gap-3">@include('components.theme-toggle', ['placement' => 'inline'])<a href="{{ route('landing') }}" class="text-sm font-bold text-blue-800 dark:text-blue-300">Back home</a></div>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-amber-300 bg-amber-50 p-3 text-sm text-amber-900 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="name@example.com" title="Enter your administrator account email address." required autofocus class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-800/15 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">Password</label>
                        <input id="password" name="password" type="password" placeholder="Enter your password" title="Enter the password for your administrator account." required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-800/15 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                    </div>

                    <button type="submit" class="w-full rounded-full bg-blue-800 px-4 py-3 text-sm font-black text-white transition hover:bg-blue-900 dark:bg-blue-400 dark:text-slate-950 dark:hover:bg-blue-300">Sign In</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
