<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
class AttendancesPageRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendances_page_renders_for_hr_manager(): void
    {
        $user = User::firstOrCreate(
            ['username' => 'hrmanager'],
            ['name' => 'HR Manager', 'email' => 'hrmanager@tanos.com', 'password' => bcrypt('password'), 'role' => 'HR Manager']
        );
        $this->actingAs($user)
            ->get('/dashboard/attendances')
            ->assertStatus(200);
    }
}
