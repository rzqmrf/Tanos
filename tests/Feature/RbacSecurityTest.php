<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Employee;
use App\Models\Project;
use App\Models\RabBudget;
use App\Models\Division;
use App\Models\PayrollPeriod;
use App\Models\PranotaBilling;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $employeeUser;
    protected $hrManager;
    protected $financeManager;
    protected $projectManager;
    protected $project;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard roles users
        $this->admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin_user',
            'email' => 'admin@tanos.com',
            'password' => bcrypt('password'),
            'role' => 'Admin'
        ]);

        $this->employeeUser = User::create([
            'name' => 'Employee User',
            'username' => 'employee_user',
            'email' => 'employee@tanos.com',
            'password' => bcrypt('password'),
            'role' => 'Employee'
        ]);

        $this->hrManager = User::create([
            'name' => 'HR Manager User',
            'username' => 'hr_manager',
            'email' => 'hr@tanos.com',
            'password' => bcrypt('password'),
            'role' => 'HR Manager'
        ]);

        $this->financeManager = User::create([
            'name' => 'Finance Manager User',
            'username' => 'finance_manager',
            'email' => 'finance@tanos.com',
            'password' => bcrypt('password'),
            'role' => 'Finance Manager'
        ]);

        $this->projectManager = User::create([
            'name' => 'Project Manager User',
            'username' => 'project_manager',
            'email' => 'pm@tanos.com',
            'password' => bcrypt('password'),
            'role' => 'Project Manager'
        ]);

        // Setup a test project
        $this->project = Project::create([
            'project_code' => 'PRJ-TEST',
            'project_name' => 'Test Project',
            'customer_name' => 'Customer',
            'contract_number' => 'CONTRACT-001',
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-31',
            'cost_center' => 'CC-001',
            'fund_center' => 'FC-001',
            'month' => 'Agustus 2026',
            'regional' => 'Regional 1',
            'segment' => 'Enterprise',
            'cost' => 1000000.00,
            'active' => true
        ]);
    }

    /**
     * Test HR Management (Employees, Org Structure, Time Management) protection
     */
    public function test_employee_and_org_structure_protected_from_regular_employees()
    {
        // Try to add employee as regular Employee user -> 403 Forbidden
        $response = $this->actingAs($this->employeeUser)
            ->post(route('employees.store'), [
                'name' => 'New Kid',
                'role' => 'Staff',
                'month' => 'Agustus 2026',
                'nipp' => 'NIPP-999',
            ]);
        $response->assertStatus(403);

        // Try to add unit as regular Employee user -> 403 Forbidden
        $response = $this->actingAs($this->employeeUser)
            ->post(route('org.sto.store'), [
                'code' => 'DEPT-X',
                'name' => 'Secret Dept',
                'unit_type' => 'Department'
            ]);
        $response->assertStatus(403);

        // Try to create absent type as regular Employee user -> 403 Forbidden
        $response = $this->actingAs($this->employeeUser)
            ->post(route('org.absent-types.store'), [
                'code' => 'XYZ',
                'name' => 'Invalid Absent Type',
                'gender' => 'All',
                'priority_level' => 2,
                'deduction_absent' => 'No',
            ]);
        $response->assertStatus(403);
    }

    public function test_hr_manager_can_access_hr_management_actions()
    {
        // HR Manager can add employee -> redirects (302) or created
        $response = $this->actingAs($this->hrManager)
            ->post(route('employees.store'), [
                'name' => 'New Staff',
                'role' => 'Staff Operasional',
                'month' => 'Agustus 2026',
                'regional' => 'Regional 1',
                'segment' => 'Enterprise',
                'nipp' => 'NIPP-111',
                'bank_name' => 'Mandiri',
                'bank_account_number' => '123',
                'bank_account_name' => 'New Staff',
                'ptkp_status' => 'TK/0',
                'tmt_date' => '2024-01-01',
                'bpjs_kesehatan_number' => '999',
                'bpjs_ketenagakerjaan_number' => '888',
                'project_id' => $this->project->id
            ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('employees', ['nipp' => 'NIPP-111']);
    }

    /**
     * Test Payroll & Billing protection
     */
    public function test_payroll_and_billing_protected_from_non_finance_and_non_admin()
    {
        // Setup payroll period
        $period = PayrollPeriod::create([
            'name' => 'Agustus 2026',
            'month' => 'Agustus 2026',
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-31',
            'status' => 'Completed',
            'project_id' => $this->project->id
        ]);

        // Regular Employee cannot calculate payroll -> 403
        $response = $this->actingAs($this->employeeUser)
            ->post(route('payrolls.calculate', $period->id), ['action' => 'Payroll']);
        $response->assertStatus(403);

        // HR Manager cannot calculate payroll -> 403
        $response = $this->actingAs($this->hrManager)
            ->post(route('payrolls.calculate', $period->id), ['action' => 'Payroll']);
        $response->assertStatus(403);

        // Finance Manager CAN calculate payroll -> 302 redirect
        $response = $this->actingAs($this->financeManager)
            ->post(route('payrolls.calculate', $period->id), ['action' => 'Payroll']);
        $response->assertStatus(302);
    }

    /**
     * Test WBS & RAB Projects protection
     */
    public function test_wbs_and_rab_budget_protected_from_regular_employees()
    {
        // Try to add WBS element as Employee user -> 403
        $response = $this->actingAs($this->employeeUser)
            ->post(route('projects.wbs.store', $this->project->id), [
                'wbs_code' => 'WBS-XYZ',
                'wbs_name' => 'Hack Attempt',
                'wbs_category' => 'Main Work',
                'weight' => 10,
            ]);
        $response->assertStatus(403);

        // Try to add WBS element as Project Manager -> 302 success redirect
        $response = $this->actingAs($this->projectManager)
            ->post(route('projects.wbs.store', $this->project->id), [
                'wbs_code' => 'WBS-XYZ',
                'wbs_name' => 'Correct Wbs',
                'wbs_category' => 'Main Work',
                'weight' => 10,
            ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('wbs_elements', ['wbs_code' => 'WBS-XYZ']);
    }
}
