<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('essence.super_admin.email');
        $password = config('essence.super_admin.password');

        if (! $email || ! $password || strlen($password) < 12) {
            throw new RuntimeException('Set SUPER_ADMIN_EMAIL and a 12+ character SUPER_ADMIN_PASSWORD before seeding.');
        }

        $existing = User::where('email', $email)->first();

        if ($existing && ! $existing->isSuperAdmin()) {
            throw new RuntimeException('The configured super admin email already belongs to another account.');
        }

        User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Essence Studio Owner',
                'password' => $password,
                'role' => 'super_admin',
            ],
        );
    }
}
