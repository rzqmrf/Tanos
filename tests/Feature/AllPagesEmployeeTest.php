<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AllPagesEmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_pages_render(): void
    {
        $emp = \App\Models\Employee::create([
            'name' => 'Employee Test',
            'role' => 'Staff Operasional',
            'month' => 'Agustus 2026',
            'regional' => 'Regional 1',
            'segment' => 'Enterprise',
        ]);
        $user = User::create([
            'name' => 'Employee Test',
            'username' => 'emp_test',
            'email' => 'emp@tanos.com',
            'password' => bcrypt('password'),
            'role' => 'Employee',
            'employee_id' => $emp->id,
        ]);
        $this->actingAs($user);

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
