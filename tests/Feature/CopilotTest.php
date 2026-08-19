<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CopilotTest extends TestCase
{
    /**
     * Test that an unauthenticated user is redirected to login.
     */
    public function test_copilot_page_redirects_unauthenticated_user(): void
    {
        $response = $this->get(route('copilot.index'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Test that an authenticated user can access the Copilot page.
     */
    public function test_copilot_page_accessible_by_authenticated_user(): void
    {
        // Session auth is used, or let's mock the session user
        $user = User::factory()->make([
            'username' => 'testuser',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get(route('copilot.index'));

        $response->assertStatus(200);
        $response->assertSee('Tanos AI Copilot');
    }

    /**
     * Test that the chat endpoint returns a successful JSON response.
     */
    public function test_copilot_chat_returns_json_response(): void
    {
        $user = User::factory()->make([
            'username' => 'testuser',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('copilot.chat'), [
                'message' => 'Tampilkan data proyek Pelindo',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['response']);
    }

    /**
     * Test that an admin can successfully change permissions via chat command.
     */
    public function test_copilot_chat_allows_admin_to_change_permissions(): void
    {
        $user = User::factory()->make([
            'username' => 'adminuser',
            'role' => 'Admin',
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('copilot.chat'), [
                'message' => 'aktifkan akses payroll untuk HR Manager',
            ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'response' => "✅ **Pembaruan Hak Akses Diterapkan!**\n\nHak akses modul **'payroll'** untuk peran **'HR Manager'** telah BERHASIL DIAKTIFKAN ✅.\n\n*Pengguna dengan peran HR Manager akan langsung melihat perubahan visibilitas menu di sidebar setelah halaman di-refresh.*"
        ]);

        // Pastikan terupdate di database
        $this->assertDatabaseHas('role_permissions', [
            'role' => 'HR Manager',
            'permission' => 'payroll',
            'is_enabled' => true,
        ]);
    }

    /**
     * Test that a non-admin is blocked from changing permissions via chat command.
     */
    public function test_copilot_chat_blocks_non_admin_from_changing_permissions(): void
    {
        $user = User::factory()->make([
            'username' => 'employeeuser',
            'role' => 'Employee',
        ]);

        // Set awal database to false
        \App\Models\RolePermission::updateOrCreate(
            ['role' => 'HR Manager', 'permission' => 'payroll'],
            ['is_enabled' => false]
        );

        $response = $this->actingAs($user)
            ->postJson(route('copilot.chat'), [
                'message' => 'aktifkan akses payroll untuk HR Manager',
            ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'response' => "❌ **Akses Ditolak!** Anda tidak memiliki izin untuk memodifikasi konfigurasi hak akses modul."
        ]);

        // Pastikan database tidak berubah
        $this->assertDatabaseHas('role_permissions', [
            'role' => 'HR Manager',
            'permission' => 'payroll',
            'is_enabled' => false,
        ]);
    }

    /**
     * Test that role/permission modifications are validated against whitelist.
     */
    public function test_copilot_chat_validates_whitelist_permissions_and_roles(): void
    {
        $user = User::factory()->make([
            'username' => 'adminuser',
            'role' => 'Admin',
        ]);

        // Mock Gemini API karena perintah yang tidak dikenal di mapping lokal akan diteruskan ke Gemini
        \Illuminate\Support\Facades\Http::fake([
            'generativelanguage.googleapis.com/*' => \Illuminate\Support\Facades\Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Maaf, saya tidak bisa melakukan tindakan tersebut.']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('copilot.chat'), [
                'message' => 'aktifkan akses root_database untuk SuperUser',
            ]);

        $response->assertStatus(200);
        // Karena tidak terdeteksi di mapping lokal, ia diteruskan ke Gemini dan mengembalikan jawaban Gemini
        $response->assertJsonFragment([
            'response' => 'Maaf, saya tidak bisa melakukan tindakan tersebut.'
        ]);

        // Pastikan tidak masuk ke database
        $this->assertDatabaseMissing('role_permissions', [
            'role' => 'SuperUser',
            'permission' => 'root_database',
        ]);
    }

    /**
     * Test that action tags in AI response are stripped and blocked if user is not admin.
     */
    public function test_copilot_chat_strips_action_tags_for_non_admin(): void
    {
        $user = User::factory()->make([
            'username' => 'employeeuser',
            'role' => 'Employee',
        ]);

        // Mock Gemini API Response
        \Illuminate\Support\Facades\Http::fake([
            'generativelanguage.googleapis.com/*' => \Illuminate\Support\Facades\Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Ini jawaban AI. [ACTION_PERM:HR Manager:payroll:true]']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        // Set awal database to false
        \App\Models\RolePermission::updateOrCreate(
            ['role' => 'HR Manager', 'permission' => 'payroll'],
            ['is_enabled' => false]
        );

        $response = $this->actingAs($user)
            ->postJson(route('copilot.chat'), [
                'message' => 'Tampilkan data payroll',
            ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'response' => "Ini jawaban AI.\n\n*(Sistem menolak perubahan otomatis hak akses karena Anda bukan pengguna Admin)*"
        ]);

        // Pastikan database tidak berubah
        $this->assertDatabaseHas('role_permissions', [
            'role' => 'HR Manager',
            'permission' => 'payroll',
            'is_enabled' => false,
        ]);
    }

    /**
     * Test that action tags in AI response are executed and processed if user is admin.
     */
    public function test_copilot_chat_processes_action_tags_for_admin(): void
    {
        $user = User::factory()->make([
            'username' => 'adminuser',
            'role' => 'Admin',
        ]);

        // Mock Gemini API Response
        \Illuminate\Support\Facades\Http::fake([
            'generativelanguage.googleapis.com/*' => \Illuminate\Support\Facades\Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Ini jawaban AI. [ACTION_PERM:HR Manager:payroll:true]']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        // Set awal database to false
        \App\Models\RolePermission::updateOrCreate(
            ['role' => 'HR Manager', 'permission' => 'payroll'],
            ['is_enabled' => false]
        );

        $response = $this->actingAs($user)
            ->postJson(route('copilot.chat'), [
                'message' => 'Tampilkan data payroll',
            ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'response' => "Ini jawaban AI.\n\n*(Sistem telah otomatis memperbarui database: Akses 'payroll' untuk peran 'HR Manager' telah diaktifkan)*"
        ]);

        // Pastikan database terupdate
        $this->assertDatabaseHas('role_permissions', [
            'role' => 'HR Manager',
            'permission' => 'payroll',
            'is_enabled' => true,
        ]);
    }

    /**
     * Test that action tags validation against whitelist works for admin.
     */
    public function test_copilot_chat_validates_action_tags_whitelist_for_admin(): void
    {
        $user = User::factory()->make([
            'username' => 'adminuser',
            'role' => 'Admin',
        ]);

        // Mock Gemini API Response
        \Illuminate\Support\Facades\Http::fake([
            'generativelanguage.googleapis.com/*' => \Illuminate\Support\Facades\Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Ini jawaban AI. [ACTION_PERM:SuperUser:root_database:true]']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('copilot.chat'), [
                'message' => 'Tampilkan data root',
            ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'response' => "Ini jawaban AI.\n\n*(Sistem menolak perubahan otomatis hak akses: peran 'SuperUser' atau modul 'root_database' tidak terdaftar)*"
        ]);

        // Pastikan tidak ada data tak terdaftar di database
        $this->assertDatabaseMissing('role_permissions', [
            'role' => 'SuperUser',
            'permission' => 'root_database',
        ]);
    }
}
