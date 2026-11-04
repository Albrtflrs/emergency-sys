<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        $configured = 0;

        foreach (config('seeding.users', []) as $role => $account) {
            $email = trim((string) ($account['email'] ?? ''));
            $password = (string) ($account['password'] ?? '');

            if ($email === '' && $password === '') {
                continue;
            }

            if (! filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 12) {
                throw new RuntimeException(sprintf(
                    'Configure SEED_%s_EMAIL as a valid email and SEED_%s_PASSWORD with at least 12 characters.',
                    Str::upper($role),
                    Str::upper($role),
                ));
            }

            User::query()->updateOrCreate(
                ['email' => $email],
                [
                    'name' => $account['name'] ?: 'Seeded ' . ucfirst($role),
                    'password' => Hash::make($password),
                    'role' => $role,
                ],
            );

            $configured++;
        }

        if ($configured === 0) {
            $this->command?->warn('No seeded staff/admin accounts configured; set SEED_ADMIN_* and/or SEED_STAFF_* in .env.');
        } else {
            $this->command?->info("Seeded or updated {$configured} role account(s) from environment configuration.");
        }
    }
}
