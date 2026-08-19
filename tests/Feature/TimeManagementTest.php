<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Employee;
use App\Models\Project;
use App\Models\AbsentType;
use App\Models\ScheduleGroup;
use App\Models\ScheduleAssignment;
use App\Models\TimeEvaluation;
use App\Models\TimePeriod;
use App\Models\TimeResult;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $employee;
    protected $project;

    protected function setUp(): void
    {
        parent::setUp();

        // Create project
        $this->project = Project::create([
            'project_code' => 'P-2026-TEST',
            'project_name' => 'Project Test',
            'customer_name' => 'Customer Test',
            'contract_number' => 'OA-TEST',
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-31',
            'cost_center' => 'CC-TEST',
            'fund_center' => 'FC-TEST',
            'month' => 'Agustus 2026',
            'regional' => 'Regional 1',
            'segment' => 'Enterprise',
            'cost' => 100000000.00,
            'active' => true
        ]);

        // Create employee
        $this->employee = Employee::create([
            'name' => 'John Doe',
            'role' => 'Staff Operasional',
            'month' => 'Agustus 2026',
            'regional' => 'Regional 1',
            'segment' => 'Enterprise',
            'nipp' => 'NIPP-123456',
            'bank_name' => 'Bank Mandiri',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'John Doe',
            'ptkp_status' => 'TK/0',
            'tmt_date' => '2024-01-01',
            'bpjs_kesehatan_number' => '999123',
            'bpjs_ketenagakerjaan_number' => '999456',
            'project_id' => $this->project->id
        ]);

        // Create admin user for login
        $this->admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin_test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'Admin'
        ]);
    }

    public function test_absent_types_index_accessible_by_admin()
    {
        $response = $this->actingAs($this->admin)->get(route('org.absent-types.index'));
        $response->assertStatus(200);
    }

    public function test_can_store_absent_type()
    {
        $data = [
            'code' => 'CT_TEST',
            'name' => 'Cuti Uji Coba',
            'gender' => 'All',
            'priority_level' => 1,
            'deduction_absent' => 'No',
            'valid_from' => '2026-08-01',
            'valid_to' => '2026-08-31',
        ];

        $response = $this->actingAs($this->admin)->post(route('org.absent-types.store'), $data);
        $response->assertRedirect();
        $this->assertDatabaseHas('absent_types', ['code' => 'CT_TEST']);
    }

    public function test_schedules_index_accessible_by_admin()
    {
        $response = $this->actingAs($this->admin)->get(route('org.schedules.index'));
        $response->assertStatus(200);
    }

    public function test_can_store_schedule_group()
    {
        $data = [
            'name' => 'Group Shift Malam',
            'type' => 'Shift',
            'work_start' => '22:00',
            'work_end' => '06:00',
        ];

        $response = $this->actingAs($this->admin)->post(route('org.schedules.group.store'), $data);
        $response->assertRedirect();
        $this->assertDatabaseHas('schedule_groups', ['name' => 'Group Shift Malam']);
    }

    public function test_can_assign_schedule_to_employee()
    {
        $group = ScheduleGroup::create([
            'name' => 'Group Test',
            'type' => 'Reguler',
            'work_start' => '08:00:00',
            'work_end' => '17:00:00'
        ]);

        $data = [
            'employee_id' => $this->employee->id,
            'schedule_group_id' => $group->id,
            'valid_from' => '2026-08-01',
            'valid_to' => '2026-08-31'
        ];

        $response = $this->actingAs($this->admin)->post(route('org.schedules.assign.store'), $data);
        $response->assertRedirect();
        $this->assertDatabaseHas('schedule_assignments', ['employee_id' => $this->employee->id]);
    }

    public function test_can_store_time_evaluation_rule()
    {
        $data = [
            'name' => 'Aturan Test 1',
            'description' => 'Test Rule',
            'valid_from' => '2026-08-01',
            'valid_to' => '2026-08-31',
            'late_tolerance_minutes' => 10,
            'early_departure_minutes' => 10
        ];

        $response = $this->actingAs($this->admin)->post(route('org.evaluations.store'), $data);
        $response->assertRedirect();
        $this->assertDatabaseHas('time_evaluations', ['name' => 'Aturan Test 1', 'is_active' => true]);
    }

    public function test_can_calculate_time_period_results()
    {
        // 1. Create schedule group and assignment
        $group = ScheduleGroup::create([
            'name' => 'Group Test',
            'type' => 'Reguler',
            'work_start' => '08:00:00',
            'work_end' => '17:00:00'
        ]);

        ScheduleAssignment::create([
            'employee_id' => $this->employee->id,
            'schedule_group_id' => $group->id,
            'valid_from' => '2026-08-01',
            'valid_to' => '2026-08-31'
        ]);

        // 2. Create standard active evaluation parameters
        TimeEvaluation::create([
            'name' => 'Aturan Pelindo Test',
            'valid_from' => '2026-08-01',
            'valid_to' => '2026-08-31',
            'late_tolerance_minutes' => 15,
            'early_departure_minutes' => 15,
            'is_active' => true
        ]);

        // 3. Create mock attendances
        // Present, not late
        Attendance::create([
            'employee_id' => $this->employee->id,
            'date' => '2026-08-03', // Workday (Monday)
            'status' => 'Hadir',
            'clock_in' => '08:05:00', // within 15 min tolerance
            'clock_out' => '17:00:00'
        ]);

        // Present, late
        Attendance::create([
            'employee_id' => $this->employee->id,
            'date' => '2026-08-04', // Workday
            'status' => 'Hadir',
            'clock_in' => '08:25:00', // late (diff is 25 mins > 15 tolerance)
            'clock_out' => '17:00:00'
        ]);

        // 4. Create mock approved leave request
        LeaveRequest::create([
            'employee_id' => $this->employee->id,
            'type' => 'Cuti Tahunan',
            'start_date' => '2026-08-05',
            'end_date' => '2026-08-05',
            'total_days' => 1,
            'reason' => 'Test Cuti',
            'status' => 'Approved'
        ]);

        // 5. Create Period
        $period = TimePeriod::create([
            'name' => 'Rekap Agustus Test',
            'project_id' => $this->project->id,
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-07', // 7 days: 5 weekdays, 2 weekend days
            'status' => 'Draft'
        ]);

        $response = $this->actingAs($this->admin)->post(route('org.periods.calculate', $period->id));
        $response->assertRedirect();
        
        // Assert results are computed:
        // Workdays should be 5
        // Present days should be 2
        // Leave days should be 1
        // Absent (Alfa) days should be 5 - 2 - 1 = 2
        // Late days should be 1
        $this->assertDatabaseHas('time_results', [
            'time_period_id' => $period->id,
            'employee_id' => $this->employee->id,
            'workdays' => 5,
            'present_days' => 2,
            'absent_days' => 2,
            'late_days' => 1,
            'leave_days' => 1,
            // Deduction: (1 late * 50000) + (2 absent * 150000) = 350000
            'deduction_amount' => 350000.00
        ]);
    }

    public function test_can_update_and_delete_absent_type()
    {
        $type = AbsentType::create([
            'code' => 'CT_U',
            'name' => 'Cuti Update',
            'gender' => 'All',
            'priority_level' => 1,
            'deduction_absent' => 'No',
            'active' => true
        ]);

        $updateData = [
            'code' => 'CT_U2',
            'name' => 'Cuti Update 2',
            'gender' => 'Male',
            'priority_level' => 2,
            'deduction_absent' => 'Yes',
            'active' => false
        ];

        $response = $this->actingAs($this->admin)->put(route('org.absent-types.update', $type->id), $updateData);
        $response->assertRedirect();
        $this->assertDatabaseHas('absent_types', ['code' => 'CT_U2', 'active' => false]);

        $response = $this->actingAs($this->admin)->delete(route('org.absent-types.destroy', $type->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('absent_types', ['id' => $type->id]);
    }

    public function test_can_update_and_delete_schedule_group_and_assignment()
    {
        $group = ScheduleGroup::create([
            'name' => 'Group U',
            'type' => 'Shift',
            'work_start' => '22:00:00',
            'work_end' => '06:00:00'
        ]);

        $updateData = [
            'name' => 'Group U2',
            'type' => 'Reguler',
            'work_start' => '08:00',
            'work_end' => '17:00',
            'is_active' => false
        ];

        $response = $this->actingAs($this->admin)->put(route('org.schedules.group.update', $group->id), $updateData);
        $response->assertRedirect();
        $this->assertDatabaseHas('schedule_groups', ['name' => 'Group U2', 'is_active' => false]);

        $assign = ScheduleAssignment::create([
            'employee_id' => $this->employee->id,
            'schedule_group_id' => $group->id,
            'valid_from' => '2026-08-01',
            'valid_to' => '2026-08-31'
        ]);

        $response = $this->actingAs($this->admin)->delete(route('org.schedules.assign.destroy', $assign->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('schedule_assignments', ['id' => $assign->id]);

        $response = $this->actingAs($this->admin)->delete(route('org.schedules.group.destroy', $group->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('schedule_groups', ['id' => $group->id]);
    }

    public function test_can_update_and_delete_time_evaluation()
    {
        $eval = TimeEvaluation::create([
            'name' => 'Aturan U',
            'valid_from' => '2026-08-01',
            'valid_to' => '2026-08-31',
            'late_tolerance_minutes' => 15,
            'early_departure_minutes' => 15,
            'is_active' => true
        ]);

        $updateData = [
            'name' => 'Aturan U2',
            'valid_from' => '2026-08-01',
            'valid_to' => '2026-08-31',
            'late_tolerance_minutes' => 20,
            'early_departure_minutes' => 20,
            'is_active' => false
        ];

        $response = $this->actingAs($this->admin)->put(route('org.evaluations.update', $eval->id), $updateData);
        $response->assertRedirect();
        $this->assertDatabaseHas('time_evaluations', ['name' => 'Aturan U2', 'is_active' => false]);

        $response = $this->actingAs($this->admin)->delete(route('org.evaluations.destroy', $eval->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('time_evaluations', ['id' => $eval->id]);
    }

    public function test_can_delete_time_period()
    {
        $period = TimePeriod::create([
            'name' => 'Periode U',
            'project_id' => $this->project->id,
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-07',
            'status' => 'Draft'
        ]);

        $response = $this->actingAs($this->admin)->delete(route('org.periods.destroy', $period->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('time_periods', ['id' => $period->id]);
    }
}
