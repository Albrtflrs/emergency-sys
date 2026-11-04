<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'report.description_min_length'   => 20,
            'report.description_max_length'   => 2000,
            'report.reporter_name_max_length' => 120,
            'report.max_images'               => 2,
            'report.image_max_bytes'          => 10 * 1024 * 1024,
            'report.image_max_dimension'      => 2000,
            'report.image_target_bytes'       => 500 * 1024,
            'report.expiry_days'              => 7,
            'report.escalation_minutes'       => 10,
            'duplicate.radius_meters'         => 200,
            'duplicate.window_minutes'        => 30,
            'notifications.email_to'          => 'duty@example.gov.ph',
            'notifications.channels'          => ['email', 'dashboard'],
            'rate.submit_per_hour'            => 10,
            'rate.track_per_hour'             => 120,
            'rate.message_per_hour'           => 30,
            'evidence.signed_url_ttl_seconds' => 300,
        ];

        foreach ($defaults as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => json_encode($value), 'updated_at' => now()]
            );
        }
    }
}