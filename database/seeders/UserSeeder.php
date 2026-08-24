<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('role_name', 'admin')->firstOrFail();
        $editorRole = Role::where('role_name', 'editor')->firstOrFail();
        $reviewerRole = Role::where('role_name', 'reviewer')->firstOrFail();

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin'),
                'role_id' => $adminRole->id,
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor',
                'password' => Hash::make('editor'),
                'role_id' => $editorRole->id,
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'reviewer@example.com'],
            [
                'name' => 'Reviewer',
                'password' => Hash::make('reviewer'),
                'role_id' => $reviewerRole->id,
                'status' => 'active',
            ]
        );
    }
}
