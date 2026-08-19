<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\CicoCorrection;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EssTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an unauthenticated user is redirected to login for ESS index.
     */
    public function test_ess_dashboard_redirects_unauthenticated_user(): void
    {
        $response = $this->get(route('ess.index'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Test that an employee user can access their ESS dashboard.
     */
    public function test_ess_dashboard_accessible_by_employee_user(): void
    {
        $employee = Employee::create([
            'name' => 'John Doe',
            'role' => 'Staff',
            'month' => 'Agustus 2026',
            'regional' => 'Jawa Timur',
            'sub_regional' => 'Surabaya',
            'segment' => 'Enterprise'
        ]);

        $user = User::create([
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'Employee',
            'employee_id' => $employee->id
        ]);

        $response = $this->actingAs($user)->get(route('ess.index'));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('Employee Self Service');
    }

    /**
     * Test that an employee can submit a leave request.
     */
    public function test_employee_can_submit_leave_request(): void
    {
        $employee = Employee::create([
            'name' => 'Jane Doe',
            'role' => 'Staff',
            'month' => 'Agustus 2026',
            'regional' => 'Jawa Timur',
            'segment' => 'Enterprise'
        ]);

        $user = User::create([
            'name' => 'Jane Doe',
            'username' => 'janedoe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'Employee',
            'employee_id' => $employee->id
        ]);

        $response = $this->actingAs($user)
            ->post(route('ess.leave.store'), [
                'type' => 'Cuti Tahunan',
                'start_date' => Carbon::tomorrow()->format('Y-m-d'),
                'end_date' => Carbon::tomorrow()->addDays(2)->format('Y-m-d'),
                'reason' => 'Keperluan keluarga penting',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('ess.index'));

        $this->assertDatabaseHas('leave_requests', [
            'employee_id' => $employee->id,
            'type' => 'Cuti Tahunan',
            'total_days' => 3,
            'reason' => 'Keperluan keluarga penting',
            'status' => 'Submitted'
        ]);
    }

    /**
     * Test that an employee can submit a CICO correction.
     */
    public function test_employee_can_submit_cico_correction(): void
    {
        $employee = Employee::create([
            'name' => 'Jane Doe',
            'role' => 'Staff',
            'month' => 'Agustus 2026',
            'regional' => 'Jawa Timur',
            'segment' => 'Enterprise'
        ]);

        $user = User::create([
            'name' => 'Jane Doe',
            'username' => 'janedoe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'Employee',
            'employee_id' => $employee->id
        ]);

        $response = $this->actingAs($user)
            ->post(route('ess.cico.store'), [
                'date' => Carbon::today()->format('Y-m-d'),
                'clock_in' => '08:00',
                'clock_out' => '17:00',
                'reason' => 'Alat scanner error',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('ess.index'));

        $this->assertDatabaseHas('cico_corrections', [
            'employee_id' => $employee->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'clock_in' => '08:00:00',
            'clock_out' => '17:00:00',
            'reason' => 'Alat scanner error',
            'status' => 'Submitted'
        ]);
    }

    /**
     * Test that an admin user can access the approvals panel.
     */
    public function test_approvals_panel_accessible_by_admin(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'username' => 'adminuser',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'Admin'
        ]);

        $response = $this->actingAs($user)->get(route('ess.admin.index'));

        $response->assertStatus(200);
        $response->assertSee('Panel Persetujuan ESS');
    }

    /**
     * Test that an employee user is restricted from accessing the approvals panel.
     */
    public function test_approvals_panel_restricted_for_employee(): void
    {
        $user = User::create([
            'name' => 'Employee User',
            'username' => 'empuser',
            'email' => 'emp@example.com',
            'password' => bcrypt('password'),
            'role' => 'Employee'
        ]);

        $response = $this->actingAs($user)->get(route('ess.admin.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard.index'));
    }

    /**
     * Test that an admin can approve a leave request, which automatically inserts into attendances.
     */
    public function test_admin_can_approve_leave_request(): void
    {
        $employee = Employee::create([
            'name' => 'Jane Doe',
            'role' => 'Staff',
            'month' => 'Agustus 2026',
            'regional' => 'Jawa Timur',
            'segment' => 'Enterprise'
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'adminuser',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'Admin'
        ]);

        $leave = LeaveRequest::create([
            'employee_id' => $employee->id,
            'type' => 'Sakit',
            'start_date' => Carbon::tomorrow()->format('Y-m-d'),
            'end_date' => Carbon::tomorrow()->format('Y-m-d'),
            'total_days' => 1,
            'reason' => 'Demam tinggi',
            'status' => 'Submitted'
        ]);

        $response = $this->actingAs($admin)
            ->post(route('ess.admin.leave.action', [$leave->id, 'Approved']));

        $response->assertStatus(302);

        $this->assertDatabaseHas('leave_requests', [
            'id' => $leave->id,
            'status' => 'Approved',
            'approved_by' => $admin->id
        ]);

        // Verify it synced into attendances table
        $this->assertDatabaseHas('attendances', [
            'employee_id' => $employee->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'status' => 'Sakit'
        ]);
    }

    /**
     * Test that an admin can approve a CICO correction, which automatically updates attendances.
     */
    public function test_admin_can_approve_cico_correction(): void
    {
        $employee = Employee::create([
            'name' => 'Jane Doe',
            'role' => 'Staff',
            'month' => 'Agustus 2026',
            'regional' => 'Jawa Timur',
            'segment' => 'Enterprise'
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'adminuser',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'Admin'
        ]);

        $cico = CicoCorrection::create([
            'employee_id' => $employee->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'clock_in' => '08:15',
            'clock_out' => '17:30',
            'reason' => 'Lupa absen pulang',
            'status' => 'Submitted'
        ]);

        $response = $this->actingAs($admin)
            ->post(route('ess.admin.cico.action', [$cico->id, 'Approved']));

        $response->assertStatus(302);

        $this->assertDatabaseHas('cico_corrections', [
            'id' => $cico->id,
            'status' => 'Approved',
            'approved_by' => $admin->id
        ]);

        // Verify it synced into attendances table
        $this->assertDatabaseHas('attendances', [
            'employee_id' => $employee->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'status' => 'Hadir',
            'clock_in' => '08:15:00',
            'clock_out' => '17:30:00'
        ]);
    }
}
