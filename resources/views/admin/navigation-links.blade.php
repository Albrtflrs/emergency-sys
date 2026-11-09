<nav class="operations-nav" aria-label="Admin navigation">
    @if (auth()->user()->role === 'admin')
        <p class="operations-nav-label">Operations</p>
        <a href="{{ route('admin.dashboard') }}" @class(['is-active' => request()->routeIs('admin.dashboard')])>
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="3" width="8" height="8" rx="1"/><rect x="13" y="3" width="8" height="5" rx="1"/><rect x="13" y="10" width="8" height="11" rx="1"/><rect x="3" y="13" width="8" height="8" rx="1"/></svg>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.dashboard.queue') }}" @class(['is-active' => request()->routeIs('admin.dashboard.queue')])>
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/><circle cx="7" cy="6" r="1" fill="currentColor"/><circle cx="7" cy="12" r="1" fill="currentColor"/><circle cx="7" cy="18" r="1" fill="currentColor"/></svg>
            <span>Incident queue</span>
        </a>
    @endif
    <p class="operations-nav-label">Public information</p>
    <a href="{{ route('admin.news.index') }}" @class(['is-active' => request()->routeIs('admin.news.index')])>
        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M5 4h12a2 2 0 0 1 2 2v14H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Zm0 0v14M8 8h7M8 12h7M8 16h4"/></svg>
        <span>News &amp; advisories</span>
    </a>
    <a href="{{ route('admin.news.create') }}" @class(['is-active' => request()->routeIs('admin.news.create', 'admin.news.edit')])>
        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/></svg>
        <span>Create update</span>
    </a>
    <p class="operations-nav-label">Public site</p>
    <a href="{{ route('landing') }}">
        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-6v-7h-4v7H4a1 1 0 0 1-1-1V10Z"/></svg>
        <span>View public site</span>
    </a>
</nav>
