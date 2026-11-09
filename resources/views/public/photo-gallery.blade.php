<section class="mt-10 border-y border-slate-300 py-7 dark:border-slate-700" aria-labelledby="photo-gallery-heading">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
        <h2 id="photo-gallery-heading" class="text-xl font-black text-slate-950 dark:text-white">Kalibo MDRRMO</h2>
        <span class="text-xs font-bold uppercase text-slate-600 dark:text-slate-300">Municipal service</span>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($photos as $photo)
            <figure class="min-w-0">
                <img src="{{ asset($photo['src']) }}" alt="{{ $photo['alt'] }}" loading="lazy" class="aspect-[16/9] w-full border border-slate-300 object-cover dark:border-slate-700">
                <figcaption class="mt-2 text-sm font-bold text-slate-700 dark:text-slate-200">{{ $photo['caption'] }}</figcaption>
            </figure>
        @endforeach
    </div>
</section>