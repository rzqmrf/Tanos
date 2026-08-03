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

        $response = $this->withSession(['user' => $user])->get(route('copilot.index'));

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

        $response = $this->withSession(['user' => $user])
            ->postJson(route('copilot.chat'), [
                'message' => 'Tampilkan data proyek Pelindo',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['response']);
    }
}
