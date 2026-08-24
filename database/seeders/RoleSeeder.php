<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::updateOrCreate(
            ['role_name' => 'admin'],
            ['permissions' => json_encode(['manage_all'])]
        );

        Role::updateOrCreate(
            ['role_name' => 'editor'],
            ['permissions' => json_encode([
                'create_content',
                'edit_content',
            ])]
        );

        Role::updateOrCreate(
            ['role_name' => 'reviewer'],
            ['permissions' => json_encode([
                'review_content',
                'approve_content',
            ])]
        );
    }
}
