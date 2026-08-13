<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /*
    When inserting through Eloquent (the Role::create() method), you need to convert a PHP array into an actual JSON string before it gets stored
    json_encode() does that conversion (makes it a string)
     */
    public function run(): void
    {
        Role::create([
            'role_name' => 'admin', // already a string, no need to encode it
            'permissions' => json_encode(['manage_all']),
        ]);

        Role::create([
            'role_name' => 'editor',
            'permissions' => json_encode(['create_content', 'edit_content']),
        ]);

        Role::create([
            'role_name' => 'reviewer',
            'permissions' => json_encode(['review_content', 'approve_content']),
        ]);
    }
}
