<?php

$localSeedDefaults = in_array(env('APP_ENV', 'production'), ['local', 'testing'], true);

return [
    'users' => [
        'admin' => [
            'name' => env('SEED_ADMIN_NAME', 'Seeded Administrator'),
            'email' => env('SEED_ADMIN_EMAIL', $localSeedDefaults ? 'manual.admin@example.test' : null),
            'password' => env('SEED_ADMIN_PASSWORD', $localSeedDefaults ? 'ManualTest2026!' : null),
        ],
        'staff' => [
            'name' => env('SEED_STAFF_NAME', 'Seeded Staff'),
            'email' => env('SEED_STAFF_EMAIL', $localSeedDefaults ? 'manual.staff@example.test' : null),
            'password' => env('SEED_STAFF_PASSWORD', $localSeedDefaults ? 'ManualTest2026!' : null),
        ],
    ],
];
