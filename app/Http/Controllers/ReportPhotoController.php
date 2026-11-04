<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use App\Models\ReportTrackingToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportPhotoController extends Controller
{
    public function forReporter(string $token): BinaryFileResponse
    {
        $trackingToken = ReportTrackingToken::query()
            ->with('report')
            ->where('token_hash', hash('sha256', $token))
            ->first();

        abort_unless(
            $trackingToken
            && ! $trackingToken->revoked_at
            && (! $trackingToken->expires_at || ! $trackingToken->expires_at->isPast())
            && $trackingToken->report?->photo_path,
            404
        );

        return $this->fileResponse($trackingToken->report->photo_path);
    }

    public function forStaff(Request $request, IncidentReport $report): BinaryFileResponse
    {
        abort_unless(in_array($request->user()?->role, ['staff', 'admin'], true) && $report->photo_path, 404);

        return $this->fileResponse($report->photo_path);
    }

    private function fileResponse(string $path): BinaryFileResponse
    {
        $disk = Storage::disk('local');
        abort_unless($disk->exists($path), 404);

        $absolutePath = $disk->path($path);

        return response()->file($absolutePath, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'no-referrer',
        ]);
    }
}
