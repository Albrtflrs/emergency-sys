<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Report tracking') }}</title>
    @vite('resources/css/app.css')
    <script>document.documentElement.classList.toggle('dark', localStorage.getItem('theme') ? localStorage.getItem('theme') === 'dark' : matchMedia('(prefers-color-scheme: dark)').matches);</script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased transition-colors duration-200 dark:bg-[#111315] dark:text-slate-100" style="background-image:url('{{ asset('assets/lgu kalibo.jpg') }}');background-size:cover;background-position:center;background-attachment:fixed">
    <div class="app-photo-canvas min-h-screen px-4 py-10">
        <main class="mx-auto max-w-3xl">
            @include('public.hotline-notice')
            <div class="mb-5 flex items-center justify-between gap-3">
                @include('public.language-switcher', ['availableLocales' => app(App\Support\I18n\LocaleCatalog::class)->all()])
                @include('components.theme-toggle', ['placement' => 'inline'])
            </div>
            <a href="{{ route('landing') }}" class="text-xs font-black uppercase tracking-[0.24em] text-blue-800 dark:text-blue-300">MDRRMO · {{ __('Emergency Response Portal') }}</a>
            <header class="mb-6 mt-5 rounded-[2rem] bg-white/95 p-6 shadow-xl shadow-slate-900/10 backdrop-blur sm:p-8 dark:bg-slate-950/95 dark:shadow-black/30" data-status-widget data-reference="{{ $reference_code }}" data-token="{{ $tracking_token ?? '' }}">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-blue-800 dark:text-blue-300">{{ __('Report tracking') }}</p>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-950 dark:text-white">{{ __('Reference code') }}</h1>
                <p class="mt-2 font-mono text-lg font-semibold text-slate-600 dark:text-slate-300">{{ $reference_code }}</p>
                <div id="report-status" class="mt-5 inline-flex rounded-full bg-amber-100 px-4 py-1.5 text-sm font-black text-amber-900 dark:bg-amber-500/15 dark:text-amber-300">{{ __(ucfirst(str_replace('_', ' ', $status ?? 'submitted'))) }}</div>
                @if (!empty($has_photo))
                    <div class="mt-5"><p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('Attached photo') }}</p><a href="{{ route('track.photo', $tracking_token) }}" target="_blank" rel="noreferrer" class="inline-flex"><img src="{{ route('track.photo', $tracking_token) }}" alt="{{ __('Photo submitted with this report') }}" class="max-h-64 rounded-2xl object-contain shadow-sm"></a></div>
                @endif
            </header>
            <section class="grid gap-4 sm:grid-cols-2">
                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900"><h2 class="text-xs font-black uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('Submitted') }}</h2><p class="mt-3 font-bold text-slate-900 dark:text-white">{{ $submitted_at ? $submitted_at->format('F j, Y g:i A') : __('Unknown') }}</p></article>
                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900"><h2 class="text-xs font-black uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('Expiry countdown') }}</h2><p class="mt-3 font-bold text-slate-900 dark:text-white">{{ $expires_at ? __('Expires on :date', ['date' => \Illuminate\Support\Carbon::parse($expires_at)->format('F j, Y g:i A')]) : __('No expiry set.') }}</p>@if ($expires_at)<p data-expiry-countdown data-expires-at="{{ \Illuminate\Support\Carbon::parse($expires_at)->toISOString() }}" class="mt-2 text-sm font-semibold text-blue-800 dark:text-blue-300"></p>@endif</article>
            </section>
            <section class="mt-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <h2 class="text-xl font-black text-slate-900 dark:text-white">{{ __('Status timeline') }}</h2>
                <p id="status-refresh" class="mt-2 text-xs text-slate-500 dark:text-slate-400" aria-live="polite">{{ __('Status updates automatically.') }}</p>
                <ol data-status-timeline class="mt-5 space-y-4 border-l-2 border-blue-100 pl-5 dark:border-slate-700">
                    @foreach ($timeline as $entry)
                        <li class="relative"><span class="absolute -left-[1.65rem] top-1 h-3 w-3 rounded-full bg-amber-400 ring-4 ring-amber-100 dark:ring-amber-500/20"></span><p class="font-bold text-slate-900 dark:text-white">{{ __($entry['label']) }}</p><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $entry['timestamp'] ? \Illuminate\Support\Carbon::parse($entry['timestamp'])->format('F j, Y g:i A') : '' }}</p></li>
                    @endforeach
                </ol>
            </section>
            <section class="mt-4 grid gap-4 sm:grid-cols-2">
                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900"><h2 class="font-black text-slate-900 dark:text-white">{{ __('Public staff updates') }}</h2><p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">@if (count($public_updates) === 0) {{ __('No public updates yet.') }} @else {{ json_encode($public_updates) }} @endif</p></article>
                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900" data-message-panel data-token="{{ $tracking_token ?? '' }}" data-closed="{{ ($thread_closed ?? false) ? 'true' : 'false' }}">
                    <h2 class="font-black text-slate-900 dark:text-white">{{ __('Message thread') }}</h2>
                    <ol data-message-list class="mt-4 max-h-72 space-y-3 overflow-y-auto" aria-live="polite"></ol>
                    <p data-message-empty class="mt-3 text-sm text-slate-500 dark:text-slate-400">{{ __('No messages yet.') }}</p>
                    <p data-message-alert class="mt-3 hidden rounded-xl bg-amber-50 px-3 py-2 text-sm text-amber-900 dark:bg-amber-500/10 dark:text-amber-200" role="status"></p>
                    <form data-message-form class="mt-4 space-y-3">
                        <label for="reporter-message" class="block text-sm font-bold text-slate-700 dark:text-slate-200">{{ __('Send a message to staff') }}</label>
                        <textarea id="reporter-message" name="body" rows="3" maxlength="500" title="Send staff a follow-up or additional details about your report." required class="w-full rounded-2xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-800/15 dark:border-slate-600 dark:bg-slate-800 dark:text-white" placeholder="{{ __('Type your message') }}"></textarea>
                        <button type="submit" class="rounded-full bg-blue-800 px-4 py-2 text-sm font-bold text-white hover:bg-blue-900 dark:bg-blue-400 dark:text-slate-950 dark:hover:bg-blue-300">{{ __('Send message') }}</button>
                    </form>
                </article>
            </section>
            <a href="{{ route('landing') }}" class="mt-6 inline-flex rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 hover:border-blue-800 hover:text-blue-800 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">← {{ __('Back to home') }}</a>
        </main>
    </div>
    <script>
        (() => {
            const countdown = document.querySelector('[data-expiry-countdown]');
            if (countdown) {
                const expiresAt = new Date(countdown.dataset.expiresAt).getTime();
                const updateCountdown = () => {
                    const seconds = Math.max(0, Math.floor((expiresAt - Date.now()) / 1000));
                    if (seconds <= 0) {
                        countdown.textContent = 'This tracking link has expired.';
                        countdown.classList.add('text-amber-700', 'dark:text-amber-300');
                        return;
                    }
                    const days = Math.floor(seconds / 86400);
                    const hours = Math.floor((seconds % 86400) / 3600);
                    const minutes = Math.floor((seconds % 3600) / 60);
                    countdown.textContent = days ? `Expires in ${days}d ${hours}h` : `Expires in ${hours}h ${minutes}m`;
                };
                updateCountdown();
                window.setInterval(updateCountdown, 60000);
            }
        })();

        (() => {
            const widget = document.querySelector('[data-status-widget]');
            if (!widget) return;
            const reference = widget.dataset.reference;
            const token = widget.dataset.token;
            const statusNode = document.getElementById('report-status');
            const timeline = document.querySelector('[data-status-timeline]');
            const refreshNote = document.getElementById('status-refresh');
            const endpoint = `/api/reports/${encodeURIComponent(reference)}/status`;

            const renderTimeline = (entries) => {
                timeline.replaceChildren();
                for (const entry of entries || []) {
                    const item = document.createElement('li');
                    item.className = 'relative';
                    const dot = document.createElement('span');
                    dot.className = 'absolute -left-[1.65rem] top-1 h-3 w-3 rounded-full bg-amber-400 ring-4 ring-amber-100 dark:ring-amber-500/20';
                    const label = document.createElement('p');
                    label.className = 'font-bold text-slate-900 dark:text-white';
                    label.textContent = entry.label || String(entry.status || '').replaceAll('_', ' ');
                    item.append(dot, label);
                    if (entry.timestamp) {
                        const time = document.createElement('p');
                        time.className = 'mt-1 text-sm text-slate-500 dark:text-slate-400';
                        time.textContent = new Date(entry.timestamp).toLocaleString();
                        item.appendChild(time);
                    }
                    timeline.appendChild(item);
                }
            };

            const refreshStatus = async () => {
                const response = await fetch(endpoint, {
                    headers: { Accept: 'application/json', 'X-Tracking-Token': token },
                    cache: 'no-store',
                });
                const data = await response.json();
                if (!response.ok) {
                    if (response.status === 404) {
                        window.location.replace('/track');
                        return;
                    }
                    throw new Error(data.error || 'Unable to refresh report status.');
                }
                statusNode.textContent = String(data.status || 'submitted').replaceAll('_', ' ').replace(/\b\w/g, (letter) => letter.toUpperCase());
                renderTimeline(data.timeline);
                refreshNote.textContent = `Last checked ${new Date().toLocaleTimeString()}. Status updates automatically.`;
            };

            const safeRefresh = () => refreshStatus().catch(() => {
                refreshNote.textContent = 'Status refresh is temporarily unavailable. Your last saved status is still shown.';
            });
            safeRefresh();
            window.setInterval(() => {
                if (!document.hidden) safeRefresh();
            }, 30000);
        })();

        (() => {
            const panel = document.querySelector('[data-message-panel]');
            if (!panel) return;
            const token = panel.dataset.token;
            const list = panel.querySelector('[data-message-list]');
            const empty = panel.querySelector('[data-message-empty]');
            const alert = panel.querySelector('[data-message-alert]');
            const form = panel.querySelector('[data-message-form]');
            const button = form.querySelector('button');
            const endpoint = `/track/${encodeURIComponent(token)}/messages`;

            const showAlert = (message) => {
                alert.textContent = message;
                alert.classList.remove('hidden');
            };

            const renderMessages = (messages) => {
                list.replaceChildren();
                messages.forEach((message) => {
                    const item = document.createElement('li');
                    item.className = 'rounded-2xl bg-slate-50 p-3 text-sm dark:bg-slate-800';
                    const sender = document.createElement('p');
                    sender.className = 'font-bold text-slate-700 dark:text-slate-200';
                    sender.textContent = message.sender_type === 'staff' ? 'MDRRMO staff' : 'You';
                    const body = document.createElement('p');
                    body.className = 'mt-1 whitespace-pre-wrap text-slate-600 dark:text-slate-300';
                    body.textContent = message.body;
                    item.append(sender, body);
                    list.appendChild(item);
                });
                empty.classList.toggle('hidden', messages.length > 0);
            };

            const refreshMessages = async () => {
                const response = await fetch(endpoint, { headers: { Accept: 'application/json' } });
                const data = await response.json();
                if (!response.ok) throw new Error(data.error || 'Unable to load messages.');
                renderMessages(data.messages || []);
                if (data.thread_closed) {
                    form.classList.add('hidden');
                    showAlert('This message thread is closed.');
                }
            };

            if (panel.dataset.closed === 'true') {
                form.classList.add('hidden');
                showAlert('This message thread is closed.');
            }

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                alert.classList.add('hidden');
                button.disabled = true;
                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ body: new FormData(form).get('body') }),
                    });
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.error || Object.values(data.errors || {}).flat().join(' ') || 'Unable to send this message.');
                    form.reset();
                    await refreshMessages();
                } catch (error) {
                    showAlert(error.message || 'Unable to send this message.');
                } finally {
                    button.disabled = false;
                }
            });

            refreshMessages().catch((error) => showAlert(error.message || 'Unable to load messages.'));
            window.setInterval(() => {
                if (!document.hidden && !form.classList.contains('hidden')) {
                    refreshMessages().catch(() => {});
                }
            }, 30000);
        })();
    </script>
</body>
</html>
