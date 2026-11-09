<section id="hotline-directory" class="mt-12 border-y border-slate-300 py-8 dark:border-slate-700" aria-labelledby="hotline-heading">
    <div class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-start">
        <div>
            <h2 id="hotline-heading" class="text-2xl font-black text-slate-950 dark:text-white">Emergency Hotline Directory</h2>
            <p class="mt-3 max-w-xl text-sm leading-6 text-slate-700 dark:text-slate-200">Life-threatening emergencies must be reported directly to the appropriate emergency hotline. This website does not dispatch responders.</p>
        </div>
        <div>
            <p class="mb-3 border border-amber-400 bg-amber-50 px-4 py-3 text-sm font-bold text-amber-950 dark:border-amber-600 dark:bg-amber-950/30 dark:text-amber-100">Official direct numbers have not yet been verified for publication. Call-to-call links will appear here after MDRRMO approval.</p>
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2">
                @foreach(['MDRRMO', 'Bureau of Fire Protection (BFP)', 'Philippine National Police (PNP)', 'Ambulance / medical response'] as $service)
                    <div class="flex items-start justify-between gap-4 border-b border-slate-300 py-2 dark:border-slate-700">
                        <dt class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $service }}</dt>
                        <dd class="text-right text-sm text-slate-600 dark:text-slate-300">Pending verified number</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>
</section>

