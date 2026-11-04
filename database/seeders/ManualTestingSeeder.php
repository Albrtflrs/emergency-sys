<?php

namespace Database\Seeders;

use App\Models\IncidentCategory;
use App\Models\IncidentReport;
use App\Models\ReportMessage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class ManualTestingSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new RuntimeException('Manual test data can only be seeded in local or testing environments.');
        }

        $password = 'ManualTest2026!';

        User::query()->updateOrCreate(
            ['email' => 'manual.staff@example.test'],
            ['name' => 'Manual Test Staff', 'password' => Hash::make($password), 'role' => 'staff']
        );

        $admin = User::query()->updateOrCreate(
            ['email' => 'manual.admin@example.test'],
            ['name' => 'Manual Test Admin', 'password' => Hash::make($password), 'role' => 'admin']
        );

        $category = IncidentCategory::query()->active()->first();
        if (! $category) {
            throw new RuntimeException('No active incident categories found. Run DatabaseSeeder first.');
        }

        $report = IncidentReport::query()->updateOrCreate(
            ['idempotency_key' => '00000000-0000-4000-8000-000000000001'],
            [
                'category_id' => $category->id,
                'reference_code' => 'MDR-' . now()->format('Y') . '-TEST01',
                'description' => 'Manual test report. No real emergency. Use this record to try triage and reporter messaging.',
                'location_text' => 'Test location, Barangay Demo',
                'reporter_name' => 'Sample Reporter',
                'contact_number' => '+639171234567',
                'reporting_for_other' => false,
                'language' => 'en',
                'priority' => 'medium',
                'status' => 'submitted',
                'suspicion_flag' => false,
                'submitted_at' => now(),
                'expires_at' => now()->addDays(7),
            ]
        );

        $trackingToken = 'manual-testing-token-2026';
        $tokenRecord = $report->trackingTokens()->first();
        if ($tokenRecord) {
            $tokenRecord->forceFill([
                'token_hash' => hash('sha256', $trackingToken),
                'expires_at' => now()->addDays(7),
                'revoked_at' => null,
            ])->save();
        } else {
            $report->trackingTokens()->create([
                'token_hash' => hash('sha256', $trackingToken),
                'expires_at' => now()->addDays(7),
            ]);
        }

        ReportMessage::query()->firstOrCreate(
            ['report_id' => $report->id, 'body' => 'This is a sample message for manual testing.'],
            ['sender_type' => 'staff', 'staff_actor_id' => $admin->id, 'closed_thread' => false]
        );

        $this->command?->newLine();
        $this->command?->info('Local manual test accounts are ready (password: ' . $password . ')');
        $this->command?->line('Staff: manual.staff@example.test');
        $this->command?->line('Admin: manual.admin@example.test');
        $this->command?->line('Sample report: ' . $report->reference_code);
        $this->command?->line('Tracking link: http://127.0.0.1:8765/track?reference=' . urlencode($report->reference_code) . '&token=' . urlencode($trackingToken));
        $this->command?->newLine();
    }
}
