<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;

class AllPagesEmployeeTest extends TestCase
{
    public function test_employee_pages_render(): void
    {
        $emp = \App\Models\Employee::first();
        $user = User::where('employee_id', $emp->id)->first();
        $this->withSession(['user' => ['id' => $user->id, 'name' => $user->name, 'username' => $user->username, 'role' => $user->role, 'employee_id' => $emp->id]]);

        $routes = ['/', '/dashboard/ess', '/dashboard/attendances', '/dashboard/copilot', '/notifications'];
        $failed = [];
        foreach ($routes as $route) {
            try {
                $resp = $this->get($route);
                if (!in_array($resp->status(), [200, 302, 404, 403])) {
                    $failed[] = "$route => {$resp->status()}";
                }
            } catch (\Throwable $e) {
                $failed[] = "$route => EXCEPTION: " . substr($e->getMessage(), 0, 80);
            }
        }
        $this->assertTrue(empty($failed), "Failed routes:\n" . implode("\n", $failed));
    }
}
