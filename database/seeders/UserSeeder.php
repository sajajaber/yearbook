<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;        

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        firstOrCreate() takes two arrays:
        <=> First array: the "search criteria."
        Laravel checks: does a row already exist matching this? (here, email = 'admin@example.com')
        <=> Second array: the extra fields to use only if it needs to create a new row

        ======================

        if a row with that email already exists, nothing happens (no error, no duplicate), Laravel just finds and returns the existing one. If it doesn't exist, it creates it using both arrays combined.

         */
        
        $adminRole = Role::where('role_name', 'admin')->first();

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'password' => 'admin',
                'role_id' => $adminRole->id,
                'status' => 'active',
            ]
        );

        $editorRole = Role::where('role_name', 'editor')->first();

        User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'editor',
                'password' => 'editor',
                'role_id' => $editorRole->id,
                'status' => 'active',
            ]
        );

        $reviewerRole = Role::where('role_name', 'reviewer')->first();
    
        User::firstOrCreate(
            ['email' => 'reviewer@example.com'],
            [
                'name' => 'reviewer',
                'password' => 'reviewer',
                'role_id' => $reviewerRole->id,
                'status' => 'active',
            ]
        );
    }

}
