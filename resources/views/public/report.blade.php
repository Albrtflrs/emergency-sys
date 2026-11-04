<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Report an incident') }}</title>
    @vite('resources/css/app.css')
    @if(config('services.turnstile.site_key'))
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
    <script>
        document.documentElement.classList.toggle('dark', localStorage.getItem('theme') ? localStorage.getItem('theme') === 'dark' : matchMedia('(prefers-color-scheme: dark)').matches);
    </script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased transition-colors duration-200 dark:bg-[#111315] dark:text-slate-100" style="background-image:url('{{ asset('assets/lgu kalibo.jpg') }}');background-size:cover;background-position:center;background-attachment:fixed">
    <div class="app-photo-canvas min-h-screen px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl">
            @include('public.hotline-notice')
            <div class="mb-6 flex items-center justify-between gap-4">
                <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-800 hover:text-blue-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300">
                    <span aria-hidden="true">←</span>
                    {{ __('Back home') }}
                </a>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    @include('public.language-switcher', ['availableLocales' => app(App\Support\I18n\LocaleCatalog::class)->all()])
                    @include('components.theme-toggle', ['placement' => 'inline'])
                    <a href="{{ route('track') }}" class="inline-flex items-center justify-center rounded-full bg-blue-800 px-4 py-2 text-sm font-black text-white shadow-sm transition hover:bg-blue-900 dark:bg-blue-400 dark:text-slate-950 dark:hover:bg-blue-300">
                        {{ __('Track your report') }}
                    </a>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
                <aside class="rounded-[28px] bg-white/95 p-6 shadow-xl shadow-slate-900/10 backdrop-blur dark:bg-slate-950/95 dark:shadow-black/30">
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

                    <div id="report-steps" class="mb-6" aria-label="Report steps">
                        <div class="mb-3 flex items-center justify-between gap-2 text-xs font-bold text-slate-500 dark:text-slate-400">
                            <span data-step-indicator="0" aria-current="step" class="text-blue-800 dark:text-blue-300">1 · Incident</span>
                            <span data-step-indicator="1">2 · Location</span>
                            <span data-step-indicator="2">3 · Contact</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800" role="progressbar" aria-label="Form progress" aria-valuemin="1" aria-valuemax="3" aria-valuenow="1">
                            <div id="step-progress" class="h-full w-1/3 rounded-full bg-blue-800 transition-all duration-300 dark:bg-blue-400"></div>
                        </div>
                        <p id="step-caption" class="mt-3 text-sm font-semibold text-slate-700 dark:text-slate-200">Step 1 of 3 · Tell us what happened</p>
                    </div>

                    <form id="report-form" class="mt-6 space-y-5" novalidate>
                        <section data-form-step="0" class="space-y-5" aria-label="Incident details">
                            <label for="category_id" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Incident type') }}</label>
                            <select id="category_id" name="category_id" title="Choose the type of emergency you are reporting." class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-800 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/60" required>
                                <option value="">{{ __('Select an incident type') }}</option>
                            </select>

                            <div>
                                <label for="description" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('What happened?') }}</label>
                                <textarea id="description" name="description" rows="5" minlength="20" maxlength="2000" title="Describe what happened, what is affected, and any immediate danger. At least 20 characters." class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-800 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/60" placeholder="{{ __('Describe the incident, what was affected, and any immediate danger.') }}" required></textarea>
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ __('Include immediate danger and what help is needed.') }}</p>
                            </div>
                        </section>

                        <section data-form-step="1" class="hidden space-y-5" aria-label="Incident location" hidden>
                            <div>
                            <label for="location_text" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Location') }}</label>
                            <input id="location_text" name="location_text" type="text" title="Enter a barangay, street, landmark, or nearby location. You can use GPS instead." class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-800 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/60" placeholder="{{ __('Barangay / landmark / street name') }}">
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ __('A nearby landmark helps responders find the location.') }}</p>
                            </div>
                            <input id="latitude" name="latitude" type="hidden">
                            <input id="longitude" name="longitude" type="hidden">
                            <input id="location_accuracy" name="location_accuracy" type="hidden">
                            <div class="flex flex-wrap items-center gap-3">
                                <button id="use-location" type="button" class="rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:border-blue-400 hover:text-blue-800 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:text-blue-300">Use my current location</button>
                                <span id="location-feedback" class="text-xs text-slate-500 dark:text-slate-400" aria-live="polite">Optional. Your device will ask before sharing.</span>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/70">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div><p class="font-bold text-slate-900 dark:text-slate-100">Add a photo</p><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Optional. Photos can only be captured with the camera here.</p></div>
                                    <button id="open-camera" type="button" class="inline-flex items-center gap-2 rounded-full bg-blue-800 px-4 py-2 text-sm font-bold text-white transition hover:bg-blue-900 dark:bg-blue-400 dark:text-slate-950 dark:hover:bg-blue-300">
                                        <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h3l1.5-2h7L17 7h3v12H4z"/><circle cx="12" cy="13" r="3.5"/></svg>
                                        <span>Open camera</span>
                                    </button>
                                </div>
                                <p id="camera-feedback" class="mt-3 text-xs text-slate-500 dark:text-slate-400" aria-live="polite"></p>
                                <div id="camera-panel" class="mt-4 hidden space-y-3" hidden>
                                    <video id="camera-preview" class="max-h-72 w-full rounded-2xl bg-slate-950 object-contain" autoplay playsinline muted></video>
                                    <div class="flex flex-wrap gap-2">
                                        <button id="capture-photo" type="button" class="rounded-full bg-blue-800 px-4 py-2 text-sm font-bold text-white hover:bg-blue-900 dark:bg-blue-400 dark:text-slate-950 dark:hover:bg-blue-300">Capture photo</button>
                                        <button id="close-camera" type="button" class="rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">Close camera</button>
                                    </div>
                                </div>
                                <div id="captured-photo-panel" class="mt-4 hidden" hidden>
                                    <img id="captured-photo-preview" alt="Preview of captured incident photo" class="max-h-72 rounded-2xl object-contain shadow-sm">
                                    <button id="retake-photo" type="button" class="mt-2 text-sm font-bold text-blue-800 hover:underline dark:text-blue-300">Retake photo</button>
                                </div>
                            </div>
                        </section>

                        <section data-form-step="2" class="hidden space-y-5" aria-label="Reporter contact information" hidden>
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

                            <div id="other-person-fields" class="mt-4 hidden space-y-4">
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
                        </section>

                        <div class="flex flex-col gap-3 border-t border-slate-200 pt-5 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-2">
                                <button type="button" id="step-back" class="hidden rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:border-blue-400 hover:text-blue-800 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:text-blue-300">Back</button>
                                <button type="button" id="step-next" class="rounded-full bg-blue-800 px-6 py-3 text-sm font-black text-white transition hover:bg-blue-900 dark:bg-blue-400 dark:text-slate-950 dark:hover:bg-blue-300">Continue</button>
                                <button type="submit" id="submit-report" class="hidden items-center justify-center rounded-full bg-blue-800 px-6 py-3 text-sm font-black text-white transition hover:bg-blue-900 disabled:cursor-wait disabled:opacity-60 dark:bg-blue-400 dark:text-slate-950 dark:hover:bg-blue-300">
                                    <span id="submit-label">{{ __('Submit a report') }}</span>
                                    <span id="submit-spinner" class="ml-2 hidden h-4 w-4 animate-spin rounded-full border-2 border-current border-r-transparent" aria-hidden="true"></span>
                                </button>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Your information is only used to respond to this report.') }}</p>
                        </div>
                    </form>

                    <div id="success-panel" class="mt-6 hidden rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-emerald-900 dark:border-emerald-800/60 dark:bg-emerald-950/30 dark:text-emerald-100">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-emerald-700 dark:text-emerald-300">{{ __('Report submitted') }}</p>
                        <h2 class="mt-2 text-xl font-black">{{ __('Your report has been received.') }}</h2>
                        <p class="mt-2 text-sm leading-6 text-emerald-800 dark:text-emerald-100">
                            {{ __('Track it using your reference code and the link below.') }}
                        </p>
                        <div class="mt-4 space-y-2 text-sm">
                            <p><span class="font-semibold">{{ __('Reference code') }}:</span> <span id="success-reference"></span></p>
                            <p><span class="font-semibold">{{ __('Tracking link') }}:</span> <a id="success-link" href="#" class="underline"></a></p>
                            <p><span class="font-semibold">{{ __('Tracking link expires') }}:</span> <span id="success-expiry"></span></p>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <button id="copy-tracking-link" type="button" class="rounded-full border border-emerald-800/30 px-4 py-2 text-sm font-bold hover:bg-emerald-900/5">Copy tracking link</button>
                            <button id="share-tracking-link" type="button" class="rounded-full border border-emerald-800/30 px-4 py-2 text-sm font-bold hover:bg-emerald-900/5">Share link</button>
                            <button id="new-report" type="button" class="rounded-full bg-blue-800 px-4 py-2 text-sm font-bold text-white hover:bg-blue-900 dark:bg-blue-400 dark:text-slate-950 dark:hover:bg-blue-300">Submit another report</button>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('report-form');
        const alertBox = document.getElementById('form-alert');
        const successPanel = document.getElementById('success-panel');
        const successReference = document.getElementById('success-reference');
        const successLink = document.getElementById('success-link');
        const successExpiry = document.getElementById('success-expiry');
        const reportSteps = document.getElementById('report-steps');
        const otherPersonFields = document.getElementById('other-person-fields');
        const reportingForOther = document.getElementById('reporting_for_other');
        const steps = [...form.querySelectorAll('[data-form-step]')];
        const stepIndicators = [...document.querySelectorAll('[data-step-indicator]')];
        const backButton = document.getElementById('step-back');
        const nextButton = document.getElementById('step-next');
        const submitButton = document.getElementById('submit-report');
        const submitLabel = document.getElementById('submit-label');
        const submitSpinner = document.getElementById('submit-spinner');
        const stepProgress = document.getElementById('step-progress');
        const progressBar = stepProgress.parentElement;
        const stepCaption = document.getElementById('step-caption');
        const relationshipInput = document.getElementById('relationship');
        const locationButton = document.getElementById('use-location');
        const locationFeedback = document.getElementById('location-feedback');
        const cameraButton = document.getElementById('open-camera');
        const cameraPanel = document.getElementById('camera-panel');
        const cameraPreview = document.getElementById('camera-preview');
        const cameraFeedback = document.getElementById('camera-feedback');
        const capturedPanel = document.getElementById('captured-photo-panel');
        const capturedPreview = document.getElementById('captured-photo-preview');
        let activeStep = 0;
        let cameraStream = null;
        let capturedPhoto = null;
        let capturedPhotoUrl = null;
        let isSubmitting = false;
        let idempotencyKey = crypto.randomUUID();

        const showAlert = (message) => {
            alertBox.textContent = message;
            alertBox.classList.remove('hidden');
        };

        const hideAlert = () => {
            alertBox.textContent = '';
            alertBox.classList.add('hidden');
        };

        const updateStep = (focus = false) => {
            steps.forEach((step, index) => {
                const active = index === activeStep;
                step.hidden = !active;
                step.classList.toggle('hidden', !active);
            });
            stepIndicators.forEach((indicator, index) => {
                const active = index === activeStep;
                indicator.classList.toggle('text-blue-800', active);
                indicator.classList.toggle('dark:text-blue-300', active);
                indicator.setAttribute('aria-current', active ? 'step' : 'false');
            });
            backButton.classList.toggle('hidden', activeStep === 0);
            nextButton.classList.toggle('hidden', activeStep === steps.length - 1);
            submitButton.classList.toggle('hidden', activeStep !== steps.length - 1);
            submitButton.classList.toggle('inline-flex', activeStep === steps.length - 1);
            stepProgress.style.width = `${((activeStep + 1) / steps.length) * 100}%`;
            progressBar.setAttribute('aria-valuenow', String(activeStep + 1));
            stepCaption.textContent = [
                'Step 1 of 3 · Tell us what happened',
                'Step 2 of 3 · Add a location and optional photo',
                'Step 3 of 3 · How can responders reach you?',
            ][activeStep];
            if (focus) steps[activeStep].querySelector('input:not([type="hidden"]), textarea, select, button')?.focus();
        };

        const validateStep = (stepIndex) => {
            if (stepIndex === 0 && document.getElementById('category_id').options.length <= 1) {
                showAlert('Incident types are still loading. Please wait and try again.');
                return false;
            }
            const invalid = [...steps[stepIndex].querySelectorAll('input, textarea, select')]
                .find((field) => !field.disabled && !field.checkValidity());
            if (invalid) {
                invalid.reportValidity();
                invalid.focus();
                return false;
            }
            if (stepIndex === 1 && !document.getElementById('location_text').value.trim() && !document.getElementById('latitude').value) {
                showAlert('Type a location or pin your current location to continue.');
                document.getElementById('location_text').focus();
                return false;
            }
            hideAlert();
            return true;
        };

        nextButton.addEventListener('click', () => {
            if (!validateStep(activeStep)) return;
            if (activeStep === 1) stopCamera();
            activeStep = Math.min(activeStep + 1, steps.length - 1);
            updateStep(true);
        });
        backButton.addEventListener('click', () => {
            if (activeStep === 1) stopCamera();
            activeStep = Math.max(activeStep - 1, 0);
            hideAlert();
            updateStep(true);
        });

        reportingForOther.addEventListener('change', () => {
            otherPersonFields.classList.toggle('hidden', !reportingForOther.checked);
            relationshipInput.required = reportingForOther.checked;
        });

        locationButton.addEventListener('click', () => {
            if (!navigator.geolocation) {
                locationFeedback.textContent = 'Location pinning is not available in this browser. You can type the location instead.';
                return;
            }
            locationButton.disabled = true;
            locationFeedback.textContent = 'Waiting for location permission…';
            navigator.geolocation.getCurrentPosition((position) => {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
                document.getElementById('location_accuracy').value = position.coords.accuracy;
                locationFeedback.textContent = `Location pinned (${position.coords.latitude.toFixed(4)}, ${position.coords.longitude.toFixed(4)}). You can still edit the address.`;
                locationButton.disabled = false;
            }, () => {
                locationFeedback.textContent = 'Location permission was unavailable. You can type the location instead.';
                locationButton.disabled = false;
            }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 });
        });

        const stopCamera = () => {
            cameraStream?.getTracks().forEach((track) => track.stop());
            cameraStream = null;
            cameraPreview.srcObject = null;
            cameraPanel.hidden = true;
            cameraPanel.classList.add('hidden');
        };

        cameraButton.addEventListener('click', async () => {
            if (!navigator.mediaDevices?.getUserMedia) {
                cameraFeedback.textContent = 'Camera capture requires a supported browser on HTTPS or localhost.';
                return;
            }
            try {
                cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' } }, audio: false });
                cameraPreview.srcObject = cameraStream;
                cameraPanel.hidden = false;
                cameraPanel.classList.remove('hidden');
                cameraFeedback.textContent = 'Camera is on. The page will only keep a photo after you press Capture photo.';
            } catch {
                cameraFeedback.textContent = 'Camera access was denied or unavailable. You can continue without a photo.';
            }
        });

        document.getElementById('close-camera').addEventListener('click', stopCamera);
        document.getElementById('capture-photo').addEventListener('click', () => {
            if (!cameraPreview.videoWidth || !cameraPreview.videoHeight) {
                cameraFeedback.textContent = 'The camera is not ready yet. Try again in a moment.';
                return;
            }
            const scale = Math.min(1, 1600 / Math.max(cameraPreview.videoWidth, cameraPreview.videoHeight));
            const canvas = document.createElement('canvas');
            canvas.width = Math.round(cameraPreview.videoWidth * scale);
            canvas.height = Math.round(cameraPreview.videoHeight * scale);
            canvas.getContext('2d').drawImage(cameraPreview, 0, 0, canvas.width, canvas.height);
            canvas.toBlob((blob) => {
                if (!blob) {
                    cameraFeedback.textContent = 'The photo could not be prepared. Please try again.';
                    return;
                }
                capturedPhoto = blob;
                if (capturedPhotoUrl) URL.revokeObjectURL(capturedPhotoUrl);
                capturedPhotoUrl = URL.createObjectURL(blob);
                capturedPreview.src = capturedPhotoUrl;
                capturedPanel.hidden = false;
                capturedPanel.classList.remove('hidden');
                cameraFeedback.textContent = 'Photo captured. It will be attached securely with this report.';
                stopCamera();
            }, 'image/jpeg', 0.82);
        });
        document.getElementById('retake-photo').addEventListener('click', () => {
            capturedPhoto = null;
            capturedPanel.hidden = true;
            capturedPanel.classList.add('hidden');
            if (capturedPhotoUrl) URL.revokeObjectURL(capturedPhotoUrl);
            capturedPhotoUrl = null;
            cameraButton.click();
        });

        async function loadCategories() {
            try {
                const response = await fetch('/api/categories?locale=' + encodeURIComponent(document.documentElement.lang || 'en'));
                if (!response.ok) {
                    throw new Error('Unable to load incident types.');
                }

                const categories = await response.json();
                const select = document.getElementById('category_id');
                categories.forEach((category) => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    select.appendChild(option);
                });
            } catch (error) {
                showAlert('Incident types are temporarily unavailable. Please try again in a few minutes.');
            }
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (isSubmitting || !validateStep(activeStep)) return;
            hideAlert();
            successPanel.classList.add('hidden');

            const formData = new FormData(form);
            formData.set('description', String(formData.get('description') || '').trim());
            formData.set('location_text', String(formData.get('location_text') || '').trim());
            formData.set('reporter_name', String(formData.get('reporter_name') || '').trim());
            formData.set('contact_number', String(formData.get('contact_number') || '').trim());
            formData.set('reporting_for_other', reportingForOther.checked ? '1' : '0');
            formData.set('victim_contactable', document.getElementById('victim_contactable').checked ? '1' : '0');
            formData.set('language', document.documentElement.lang || 'en');
            formData.set('idempotency_key', idempotencyKey);
            if (capturedPhoto) formData.set('photo', capturedPhoto, 'incident-photo.jpg');

            if (typeof window.turnstile !== 'undefined') {
                const token = document.querySelector('.cf-turnstile textarea[name="cf-turnstile-response"]');
                if (token) {
                    formData.set('cf_turnstile_response', token.value);
                }
            }

            isSubmitting = true;
            submitButton.disabled = true;
            submitSpinner.classList.remove('hidden');
            submitLabel.textContent = 'Sending report…';
            try {
                const response = await fetch('/api/reports', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok) {
                    const message = data?.errors && typeof data.errors === 'object'
                        ? Object.values(data.errors).flat().join(' ')
                        : 'We could not submit this report. Please check the details and try again.';

                    if (response.status === 429) {
                        throw new Error('The reporting queue is currently full. Please try again in a few minutes or contact the MDRRMO office.');
                    }

                    throw new Error(message);
                }

                successReference.textContent = data.reference_code;
                successLink.textContent = data.tracking_url || '';
                successLink.href = data.tracking_url || '#';
                successExpiry.textContent = data.expires_at ? new Date(data.expires_at).toLocaleString() : 'See the expiry shown on your tracking page.';
                successPanel.classList.remove('hidden');
                form.classList.add('hidden');
                reportSteps.classList.add('hidden');
                form.reset();
                capturedPhoto = null;
                if (capturedPhotoUrl) URL.revokeObjectURL(capturedPhotoUrl);
                capturedPhotoUrl = null;
                capturedPanel.hidden = true;
                capturedPanel.classList.add('hidden');
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
                document.getElementById('location_accuracy').value = '';
                locationFeedback.textContent = 'Optional. Your device will ask before sharing.';
                if (document.querySelector('.cf-turnstile')) {
                    if (window.turnstile) {
                        window.turnstile.reset();
                    }
                }
                otherPersonFields.classList.add('hidden');
                relationshipInput.required = false;
                idempotencyKey = crypto.randomUUID();
                activeStep = 0;
                updateStep();
            } catch (error) {
                showAlert(error.message || 'We could not submit this report. Please try again in a few minutes.');
            } finally {
                isSubmitting = false;
                submitButton.disabled = false;
                submitSpinner.classList.add('hidden');
                submitLabel.textContent = 'Submit a report';
            }
        });

        document.getElementById('copy-tracking-link').addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(successLink.href);
                document.getElementById('copy-tracking-link').textContent = 'Copied';
            } catch {
                showAlert('Copy was unavailable. Select the tracking link above to copy it manually.');
            }
        });
        document.getElementById('share-tracking-link').addEventListener('click', async () => {
            if (!navigator.share) {
                showAlert('Link sharing is unavailable in this browser. Use Copy tracking link instead.');
                return;
            }
            try {
                await navigator.share({ title: 'Emergency report tracking', text: `Report ${successReference.textContent}`, url: successLink.href });
            } catch (error) {
                if (error.name !== 'AbortError') showAlert('The tracking link could not be shared. You can copy it instead.');
            }
        });
        document.getElementById('new-report').addEventListener('click', () => {
            successPanel.classList.add('hidden');
            form.classList.remove('hidden');
            reportSteps.classList.remove('hidden');
            hideAlert();
            updateStep(true);
        });

        window.addEventListener('pagehide', () => {
            stopCamera();
            if (capturedPhotoUrl) URL.revokeObjectURL(capturedPhotoUrl);
        });
        updateStep();
        loadCategories();
    </script>
</body>
</html>