<section id="latest-updates" class="mt-12" aria-labelledby="updates-heading">
    <div class="flex flex-col gap-4 border-b border-slate-300 pb-5 dark:border-slate-700 md:flex-row md:items-end md:justify-between">
        <div>
            <h2 id="updates-heading" class="text-3xl font-black text-slate-950 dark:text-white">Latest Incident Updates</h2>
            <p class="mt-2 text-sm text-slate-700 dark:text-slate-200">Demonstration data only. These examples are not real reports or current emergencies.</p>
        </div>
        <a href="{{ route('track') }}" class="inline-flex min-h-10 items-center justify-center border border-blue-800 px-4 py-2 text-sm font-bold text-blue-900 hover:bg-blue-50 dark:border-blue-300 dark:text-blue-200 dark:hover:bg-slate-800">Track your report</a>
    </div>

    <form method="get" action="{{ route('landing') }}#latest-updates" class="mt-5 grid gap-3 border-b border-slate-300 pb-5 dark:border-slate-700 sm:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr_auto_auto] lg:items-end">
        <div>
            <label for="incident-search" class="mb-1 block text-sm font-bold text-slate-800 dark:text-slate-100">Search example updates</label>
            <input id="incident-search" name="q" type="search" value="{{ $filters['q'] }}" placeholder="Category or example location" class="w-full border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
        </div>
        <div>
            <label for="incident-category" class="mb-1 block text-sm font-bold text-slate-800 dark:text-slate-100">Category</label>
            <select id="incident-category" name="category" class="w-full border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                <option value="">All categories</option>
                @foreach($categories as $category)<option value="{{ $category }}" @selected($filters['category'] === $category)>{{ $category }}</option>@endforeach
            </select>
        </div>
        <div>
            <label for="incident-status" class="mb-1 block text-sm font-bold text-slate-800 dark:text-slate-100">Status</label>
            <select id="incident-status" name="status" class="w-full border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                <option value="">All statuses</option>
                @foreach($statuses as $status)<option value="{{ $status }}" @selected($filters['status'] === $status)>{{ $status }}</option>@endforeach
            </select>
        </div>
        <button type="submit" class="min-h-10 border border-blue-800 bg-blue-800 px-4 py-2 text-sm font-bold text-white hover:bg-blue-900 dark:border-blue-300 dark:bg-blue-300 dark:text-slate-950">Apply filters</button>
        <a href="{{ route('landing') }}#latest-updates" class="inline-flex min-h-10 items-center justify-center border border-slate-400 px-4 py-2 text-sm font-bold text-slate-800 hover:bg-slate-100 dark:border-slate-600 dark:text-slate-100 dark:hover:bg-slate-800">Clear</a>
    </form>

    <fieldset class="mt-5">
        <legend class="mb-2 text-sm font-bold text-slate-800 dark:text-slate-100">Display mode</legend>
        <div class="flex flex-wrap gap-4 text-sm text-slate-800 dark:text-slate-100">
            <label class="inline-flex items-center gap-2"><input class="peer/list" type="radio" name="updates-view" checked><span>List</span></label>
            <label class="inline-flex items-center gap-2"><input class="peer/map" type="radio" name="updates-view"><span>Illustrative map</span></label>
        </div>
    </fieldset>

    <div class="mt-4 hidden grid-cols-1 gap-3 peer-checked/list:grid md:grid-cols-2 xl:grid-cols-4">
        @forelse($incidents as $incident)
            @php
                $statusTone = match($incident['status']) {
                    'Pending' => 'border-amber-500 bg-amber-50 text-amber-950 dark:bg-amber-950/30 dark:text-amber-100',
                    'Under Review' => 'border-sky-600 bg-sky-50 text-sky-950 dark:bg-sky-950/30 dark:text-sky-100',
                    'In Progress' => 'border-orange-600 bg-orange-50 text-orange-950 dark:bg-orange-950/30 dark:text-orange-100',
                    default => 'border-emerald-700 bg-emerald-50 text-emerald-950 dark:bg-emerald-950/30 dark:text-emerald-100',
                };
            @endphp
            <article id="{{ $incident['id'] }}" class="flex min-h-52 flex-col border border-slate-300 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-3">
                    <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ $incident['category'] }}</h3>
                    <span class="shrink-0 border px-2 py-1 text-xs font-bold {{ $statusTone }}">{{ $incident['status'] }}</span>
                </div>
                <p class="mt-3 text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $incident['location'] }}</p>
                <p class="mt-1 text-xs text-slate-600 dark:text-slate-300">{{ $incident['reported_at'] }}</p>
                <p class="mt-3 text-sm leading-6 text-slate-700 dark:text-slate-200">{{ $incident['description'] }}</p>
                <span class="mt-auto pt-4 text-xs font-black uppercase text-slate-600 dark:text-slate-300">Demonstration entry</span>
            </article>
        @empty
            <p class="border border-slate-300 bg-white px-4 py-6 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">No demonstration entries match these filters. Clear the filters to see the examples.</p>
        @endforelse
    </div>

    <section class="mt-4 hidden border border-slate-300 bg-[#e3ece4] p-4 dark:border-slate-700 dark:bg-slate-900 peer-checked/map:block" aria-label="Illustrative incident map">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h3 class="text-lg font-black text-slate-950 dark:text-white">Illustrative location diagram</h3>
                <p class="mt-1 text-sm text-slate-700 dark:text-slate-200">Not a geographic map. Markers are fictional demonstration entries; no resident locations are shown.</p>
            </div>
            <p class="text-xs font-bold text-slate-700 dark:text-slate-200">Use the list view for summaries</p>
        </div>
        <div class="relative mt-4 min-h-56 overflow-hidden border border-slate-400 bg-white p-4 dark:border-slate-600 dark:bg-slate-800">
            <svg aria-hidden="true" viewBox="0 0 720 260" class="absolute inset-0 h-full w-full" preserveAspectRatio="none">
                <path d="M-20 210 C120 185 170 225 270 170 S470 115 740 145 M-10 65 C150 105 205 35 340 72 S520 195 740 40 M115 -10 C170 80 165 150 220 270 M470 -10 C410 75 505 125 460 270 M680 -10 C600 75 660 170 590 270" fill="none" stroke="#bfcdc2" stroke-width="18"/>
                <path d="M-20 210 C120 185 170 225 270 170 S470 115 740 145 M-10 65 C150 105 205 35 340 72 S520 195 740 40 M115 -10 C170 80 165 150 220 270 M470 -10 C410 75 505 125 460 270 M680 -10 C600 75 660 170 590 270" fill="none" stroke="#ffffff" stroke-width="7"/>
            </svg>
            <div class="relative grid min-h-48 grid-cols-2 gap-3 sm:grid-cols-4">
                @forelse($incidents as $incident)
                    <details class="self-start border border-slate-300 bg-white p-3 shadow-sm dark:border-slate-600 dark:bg-slate-900">
                        <summary class="cursor-pointer text-sm font-black text-slate-900 dark:text-white">
                            <span class="mr-2 inline-block h-3 w-3 rounded-full {{ $incident['status'] === 'Pending' ? 'bg-amber-500' : ($incident['status'] === 'Under Review' ? 'bg-sky-600' : ($incident['status'] === 'In Progress' ? 'bg-orange-600' : 'bg-emerald-700')) }}"></span>{{ $incident['category'] }} marker
                        </summary>
                        <p class="mt-2 text-xs leading-5 text-slate-700 dark:text-slate-200">{{ $incident['location'] }} · {{ $incident['status'] }} · Demo only</p>
                    </details>
                @empty
                    <p class="col-span-full self-center text-sm font-semibold text-slate-700 dark:text-slate-200">No demonstration markers match these filters.</p>
                @endforelse
            </div>
        </div>
        <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-xs font-semibold text-slate-700 dark:text-slate-200">
            <span><i class="mr-1 inline-block h-3 w-3 rounded-full bg-amber-500"></i>Pending</span>
            <span><i class="mr-1 inline-block h-3 w-3 rounded-full bg-sky-600"></i>Under review</span>
            <span><i class="mr-1 inline-block h-3 w-3 rounded-full bg-orange-600"></i>In progress</span>
            <span><i class="mr-1 inline-block h-3 w-3 rounded-full bg-emerald-700"></i>Resolved</span>
        </div>
    </section>
