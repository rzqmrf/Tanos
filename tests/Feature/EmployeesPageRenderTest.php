<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
class EmployeesPageRenderTest extends TestCase
{
    public function test_employees_page_renders_for_authenticated_user(): void
    {
        $user = User::where('username', 'rozaq')->first();
        $this->withSession(['user' => ['id' => $user->id, 'name' => $user->name, 'username' => $user->username, 'role' => $user->role, 'employee_id' => null]])
            ->get('/dashboard/employees')
            ->assertStatus(200);
    }
}
