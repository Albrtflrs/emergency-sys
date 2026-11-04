<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use App\Models\ReportMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffDashboardController extends Controller
{
    public function index(): View
    {
        $reports = IncidentReport::query()
            ->orderByDesc('submitted_at')
            ->get();

        return view('staff.dashboard', [
            'reports' => $reports,
        ]);
    }

    public function poll(): JsonResponse
    {
        $freshReports = IncidentReport::query()
            ->where('created_at', '>=', now()->subMinutes(5))
            ->count();

        $newMessages = ReportMessage::query()
            ->where('created_at', '>=', now()->subMinutes(5))
            ->count();

        return response()->json([
            'new_reports' => $freshReports,
            'new_messages' => $newMessages,
            'updated_at' => now()->toISOString(),
        ]);
    }

    public function triage(Request $request, IncidentReport $report): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['nullable', 'string', 'in:submitted,in_progress,resolved,closed'],
            'priority' => ['nullable', 'string', 'in:low,medium,high,critical'],
            'return_to' => ['nullable', 'string', 'in:staff,admin'],
        ]);

        DB::transaction(function () use ($report, $data, $request): void {
            $statusChanged = array_key_exists('status', $data)
                && $data['status'] !== null
                && $data['status'] !== $report->status;

            if (array_key_exists('status', $data) && $data['status'] !== null) {
                $report->status = $data['status'];
            }

            if (array_key_exists('priority', $data) && $data['priority'] !== null) {
                $report->priority = $data['priority'];
            }

            $report->save();

            if ($statusChanged) {
                $report->statusEvents()->create([
                    'status' => $report->status,
                    'actor_user_id' => $request->user()?->id,
                ]);
            }
        });

        $destination = ($data['return_to'] ?? 'staff') === 'admin' && $request->user()?->role === 'admin'
            ? 'admin.dashboard.queue'
            : 'staff.dashboard';

        return redirect()->route($destination)->with('status', 'Report updated successfully.');
    }
}
