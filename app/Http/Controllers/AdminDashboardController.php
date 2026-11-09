<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        if (! Auth::check() || (Auth::user()->role ?? 'staff') !== 'admin') {
            abort(403, 'Admin access required.');
        }

        $today = now()->startOfDay();
        $trendStart = $today->copy()->subDays(6);
        $dailyCounts = IncidentReport::query()
            ->where('submitted_at', '>=', $trendStart)
            ->selectRaw('DATE(submitted_at) as report_date, COUNT(*) as total')
            ->groupBy('report_date')
            ->pluck('total', 'report_date');
        $reportTrend = collect(range(6, 0))->map(function (int $daysAgo) use ($today, $dailyCounts): array {
            $date = $today->copy()->subDays($daysAgo);
            $key = $date->toDateString();

            return [
                'label' => $date->format('D'),
                'date' => $key,
                'count' => (int) ($dailyCounts[$key] ?? 0),
            ];
        });
        $trendMax = max(1, (int) $reportTrend->max('count'));
        $reportTrend = $reportTrend->map(function (array $day) use ($trendMax): array {
            $day['height'] = $day['count'] === 0 ? 4 : max(12, (int) round($day['count'] / $trendMax * 100));

            return $day;
        });
        $statusCounts = IncidentReport::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $reports = IncidentReport::query()
            ->with(['category'])
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();

        return view('admin.dashboard', [
            'reports' => $reports,
            'summary' => [
                'total' => IncidentReport::query()->count(),
                'open' => IncidentReport::query()->whereIn('status', ['submitted', 'in_progress', 'queued'])->count(),
                'resolved' => IncidentReport::query()->where('status', 'resolved')->count(),
                'critical' => IncidentReport::query()->where('priority', 'critical')->count(),
            ],
            'reportTrend' => $reportTrend,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function queue(Request $request): View
    {
        if (! Auth::check() || (Auth::user()->role ?? 'staff') !== 'admin') {
            abort(403, 'Admin access required.');
        }

        $reports = IncidentReport::query()
            ->with(['category'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->orderByDesc('created_at')
            ->get();

        return view('admin.queue', [
            'reports' => $reports,
            'status' => $request->status,
        ]);
    }
}
