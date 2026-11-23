<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_access_user_management_page(): void
    {
        $superadmin = User::factory()->create([
            'nama' => 'Super Admin',
            'email' => 'superadmin@monitoring.com',
            'role' => 'superadmin',
        ]);

        $response = $this->actingAs($superadmin)->get('/users');

        $response->assertStatus(200);
        $response->assertSee('Kelola User');
        $response->assertSee('Super Admin');
    }

    public function test_non_superadmin_cannot_access_user_management_page(): void
    {
        $user = User::factory()->create([
            'nama' => 'Admin',
            'email' => 'admin@monitoring.com',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(403);
    }
}
