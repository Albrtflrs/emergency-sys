<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Report an incident') }}</title>
    @vite('resources/css/app.css')
</head>
<body class="public-surface min-h-screen bg-slate-50 text-slate-800 antialiased transition-colors duration-200 dark:bg-[#18181b] dark:text-slate-100" style="background-image:url('{{ asset('assets/lgu kalibo.jpg') }}');background-size:cover;background-position:center;background-attachment:fixed">
    <div class="app-photo-canvas min-h-screen">
        @include('public.navigation')
        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
            @include('public.hotline-notice')
            <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
                <aside class="civic-panel rounded-[6px] p-6 backdrop-blur dark:bg-slate-950/95 dark:shadow-black/30">
                    <p class="text-xs font-black uppercase tracking-[0.28em] text-blue-800 dark:text-blue-300">{{ __('Resident reporting') }}</p>
                    <h1 class="mt-4 text-3xl font-black text-slate-900 dark:text-white">{{ __('Report an incident') }}</h1>
                    <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">
                        {{ __('Share the key details and a responder can review the report quickly.') }}
                    </p>

                    <div class="mt-6 rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/80">
                        <p class="text-[10px] font-black uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">{{ __('Before you submit') }}</p>
                        <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            <li>• {{ __('Describe what happened clearly and with facts.') }}</li>
                            <li>• {{ __('Include the nearest location or landmark.') }}</li>
                            <li>• {{ __('Use the tracking link to follow updates after submission.') }}</li>
                        </ul>
                    </div>
                </aside>

                <main class="rounded-[28px] bg-white/95 p-6 shadow-xl shadow-slate-900/10 backdrop-blur dark:bg-slate-950/95 dark:shadow-black/30 sm:p-8">
                    <div id="form-alert" class="hidden rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 dark:border-amber-800/60 dark:bg-amber-950/30 dark:text-amber-200" aria-live="polite"></div>

                    <form id="report-form" action="{{ route('public.report.store') }}" method="post" enctype="multipart/form-data" class="mt-6 space-y-7">
                        @csrf
                        <input type="hidden" name="idempotency_key" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
                        <input type="hidden" name="language" value="{{ app()->getLocale() }}">
                        @if($errors->any())
                            <div class="rounded-[4px] border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
                                <p class="font-bold">{{ __('Please check the highlighted report details and try again.') }}</p>
                                <ul class="mt-2 list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            </div>
                        @endif
                        <fieldset class="space-y-5" aria-labelledby="incident-heading">
                            <legend id="incident-heading" class="text-lg font-black text-slate-900 dark:text-white">1. {{ __('Incident information') }}</legend>
                            <label for="category_id" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Incident type') }}</label>
                            <select id="category_id" name="category_id" title="Choose the type of emergency you are reporting." class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-800 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/60" required>
                                <option value="">{{ __('Select an incident type') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category['id'] }}" @selected(old('category_id') == $category['id'])>{{ $category['name'] }}</option>
                                @endforeach
                            </select>

                            <div>
                                <label for="description" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('What happened?') }}</label>
                                <textarea id="description" name="description" rows="5" minlength="20" maxlength="2000" title="Describe what happened, what is affected, and any immediate danger. At least 20 characters." class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-800 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/60" placeholder="{{ __('Describe the incident, what was affected, and any immediate danger.') }}" required></textarea>
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ __('Include immediate danger and what help is needed.') }}</p>
                            </div>
                        </fieldset>

                        <fieldset class="space-y-5 border-t border-slate-200 pt-5 dark:border-slate-700" aria-labelledby="location-heading">
                            <legend id="location-heading" class="text-lg font-black text-slate-900 dark:text-white">2. {{ __('Location information') }}</legend>
                            <div>
                            <label for="location_text" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Location') }}</label>
                            <input id="location_text" name="location_text" type="text" title="Enter a barangay, street, landmark, or nearby location. You can use GPS instead." class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-800 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/60" placeholder="{{ __('Barangay / landmark / street name') }}">
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ __('A nearby landmark helps responders find the location.') }}</p>
                            </div>
                            <input id="latitude" name="latitude" type="hidden" value="{{ old('latitude') }}">
                            <input id="longitude" name="longitude" type="hidden" value="{{ old('longitude') }}">
                            <input id="location_accuracy" name="location_accuracy" type="hidden" value="{{ old('location_accuracy') }}">
                            <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/70">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div><p class="font-bold text-slate-900 dark:text-slate-100">{{ __('Add one supporting photo') }}</p><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ __('Optional. JPG, JPEG, PNG, or WebP up to 4 MB. Avoid images that identify victims.') }}</p></div>
                                </div>
                                <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" class="mt-4 block w-full text-sm text-slate-700 file:mr-4 file:min-h-10 file:border-0 file:bg-blue-800 file:px-4 file:py-2 file:font-bold file:text-white dark:text-slate-200 dark:file:bg-blue-400 dark:file:text-slate-950">
                            </div>
                        </fieldset>

                        <fieldset class="space-y-5 border-t border-slate-200 pt-5 dark:border-slate-700" aria-labelledby="contact-heading">
                            <legend id="contact-heading" class="text-lg font-black text-slate-900 dark:text-white">3. {{ __('Reporter information') }}</legend>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="reporter_name" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Reporter name') }}</label>
                                <input id="reporter_name" name="reporter_name" type="text" title="Enter the name responders can use to contact you." class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-800 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/60" placeholder="{{ __('Full name') }}" required>
                            </div>
                            <div>
                                <label for="contact_number" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Contact number') }}</label>
                                <input id="contact_number" name="contact_number" type="tel" pattern="\+63[0-9]{10,12}" title="Enter a Philippine phone number starting with +63, for example +639171234567." class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-800 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/60" placeholder="{{ __('+63...') }}" required>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/70">
                            <label class="flex items-center gap-3 text-sm font-semibold text-slate-700 dark:text-slate-200">
                                <input id="reporting_for_other" name="reporting_for_other" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-800 focus:ring-blue-800 dark:border-slate-600 dark:bg-slate-900" />
                                {{ __('I am reporting for someone else') }}
                            </label>

                            <div id="other-person-fields" class="mt-4 space-y-4">
                                <div>
                                    <label for="relationship" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Relationship to the person') }}</label>
                                    <input id="relationship" name="relationship" type="text" maxlength="80" title="Describe how you know the person you are reporting for." class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-800 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/60" placeholder="{{ __('Parent / neighbor / coworker') }}">
                                </div>

                                <label class="flex items-center gap-3 text-sm font-semibold text-slate-700 dark:text-slate-200">
                                    <input id="victim_contactable" name="victim_contactable" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-800 focus:ring-blue-800 dark:border-slate-600 dark:bg-slate-900" />
                                    {{ __('The person can be contacted directly') }}
                                </label>
                            </div>
                        </div>

                        @if(config('services.turnstile.site_key'))
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-800/60">
                                <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}"></div>
                            </div>
                        @endif
                        </fieldset>

                        <div class="flex flex-col gap-3 border-t border-slate-200 pt-5 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
                            <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-[4px] bg-blue-800 px-6 py-3 text-sm font-black text-white transition hover:bg-blue-900 dark:bg-blue-400 dark:text-slate-950 dark:hover:bg-blue-300">{{ __('Submit a report') }}</button>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Your information is only used to respond to this report.') }}</p>
                        </div>
                    </form>
                </main>
            </div>
        </div>
        @include('public.report-flow')
        @include('public.footer')
    </div>

</body>
</html>
