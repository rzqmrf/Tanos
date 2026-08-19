<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
class EmployeesPageRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_employees_page_renders_for_authenticated_user(): void
    {
        $user = User::firstOrCreate(
            ['username' => 'rozaq'],
            ['name' => 'Rozaq', 'email' => 'rozaq@tanos.com', 'password' => bcrypt('password'), 'role' => 'Admin']
        );
        $this->actingAs($user)
            ->get('/dashboard/employees')
            ->assertStatus(200);
    }
}
