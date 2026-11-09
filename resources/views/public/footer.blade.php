<footer class="system-footer">
    <div class="system-footer-inner">
        <div class="system-footer-head">
            <div class="system-footer-identity">
                @include('components.brand-marks')
                <div>
                    <p class="system-footer-kicker">Kalibo MDRRMO · SOU</p>
                    <p class="system-footer-name">Special Operations Unit</p>
                </div>
            </div>
            <a href="{{ route('news.index') }}" class="system-footer-all">All news & advisories <svg aria-hidden="true" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" class="h-3.5 w-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12 12 4M5 4h7v7"/></svg></a>
        </div>

        <section class="system-footer-news" aria-labelledby="footer-news-heading">
            <div class="system-footer-news-heading">
                <h2 id="footer-news-heading">Recent news & updates</h2>
                <p>Latest public notices from the MDRRMO</p>
            </div>
            @if ($footerNews->isNotEmpty())
                <ol class="system-footer-news-list">
                    @foreach ($footerNews as $post)
                        <li>
                            <a href="{{ route('news.show', $post->slug) }}">
                                <time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->format('M j') }}</time>
                                <span class="system-footer-news-copy">
                                    <strong>{{ $post->title }}</strong>
                                    <span>{{ \App\Models\NewsPost::CATEGORIES[$post->category] ?? $post->category }}</span>
                                </span>
                                <svg aria-hidden="true" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" class="system-footer-arrow"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12 12 4M5 4h7v7"/></svg>
                            </a>
                        </li>
                    @endforeach
                </ol>
            @else
                <p class="system-footer-empty">No public updates are published right now. Check the advisories page for the latest notices.</p>
            @endif
        </section>

        <section class="system-footer-hotlines" aria-labelledby="footer-hotline-heading">
            <div class="system-footer-hotline-heading">
                <h2 id="footer-hotline-heading">Kalibo emergency hotlines</h2>
                <p>Numbers listed in the Aklan PDRRMO directory published October 2020; confirm current availability when possible.</p>
            </div>
            <ul>
                <li><span>Kalibo MDRRMO</span><a href="tel:02628991">(036) 262-8991</a></li>
                <li><span>Emergency hotline</span><a href="tel:159">159</a></li>
                <li><span>Mobile</span><a href="tel:+639078951904">0907-895-1904</a></li>
                <li><span>Mobile</span><a href="tel:+639186312150">0918-631-2150</a></li>
            </ul>
            <a class="system-footer-source" href="https://aklan.gov.ph/list-of-disaster-risk-reduction-and-management-offices-in-aklan/" target="_blank" rel="noopener noreferrer">Source: Official Aklan provincial directory <span aria-hidden="true">↗</span></a>
        </section>

        <div class="system-footer-bottom">
            <span>© {{ date('Y') }} Municipality of Kalibo · MDRRMO</span>
            <nav aria-label="Footer navigation">
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('how-it-works') }}">How it works</a>
                <a href="{{ route('public.report') }}">Report an incident</a>
            </nav>
        </div>
    </div>
</footer>