<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
class AttendancesPageRenderTest extends TestCase
{
    public function test_attendances_page_renders_for_hr_manager(): void
    {
        $user = User::where('username', 'hrmanager')->first();
        $this->withSession(['user' => ['id' => $user->id, 'name' => $user->name, 'username' => $user->username, 'role' => $user->role, 'employee_id' => null]])
            ->get('/dashboard/attendances')
            ->assertStatus(200);
    }
}
