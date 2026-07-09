<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test that root redirects to login when not authenticated.
     */
    public function test_the_application_redirects_unauthenticated_user_to_login(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Test that the login page returns a successful response.
     */
    public function test_login_page_returns_successful_response(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}
