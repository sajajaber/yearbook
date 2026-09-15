<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function auditTestUser(string $roleName): User
{
    $role = Role::firstOrCreate(['role_name' => $roleName]);

    return User::factory()->create([
        'role_id' => $role->id,
        'status' => 'active',
    ]);
}

test('admins can access the audit log', function () {
    $admin = auditTestUser('admin');

    $this->actingAs($admin)
        ->get(route('audit-logs.index'))
        ->assertOk()
        ->assertSee('Audit Log')
        ->assertSee('Admin only');
});

test('editors cannot access the audit log', function () {
    $editor = auditTestUser('editor');

    $this->actingAs($editor)
        ->get(route('audit-logs.index'))
        ->assertForbidden();
});

test('reviewers cannot access the audit log', function () {
    $reviewer = auditTestUser('reviewer');

    $this->actingAs($reviewer)
        ->get(route('audit-logs.index'))
        ->assertForbidden();
});

test('guests cannot access the audit log', function () {
    $this->get(route('audit-logs.index'))
        ->assertRedirect();
});
