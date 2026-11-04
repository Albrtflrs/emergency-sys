<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
    <script>document.documentElement.classList.toggle('dark', localStorage.getItem('theme') ? localStorage.getItem('theme') === 'dark' : matchMedia('(prefers-color-scheme: dark)').matches);</script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased transition-colors duration-200 dark:bg-[#111315] dark:text-slate-100" style="background-image:url('{{ asset('assets/lgu kalibo.jpg') }}');background-size:cover;background-position:center;background-attachment:fixed">
    <div class="app-photo-canvas min-h-screen">
    <div class="mx-auto max-w-7xl px-6 py-8">
        <header class="mb-8 flex flex-col gap-4 rounded-[2rem] border border-slate-200 bg-white p-6 text-slate-900 shadow-sm md:flex-row md:items-center md:justify-between dark:border-slate-800 dark:bg-slate-900 dark:text-white">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.25em] text-blue-800 dark:text-blue-300">Admin panel</p>
                <h1 class="mt-2 text-3xl font-black tracking-tight">Operations dashboard</h1>
            </div>
            <div class="flex items-center gap-3">
                @include('components.theme-toggle', ['placement' => 'inline'])
                <a href="{{ route('admin.dashboard.queue') }}" class="rounded-full border border-slate-300 bg-slate-50 px-4 py-2 text-sm font-bold text-slate-700 hover:border-blue-700 hover:text-blue-800 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">Queue</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="rounded-full bg-blue-800 px-4 py-2 text-sm font-black text-white hover:bg-blue-900 dark:bg-blue-400 dark:text-slate-950 dark:hover:bg-blue-300">Logout</button>
                </form>
            </div>
        </header>

        <section class="grid gap-5 md:grid-cols-4">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Total reports</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-slate-900 dark:text-white">{{ $summary['total'] }}</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Open</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-amber-700 dark:text-amber-300">{{ $summary['open'] }}</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Resolved</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-emerald-700 dark:text-emerald-300">{{ $summary['resolved'] }}</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Critical</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-blue-800 dark:text-blue-300">{{ $summary['critical'] }}</p>
            </div>
        </section>

        <section class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl font-black tracking-tight text-slate-900 dark:text-white">Recent incidents</h2>
                <span class="text-sm text-slate-500 dark:text-slate-400">Latest 12 entries</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-700">
                    <thead class="bg-slate-50 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Incident</th>
                            <th class="px-4 py-3 font-semibold">Location</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Priority</th>
                            <th class="px-4 py-3 font-semibold">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse ($reports as $report)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/70">
                                <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-100">{{ $report->category?->nameFor(app()->getLocale(), 'en') ?? 'Incident' }}<span class="mt-1 block font-mono text-xs text-slate-500 dark:text-slate-400">{{ $report->reference_code }}</span></td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $report->location_text ?: 'Not provided' }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ ucfirst($report->status ?? 'open') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-900 dark:bg-amber-500/15 dark:text-amber-300">{{ ucfirst($report->priority) }}</span>
                                </td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $report->created_at?->format('M d, Y H:i') ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No incidents recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    </div>
</body>
</html>
