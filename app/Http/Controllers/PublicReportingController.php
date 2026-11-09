<?php

namespace App\Http\Controllers;

use App\Models\IncidentCategory;
use App\Models\IncidentReport;
use App\Models\Setting;
use App\Support\I18n\LocaleCatalog;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PublicReportingController extends Controller
{
    public function create(LocaleCatalog $locales): View
    {
        $locale = app()->getLocale();
        $categories = IncidentCategory::query()
            ->active()
            ->get()
            ->map(fn (IncidentCategory $category) => [
                'id' => $category->id,
                'name' => $category->nameFor($locale, 'en'),
            ]);

        return view('public.report', compact('categories'));
    }

    public function categories(Request $request, LocaleCatalog $locales): JsonResponse
    {
        $requestedLocale = (string) $request->query('locale', app()->getLocale());
        $locale = $locales->supports($requestedLocale) ? $requestedLocale : config('app.fallback_locale');

        $categories = IncidentCategory::query()
            ->active()
            ->get()
            ->map(fn (IncidentCategory $category) => [
                'id' => $category->id,
                'slug' => $category->slug,
                'name' => $category->nameFor($locale, 'en'),
            ])
            ->values();

        return response()->json($categories);
    }

    public function store(Request $request, LocaleCatalog $locales): JsonResponse
    {
        $descriptionMin = (int) Setting::get('report.description_min_length', 20);
        $descriptionMax = (int) Setting::get('report.description_max_length', 2000);
        $reporterNameMax = (int) Setting::get('report.reporter_name_max_length', 120);
        $expiryDays = (int) Setting::get('report.expiry_days', 7);

        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:incident_categories,id'],
            'description' => ['required', 'string', 'min:' . $descriptionMin, 'max:' . $descriptionMax],
            'location_text' => ['nullable', 'string', 'max:500', 'required_without_all:latitude,longitude'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180', 'required_with:latitude'],
            'location_accuracy' => ['nullable', 'numeric', 'min:0'],
            'reporter_name' => ['required', 'string', 'max:' . $reporterNameMax],
            'contact_number' => ['required', 'string', 'regex:/^\+63[0-9]{10,12}$/'],
            'reporting_for_other' => ['boolean'],
            'relationship' => ['nullable', 'required_if:reporting_for_other,1', 'string', 'max:80'],
            'victim_contactable' => ['nullable', 'boolean'],
            'language' => ['nullable', 'string', Rule::in(array_keys($locales->all()))],
            'idempotency_key' => ['required', 'string', 'max:255'],
        ]);

        $category = IncidentCategory::query()
            ->whereKey($data['category_id'])
            ->active()
            ->first();

        if (! $category) {
            throw ValidationException::withMessages([
                'category_id' => ['The selected category is not available.'],
            ]);
        }

        $data['location_text'] = trim((string) ($data['location_text'] ?? ''));
        if ($data['location_text'] === '' && isset($data['latitude'], $data['longitude'])) {
            $data['location_text'] = sprintf('Pinned location: %s, %s', $data['latitude'], $data['longitude']);
        }

        $existing = IncidentReport::query()
            ->where('idempotency_key', $data['idempotency_key'])
            ->first();

        if ($existing) {
            return response()->json([
                ...$this->serializeReport($existing),
                ...$this->issueTrackingLink($existing),
            ], 200);
        }

        $rawTrackingToken = bin2hex(random_bytes(32));
        $photoPath = $request->file('photo')?->store('report-evidence', 'local');
        if ($request->hasFile('photo') && ! $photoPath) {
            return response()->json(['errors' => ['photo' => ['The captured photo could not be saved. Please try again or continue without it.']]], 500);
        }

        try {
            $report = DB::transaction(function () use ($data, $category, $expiryDays, $rawTrackingToken, $photoPath): IncidentReport {
                $report = IncidentReport::query()->create([
                    'category_id' => $category->id,
                    'reference_code' => IncidentReport::nextReferenceCode(),
                    'description' => $data['description'],
                    'location_text' => $data['location_text'],
                    'photo_path' => $photoPath,
                    'latitude' => $data['latitude'] ?? null,
                    'longitude' => $data['longitude'] ?? null,
                    'location_accuracy' => $data['location_accuracy'] ?? null,
                    'reporter_name' => $data['reporter_name'],
                    'contact_number' => $data['contact_number'],
                    'reporting_for_other' => (bool) ($data['reporting_for_other'] ?? false),
                    'relationship' => $data['reporting_for_other'] ? ($data['relationship'] ?? null) : null,
                    'victim_contactable' => $data['reporting_for_other'] ? ($data['victim_contactable'] ?? null) : null,
                    'language' => $data['language'] ?? app()->getLocale(),
                    'priority' => 'medium',
                    'status' => 'submitted',
                    'idempotency_key' => $data['idempotency_key'],
                    'suspicion_flag' => false,
                    'submitted_at' => now(),
                    'expires_at' => now()->addDays($expiryDays),
                ]);

                $report->statusEvents()->create(['status' => 'submitted']);
                $this->storeTrackingToken($report, $rawTrackingToken);

                return $report;
            });
        } catch (QueryException $exception) {
            if ($photoPath) {
                Storage::disk('local')->delete($photoPath);
            }

            $duplicate = IncidentReport::query()
                ->where('idempotency_key', $data['idempotency_key'])
                ->first();

            if (! $duplicate) {
                throw $exception;
            }

            return response()->json([
                ...$this->serializeReport($duplicate),
                ...$this->issueTrackingLink($duplicate),
            ], 200);
        } catch (\Throwable $exception) {
            if ($photoPath) {
                Storage::disk('local')->delete($photoPath);
            }

            throw $exception;
        }

        return response()->json([
            ...$this->serializeReport($report),
            ...$this->trackingLinkPayload($report, $rawTrackingToken),
        ], 200);
    }

    private function issueTrackingLink(IncidentReport $report): array
    {
        $rawTrackingToken = bin2hex(random_bytes(32));

        DB::transaction(function () use ($report, $rawTrackingToken): void {
            $report->trackingTokens()->whereNull('revoked_at')->update(['revoked_at' => now()]);
            $this->storeTrackingToken($report, $rawTrackingToken);
        });

        return $this->trackingLinkPayload($report, $rawTrackingToken);
    }

    private function storeTrackingToken(IncidentReport $report, string $rawTrackingToken): void
    {
        $report->trackingTokens()->create([
            'token_hash' => hash('sha256', $rawTrackingToken),
            'expires_at' => $report->expires_at,
        ]);
    }

    private function trackingLinkPayload(IncidentReport $report, string $rawTrackingToken): array
    {
        return [
            'tracking_token' => $rawTrackingToken,
            'tracking_url' => url('/track?reference=' . urlencode($report->reference_code) . '&token=' . urlencode($rawTrackingToken)),
        ];
    }

    private function serializeReport(IncidentReport $report): array
    {
        return [
            'id' => $report->id,
            'reference_code' => $report->reference_code,
            'status' => $report->status,
            'expires_at' => $report->expires_at?->toISOString(),
        ];
    }

    public function storeFromForm(Request $request): RedirectResponse
    {
        $response = $this->store($request, app(LocaleCatalog::class));
        $payload = $response->getData(true);

        return redirect()->to($payload['tracking_url']);
    }
}
