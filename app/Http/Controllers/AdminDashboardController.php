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
