<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AllPagesRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_admin_pages_render(): void
    {
        $user = User::firstOrCreate(
            ['username' => 'rozaq'],
            ['name' => 'Rozaq', 'email' => 'rozaq@tanos.com', 'password' => bcrypt('password'), 'role' => 'Admin']
        );
        $this->actingAs($user);

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
