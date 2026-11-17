<?php

namespace Tests\Feature;

use App\Models\ActionLog;
use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoSeeder::class);
    }

    public function test_superadmin_can_login_and_is_redirected_to_superadmin_dashboard(): void
    {
        $response = $this->post('/login', [
            'username' => 'guruhhhh',
            'password' => 'superadmin123',
        ]);

        $response->assertRedirect(route('superadmin.dashboard'));
        $this->assertAuthenticated();

        // Verify action log was recorded for login
        $this->assertDatabaseHas('action_logs', [
            'aktivitas' => 'Masuk ke sistem',
            'modul' => 'Autentikasi',
            'status' => 'Berhasil',
        ]);
    }

    public function test_superadmin_dashboard_renders_blade_view_for_web_requests(): void
    {
        $superadmin = User::where('username', 'guruhhhh')->first();

        $response = $this->actingAs($superadmin)->get('/superadmin/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('super_admin.dashboard');
        $response->assertSee('Dashboard super admin');
        $response->assertSee('Total akun');
    }

    public function test_superadmin_dashboard_returns_json_when_requested_with_json_header(): void
    {
        $superadmin = User::where('username', 'guruhhhh')->first();

        $response = $this->actingAs($superadmin)->getJson('/superadmin/dashboard');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'users' => ['total', 'active', 'inactive', 'customer', 'admin', 'superadmin'],
            'activities',
            'transactions',
            'payments',
        ]);
    }

    public function test_superadmin_can_view_buat_akun_page(): void
    {
        $superadmin = User::where('username', 'guruhhhh')->first();

        $response = $this->actingAs($superadmin)->get('/superadmin/buat-akun');

        $response->assertStatus(200);
        $response->assertViewIs('super_admin.buat_akun');
        $response->assertSee('Buat akun');
    }

    public function test_superadmin_can_create_new_account_and_logs_action(): void
    {
        $superadmin = User::where('username', 'guruhhhh')->first();

        $response = $this->actingAs($superadmin)->post('/superadmin/users', [
            'nama_lengkap' => 'Staff Gudang',
            'email' => 'gudang@authensix.com',
            'username' => 'staffgudang',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
            'status' => 'active',
            'must_change_password' => 1,
        ]);

        $response->assertRedirect(route('superadmin.dashboard'));

        $this->assertDatabaseHas('users', [
            'username' => 'staffgudang',
            'email' => 'gudang@authensix.com',
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => true,
        ]);

        $this->assertDatabaseHas('action_logs', [
            'aktivitas' => 'Membuat akun',
            'modul' => 'Akun',
            'status' => 'Berhasil',
        ]);
    }

    public function test_superadmin_can_view_action_log_page(): void
    {
        $superadmin = User::where('username', 'guruhhhh')->first();

        ActionLog::record($superadmin, 'Pengujian Action Log', 'Sistem', 'Testing unit', 'Berhasil');

        $response = $this->actingAs($superadmin)->get('/superadmin/action-log');

        $response->assertStatus(200);
        $response->assertViewIs('super_admin.action_log');
        $response->assertSee('Riwayat aktivitas');
        $response->assertSee('Pengujian Action Log');
    }

    public function test_superadmin_can_export_action_log_csv(): void
    {
        $superadmin = User::where('username', 'guruhhhh')->first();

        ActionLog::record($superadmin, 'Export testing', 'Sistem', 'Detail testing', 'Berhasil');

        $response = $this->actingAs($superadmin)->get('/superadmin/action-logs/export');

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
    }

    public function test_regular_customer_cannot_access_superadmin_dashboard(): void
    {
        $customer = User::where('role', 'customer')->first();

        $response = $this->actingAs($customer)->get('/superadmin/dashboard');

        $response->assertStatus(403);
    }
}
