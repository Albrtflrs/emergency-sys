<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Incident Queue</title>
    @vite('resources/css/app.css')
</head>
<body class="operations-surface min-h-screen bg-slate-50 text-slate-800 antialiased dark:bg-[#18181b] dark:text-slate-100" style="background-image:url('{{ asset('assets/lgu kalibo.jpg') }}');background-size:cover;background-position:center;background-attachment:fixed">
    <div class="operations-shell">
        @include('admin.sidebar')
        <main class="operations-main">
            <header class="operations-page-head"><div><p class="operations-eyebrow">Operations</p><h1>Incident queue</h1><p>Update report status and priority, and message the reporter.</p></div><div class="operations-head-actions">@include('components.theme-toggle', ['placement' => 'inline', 'iconOnly' => true])</div></header>

            @if (session('status'))
                <p class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-900 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200" role="status">{{ session('status') }}</p>
            @endif
            @if ($errors->any())
                <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900 dark:border-rose-800 dark:bg-rose-950/40 dark:text-rose-200" role="alert">
                    @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900 sm:p-6">
                <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-900 dark:text-white">Reports</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Select a status to filter the queue.</p>
                    </div>
                    <form method="GET" action="{{ route('admin.dashboard.queue') }}" class="flex items-center gap-2">
                        <label for="status-filter" class="sr-only">Filter by status</label>
                        <select id="status-filter" name="status" title="Filter reports by status" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800">
                            <option value="">All statuses</option>
                            @foreach (['submitted' => 'Submitted', 'in_progress' => 'In progress', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $value => $label)
                                <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="rounded-full bg-blue-800 px-4 py-2 text-sm font-bold text-white hover:bg-blue-900 dark:bg-blue-400 dark:text-slate-950">Filter</button>
                    </form>
                </div>

                <div class="space-y-4">
                    @forelse ($reports as $report)
                        <article class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700 sm:p-5">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-mono text-lg font-black text-slate-900 dark:text-white">{{ $report->reference_code }}</span>
                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ ucfirst(str_replace('_', ' ', $report->status)) }}</span>
                                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-900 dark:bg-amber-500/15 dark:text-amber-300">{{ ucfirst($report->priority) }} priority</span>
                                    </div>
                                    <p class="mt-2 font-bold text-slate-800 dark:text-slate-100">{{ $report->category?->nameFor(app()->getLocale(), 'en') ?? 'Incident report' }}</p>
                                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $report->location_text }}</p>
                                    <p class="mt-3 whitespace-pre-wrap text-sm leading-6 text-slate-700 dark:text-slate-200">{{ $report->description }}</p>
                                    <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">Submitted {{ $report->submitted_at?->format('M j, Y g:i A') ?? '—' }}</p>
                                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Reporter: <span class="font-semibold">{{ $report->reporter_name }}</span> · <a class="font-semibold text-blue-800 hover:underline dark:text-blue-300" href="tel:{{ $report->contact_number }}">{{ $report->contact_number }}</a></p>
                                    @if ($report->photo_path)
                                        <a href="{{ route('staff.report.photo', $report) }}" target="_blank" rel="noreferrer" class="mt-3 inline-flex items-center gap-2 text-sm font-bold text-blue-800 hover:underline dark:text-blue-300">View submitted photo</a>
                                    @endif
                                </div>

                                <form method="POST" action="{{ route('staff.report.triage', $report) }}" class="flex flex-wrap items-center gap-2 rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/70">
                                    @csrf
                                    <input type="hidden" name="return_to" value="admin">
                                    <label class="sr-only" for="status-{{ $report->id }}">Report status</label>
                                    <select id="status-{{ $report->id }}" name="status" title="Update the report status" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
                                        @foreach (['submitted' => 'Submitted', 'in_progress' => 'In progress', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $value => $label)
                                            <option value="{{ $value }}" @selected($report->status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <label class="sr-only" for="priority-{{ $report->id }}">Response priority</label>
                                    <select id="priority-{{ $report->id }}" name="priority" title="Update the response priority" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
                                        @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'critical' => 'Critical'] as $value => $label)
                                            <option value="{{ $value }}" @selected($report->priority === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded-full bg-blue-800 px-4 py-2 text-sm font-bold text-white hover:bg-blue-900 dark:bg-blue-400 dark:text-slate-950">Save update</button>
                                </form>
                            </div>

                            <details class="mt-4 border-t border-slate-200 pt-4 dark:border-slate-700" data-admin-thread data-index-url="{{ route('staff.report.messages', $report) }}" data-store-url="{{ route('staff.report.messages.store', $report) }}" data-closed="{{ $report->thread_closed ? 'true' : 'false' }}">
                                <summary class="cursor-pointer text-sm font-bold text-blue-800 dark:text-blue-300">Message reporter</summary>
                                <ol data-message-list class="mt-3 max-h-64 space-y-2 overflow-y-auto" aria-live="polite"></ol>
                                <p data-message-empty class="mt-2 text-sm text-slate-500 dark:text-slate-400">No messages yet.</p>
                                <p data-message-alert class="mt-2 hidden rounded-xl bg-amber-50 px-3 py-2 text-sm text-amber-900 dark:bg-amber-500/10 dark:text-amber-200" role="status"></p>
                                <form data-message-form class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-end">
                                    @csrf
                                    <label class="flex-1 text-sm font-semibold text-slate-700 dark:text-slate-200">Message
                                        <textarea name="body" rows="2" maxlength="500" placeholder="Write an update or question for the reporter" title="Send a message of up to 500 characters. Closing the thread prevents further messages." required class="mt-1 w-full rounded-2xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-800/15 dark:border-slate-600 dark:bg-slate-800 dark:text-white"></textarea>
                                    </label>
                                    <label class="flex items-center gap-2 pb-2 text-xs font-semibold text-slate-600 dark:text-slate-300"><input type="checkbox" name="close_thread" value="1" class="rounded border-slate-300 text-blue-800 focus:ring-blue-800"> Close thread</label>
                                    <button type="submit" class="rounded-full bg-blue-800 px-4 py-2 text-sm font-bold text-white hover:bg-blue-900 dark:bg-blue-400 dark:text-slate-950">Send message</button>
                                </form>
                            </details>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">No reports match this filter.</div>
                    @endforelse
                </div>
            </section>
        </main>
    </div>


</body>
</html>
