<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\WbsElement;
use App\Models\PayrollPeriod;
use App\Models\PayrollComponent;
use App\Models\PayrollResult;
use App\Models\PranotaBilling;
use App\Models\NotaBilling;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PayrollTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        return User::create([
            'name' => 'Admin Rozaq',
            'username' => 'admin_rozaq',
            'email' => 'rozaq@example.com',
            'password' => bcrypt('password'),
            'role' => 'Admin',
        ]);
    }

    private function setupProjectAndEmployee(): array
    {
        $project = Project::create([
            'project_code' => 'S/PS-2026-0819',
            'project_name' => 'Jasa Keamanan Perak',
            'customer_name' => 'PT Pelindo Terminal Petikemas',
            'contract_number' => 'OA-2026-1002',
            'start_date' => '2026-08-01',
            'end_date' => '2027-07-31',
            'cost_center' => 'CC-9901',
            'fund_center' => 'FC-9901',
            'month' => 'Agustus 2026',
            'regional' => 'Jawa Timur',
            'segment' => 'Enterprise',
            'cost' => 50000000,
            'active' => 1
        ]);

        $employee = Employee::create([
            'name' => 'Leo Rajata',
            'role' => 'Security',
            'month' => 'Agustus 2026',
            'regional' => 'Jawa Timur',
            'sub_regional' => 'Surabaya',
            'segment' => 'Enterprise'
        ]);

        // Add 5 attendance days
        for ($i = 1; $i <= 5; $i++) {
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => Carbon::parse("2026-08-0$i"),
                'status' => 'Hadir',
                'clock_in' => '08:00:00',
                'clock_out' => '17:00:00',
                'overtime_hours' => 2.0 // 2 hours overtime per day
            ]);
        }

        // Add WBS elements
        $wbs = WbsElement::create([
            'project_id' => $project->id,
            'wbs_code' => 'WBS-ENT-001',
            'wbs_name' => 'Penyediaan Security',
            'wbs_category' => 'Upah Pokok',
            'weight' => 100,
            'expected_start' => '2026-08-01',
            'expected_end' => '2026-08-31',
        ]);

        return compact('project', 'employee', 'wbs');
    }

    public function test_can_create_payroll_period_and_components(): void
    {
        $admin = $this->createAdminUser();
        $data = $this->setupProjectAndEmployee();

        $response = $this->withSession(['user' => $admin])
            ->post(route('payrolls.store'), [
                'project_id' => $data['project']->id,
                'name' => 'Gaji Security Perak Ags 2026',
                'type' => 'On-Cycle',
                'month' => 'Agustus 2026',
                'start_date' => '2026-08-01',
                'end_date' => '2026-08-31',
            ]);

        $this->assertDatabaseHas('payroll_periods', [
            'name' => 'Gaji Security Perak Ags 2026',
            'status' => 'Draft'
        ]);

        // Check if default components were created
        $period = PayrollPeriod::where('name', 'Gaji Security Perak Ags 2026')->first();
        $this->assertCount(6, $period->components);
    }

    public function test_can_calculate_payroll_results(): void
    {
        $admin = $this->createAdminUser();
        $data = $this->setupProjectAndEmployee();

        $period = PayrollPeriod::create([
            'project_id' => $data['project']->id,
            'name' => 'Gaji Security Perak Ags 2026',
            'type' => 'On-Cycle',
            'month' => 'Agustus 2026',
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-31',
        ]);

        // Create basic payroll components
        PayrollComponent::create([
            'payroll_period_id' => $period->id,
            'wbs_element_id' => $data['wbs']->id,
            'code' => 'W001',
            'name' => 'Upah Pokok',
            'type' => 'Valuation',
            'amount' => 4000000,
        ]);
        PayrollComponent::create([
            'payroll_period_id' => $period->id,
            'wbs_element_id' => $data['wbs']->id,
            'code' => 'W002',
            'name' => 'Uang Transport',
            'type' => 'Formula',
            'amount' => 20000,
            'formula_expression' => '{days_present} * {amount}',
        ]);
        PayrollComponent::create([
            'payroll_period_id' => $period->id,
            'wbs_element_id' => $data['wbs']->id,
            'code' => 'W004',
            'name' => 'Uang Lembur',
            'type' => 'Formula',
            'amount' => 30000,
            'formula_expression' => '{overtime_hours} * {amount}',
        ]);

        // Calculate Simulation
        $response = $this->withSession(['user' => $admin])
            ->post(route('payrolls.calculate', $period->id), ['action' => 'Simulation']);

        $response->assertRedirect();
        
        $this->assertEquals('Simulated', $period->fresh()->status);

        // Calculate Real Payroll
        $response = $this->withSession(['user' => $admin])
            ->post(route('payrolls.calculate', $period->id), ['action' => 'Payroll']);

        $this->assertEquals('Completed', $period->fresh()->status);

        // Assert calculated salary result:
        // Basic: 4.000.000
        // Transport: 5 days * 20.000 = 100.000
        // Overtime: 10 hours * 30.000 = 300.000
        // Net: 4.400.000
        $this->assertDatabaseHas('payroll_results', [
            'employee_id' => $data['employee']->id,
            'days_present' => 5,
            'overtime_hours' => 10.00,
            'basic_salary' => 4000000,
            'transport_allowance' => 100000,
            'overtime_pay' => 300000,
            'net_salary' => 4400000,
        ]);
    }

    public function test_can_post_payroll_to_sap_and_trigger_pranota(): void
    {
        $admin = $this->createAdminUser();
        $data = $this->setupProjectAndEmployee();

        $period = PayrollPeriod::create([
            'project_id' => $data['project']->id,
            'name' => 'Gaji Security Perak Ags 2026',
            'type' => 'On-Cycle',
            'month' => 'Agustus 2026',
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-31',
            'status' => 'Completed',
        ]);

        PayrollResult::create([
            'payroll_period_id' => $period->id,
            'employee_id' => $data['employee']->id,
            'days_present' => 5,
            'overtime_hours' => 10.00,
            'basic_salary' => 4000000,
            'transport_allowance' => 100000,
            'overtime_pay' => 300000,
            'net_salary' => 4400000,
        ]);

        // Post to SAP
        $response = $this->withSession(['user' => $admin])
            ->post(route('payrolls.post-sap', $period->id));

        $this->assertEquals('Posted', $period->fresh()->status);

        // Check mock SAP document generated
        $result = PayrollResult::where('payroll_period_id', $period->id)->first();
        $this->assertNotNull($result->sap_doc_number);
        $this->assertNotNull($result->posted_at);

        // Check if Pranota Billing was automatically generated
        $this->assertDatabaseHas('pranota_billings', [
            'payroll_period_id' => $period->id,
            'project_id' => $data['project']->id,
            'status' => 'Belum Terbilling',
            'amount' => 5372400.00
        ]);
    }

    public function test_can_do_nota_and_post_ar_billing_to_sap(): void
    {
        $admin = $this->createAdminUser();
        $data = $this->setupProjectAndEmployee();

        $pranota = PranotaBilling::create([
            'project_id' => $data['project']->id,
            'pranota_number' => 'PRAN-TEST-001',
            'amount' => 5000000,
            'status' => 'Siap Terbilling'
        ]);

        // Do Nota
        $response = $this->withSession(['user' => $admin])
            ->post(route('billing.nota.store'), [
                'pranota_ids' => [$pranota->id],
                'project_id' => $data['project']->id,
                'nota_number' => 'NOTA-TEST-001'
            ]);

        $this->assertEquals('Sudah Terbilling', $pranota->fresh()->status);
        $this->assertDatabaseHas('nota_billings', [
            'nota_number' => 'NOTA-TEST-001',
            'amount' => 5000000,
            'status' => 'Draft'
        ]);

        $nota = NotaBilling::where('nota_number', 'NOTA-TEST-001')->first();

        // Post AR Nota to SAP
        $response = $this->withSession(['user' => $admin])
            ->post(route('billing.nota.post-sap', $nota->id));

        $this->assertEquals('Completed', $nota->fresh()->status);
        $this->assertNotNull($nota->fresh()->sap_doc_number);
    }
}
