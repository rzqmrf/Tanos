<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;

class AllPagesRenderTest extends TestCase
{
    public function test_all_admin_pages_render(): void
    {
        $user = User::where('username', 'rozaq')->first();
        $this->withSession(['user' => ['id' => $user->id, 'name' => $user->name, 'username' => $user->username, 'role' => $user->role, 'employee_id' => null]]);

        $routes = [
            '/', '/dashboard/employees', '/dashboard/attendances', '/dashboard/projects',
            '/dashboard/invoices', '/dashboard/copilot', '/dashboard/users',
            '/dashboard/project-config', '/dashboard/access-controls',
            '/dashboard/rab-budgets', '/dashboard/org-structure/sto',
            '/dashboard/org-structure/job', '/dashboard/org-structure/ecn',
            '/dashboard/payrolls', '/dashboard/reports', '/dashboard/billing',
            '/dashboard/ess', '/notifications',
        ];

        $failed = [];
        foreach ($routes as $route) {
            try {
                $resp = $this->get($route);
                if (!in_array($resp->status(), [200, 302, 404])) {
                    $failed[] = "$route => {$resp->status()}";
                }
            } catch (\Throwable $e) {
                $failed[] = "$route => EXCEPTION: " . substr($e->getMessage(), 0, 80);
            }
        }

        $this->assertTrue(empty($failed), "Failed routes:\n" . implode("\n", $failed));
    }
}