</section>

<section id="preparedness" class="mt-12 border-t border-slate-300 pt-8 dark:border-slate-700" aria-labelledby="preparedness-heading">
    <div class="grid gap-3 border-b border-slate-300 pb-5 dark:border-slate-700 md:grid-cols-[1fr_1.2fr] md:items-end">
        <h2 id="preparedness-heading" class="text-3xl font-black text-slate-950 dark:text-white">Emergency Preparedness and Safety</h2>
        <p class="text-sm leading-6 text-slate-700 dark:text-slate-200">Practical reminders for households. Follow instructions from authorized local responders during an emergency.</p>
    </div>
    <div class="mt-3 grid gap-x-8 md:grid-cols-2 xl:grid-cols-3">
        @foreach([
            ['Flood preparedness', 'Keep essential medicines and documents in a sealed, reachable bag. Move to higher ground when local authorities advise evacuation.'],
            ['Fire safety', 'Know two ways out of each room. Leave immediately when there is smoke or fire; do not re-enter to retrieve belongings.'],
            ['Earthquake preparedness', 'Practice Drop, Cover, and Hold On. After shaking stops, check for hazards and follow official guidance.'],
            ['Typhoon preparedness', 'Monitor official weather bulletins, secure loose outdoor items, and prepare drinking water and essential supplies.'],
            ['Landslide safety', 'Watch for ground cracks, leaning trees, or unusual slope sounds. Move away from slopes and streams when advised.'],
            ['First aid awareness', 'Keep a basic first-aid kit. Call qualified emergency services for serious injuries and follow dispatcher instructions.'],
        ] as [$topic, $guidance])
            <details class="border-b border-slate-300 py-4 dark:border-slate-700">
                <summary class="cursor-pointer text-base font-black text-slate-900 dark:text-white">{{ $topic }} <span class="ml-2 text-sm font-semibold text-blue-800 underline underline-offset-4 dark:text-blue-300">Learn more</span></summary>
                <p class="mt-3 max-w-prose text-sm leading-6 text-slate-700 dark:text-slate-200">{{ $guidance }}</p>
            </details>
        @endforeach
    </div>
</section>

<section id="announcements" class="mt-12 border-y border-slate-300 py-8 dark:border-slate-700" aria-labelledby="announcements-heading">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 id="announcements-heading" class="text-2xl font-black text-slate-950 dark:text-white">Public Announcements and Alerts</h2>
            <p class="mt-2 text-sm text-slate-700 dark:text-slate-200">Weather advisories, warnings, closures, and evacuation guidance appear here only after authorized publication.</p>
        </div>
        <span class="border border-slate-400 px-3 py-1 text-xs font-bold uppercase text-slate-700 dark:border-slate-600 dark:text-slate-200">Verified sources only</span>
    </div>
    <p class="mt-5 border border-slate-300 bg-white px-4 py-5 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">No verified public announcements are available at this time.</p>
</section>

<footer id="privacy-note" class="mt-10 border-t border-slate-300 py-7 text-sm dark:border-slate-700">
    <div class="grid gap-6 md:grid-cols-3">
        <div>
            <p class="font-black text-slate-900 dark:text-white">MDRRMO · Kalibo</p>
            <p class="mt-2 leading-6 text-slate-700 dark:text-slate-200">Emergency Response Portal. This service collects reports and provides status tracking; it is not an emergency dispatch service.</p>
        </div>
        <nav aria-label="Footer navigation" class="flex flex-col items-start gap-2 text-slate-700 dark:text-slate-200">
            <a class="underline underline-offset-4 hover:text-blue-800 dark:hover:text-blue-300" href="{{ route('public.report') }}">Report an incident</a>
            <a class="underline underline-offset-4 hover:text-blue-800 dark:hover:text-blue-300" href="{{ route('track') }}">Track a report</a>
            <a class="underline underline-offset-4 hover:text-blue-800 dark:hover:text-blue-300" href="#hotline-directory">Emergency hotline directory</a>
            <a class="underline underline-offset-4 hover:text-blue-800 dark:hover:text-blue-300" href="#preparedness">Preparedness guidance</a>
        </nav>
        <p class="text-slate-700 dark:text-slate-200">Do not include unnecessary personal details in public-facing content. Reporter contact information is handled through the private reporting and tracking workflow.</p>
    </div>
    <p class="mt-6 border-t border-slate-300 pt-4 text-xs text-slate-600 dark:border-slate-700 dark:text-slate-300">© {{ date('Y') }} MDRRMO · Kalibo. Official hotline details and public notices require administrative verification.</p>
</footer>
