<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProjectConfigController;
use App\Http\Controllers\AccessControlController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CopilotController;
use App\Http\Controllers\EssController;
use App\Http\Controllers\RabBudgetController;
use App\Http\Controllers\OrgStructureController;
use App\Http\Controllers\TimeManagementController;
use App\Http\Controllers\PeoSettingController;

// auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('permission:dashboard');
Route::get('/api/dashboard-data', [DashboardController::class, 'apiData'])->name('dashboard.api')->middleware('permission:dashboard');

// profile routes (using manual session check in controller)
Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
Route::put('profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings');

// crud grop
Route::prefix('dashboard')->group(function () {
    // settings permission group
    Route::middleware('permission:settings')->group(function () {
        Route::resource('project-config', ProjectConfigController::class)->names([
            'index' => 'project.config'
        ]);
        Route::post('project-config/regionals', [ProjectConfigController::class, 'storeRegional'])->name('project.config.regionals.store');
        Route::put('project-config/regionals/{regional}', [ProjectConfigController::class, 'updateRegional'])->name('project.config.regionals.update');
        Route::delete('project-config/regionals/{regional}', [ProjectConfigController::class, 'destroyRegional'])->name('project.config.regionals.destroy');
        Route::post('project-config/sub-regionals', [ProjectConfigController::class, 'storeSubRegional'])->name('project.config.sub-regionals.store');
        Route::put('project-config/sub-regionals/{subRegional}', [ProjectConfigController::class, 'updateSubRegional'])->name('project.config.sub-regionals.update');
        Route::delete('project-config/sub-regionals/{subRegional}', [ProjectConfigController::class, 'destroySubRegional'])->name('project.config.sub-regionals.destroy');
        Route::post('project-config/segments', [ProjectConfigController::class, 'storeSegment'])->name('project.config.segments.store');
        Route::put('project-config/segments/{segment}', [ProjectConfigController::class, 'updateSegment'])->name('project.config.segments.update');
        Route::delete('project-config/segments/{segment}', [ProjectConfigController::class, 'destroySegment'])->name('project.config.segments.destroy');

        Route::resource('access-controls', AccessControlController::class)->names([
            'index' => 'access.controls'
        ]);
        Route::post('access-controls/roles', [AccessControlController::class, 'addRole'])->name('access.controls.add-role');
        Route::resource('users', UserController::class);

        Route::get('peo-settings', [PeoSettingController::class, 'index'])->name('peo.index');
        Route::post('peo-settings', [PeoSettingController::class, 'store'])->name('peo.store');
        Route::get('peo-settings/{id}', [PeoSettingController::class, 'show'])->name('peo.show');
        Route::put('peo-settings/{id}', [PeoSettingController::class, 'update'])->name('peo.update');
        Route::delete('peo-settings/{id}', [PeoSettingController::class, 'destroy'])->name('peo.destroy');
    });

    Route::resource('projects', ProjectController::class)->middleware('permission:projects');
    Route::resource('invoices', InvoiceController::class)->middleware('permission:invoices');
    Route::resource('employees', EmployeeController::class)->middleware('permission:employees');

    Route::get('reports', function () {
        return view('finance.reports');
    })->name('reports.index')->middleware('permission:reports');

    Route::resource('attendances', AttendanceController::class)->middleware('permission:attendance');

    // Projects WBS System
    Route::get('projects/{project}/wbs', [\App\Http\Controllers\WbsController::class, 'index'])->name('projects.wbs.index')->middleware('permission:projects');
    Route::post('projects/{project}/wbs', [\App\Http\Controllers\WbsController::class, 'store'])->name('projects.wbs.store')->middleware('permission:projects');
    Route::put('projects/{project}/wbs/{wbs}', [\App\Http\Controllers\WbsController::class, 'update'])->name('projects.wbs.update')->middleware('permission:projects');
    Route::delete('projects/{project}/wbs/{wbs}', [\App\Http\Controllers\WbsController::class, 'destroy'])->name('projects.wbs.destroy')->middleware('permission:projects');
    Route::post('projects/{project}/wbs/send-sap', [\App\Http\Controllers\WbsController::class, 'sendToSap'])->name('projects.wbs.send-sap')->middleware('permission:projects');

    // RAB Budget Matrix System
    Route::get('rab-budgets', [RabBudgetController::class, 'index'])->name('rab.index')->middleware('permission:projects');
    Route::get('rab-budgets/{id}', [RabBudgetController::class, 'show'])->name('rab.show')->middleware('permission:projects');
    Route::post('rab-budgets/{rabId}/items', [RabBudgetController::class, 'storeItem'])->name('rab.items.store')->middleware('permission:projects');
    Route::put('rab-budgets/{rabId}/items/{itemId}', [RabBudgetController::class, 'updateItem'])->name('rab.items.update')->middleware('permission:projects');
    Route::delete('rab-budgets/{rabId}/items/{itemId}', [RabBudgetController::class, 'destroyItem'])->name('rab.items.destroy')->middleware('permission:projects');
    Route::post('rab-budgets/{id}/send-sap', [RabBudgetController::class, 'sendToSap'])->name('rab.send-sap')->middleware('permission:projects');

    // Human Capital Master Data (Regional, Job Class, Job Field, Employee Status, Religion, Job Status)
    Route::get('hc-master/{category?}', [\App\Http\Controllers\HcMasterDataController::class, 'index'])->name('hc.master.index')->middleware('permission:employees');
    Route::post('hc-master/{category}', [\App\Http\Controllers\HcMasterDataController::class, 'store'])->name('hc.master.store')->middleware('permission:employees');
    Route::put('hc-master/update/{id}', [\App\Http\Controllers\HcMasterDataController::class, 'update'])->name('hc.master.update')->middleware('permission:employees');
    Route::delete('hc-master/{id}', [\App\Http\Controllers\HcMasterDataController::class, 'destroy'])->name('hc.master.destroy')->middleware('permission:employees');

    // Organizational Structure Modules (STO, Job Position, ECN)
    Route::get('org-structure/sto', [OrgStructureController::class, 'stoIndex'])->name('org.sto.index')->middleware('permission:employees');
    Route::post('org-structure/sto', [OrgStructureController::class, 'stoStore'])->name('org.sto.store')->middleware('permission:employees');
    Route::post('org-structure/sto/{id}/delimit', [OrgStructureController::class, 'stoDelimit'])->name('org.sto.delimit')->middleware('permission:employees');
    Route::post('org-structure/sto/{id}/send-sap', [OrgStructureController::class, 'stoSendSap'])->name('org.sto.send-sap')->middleware('permission:employees');

    Route::get('org-structure/job', [OrgStructureController::class, 'jobIndex'])->name('org.job.index')->middleware('permission:employees');
    Route::post('org-structure/job', [OrgStructureController::class, 'jobStore'])->name('org.job.store')->middleware('permission:employees');
    Route::post('org-structure/job/{id}/delimit', [OrgStructureController::class, 'jobDelimit'])->name('org.job.delimit')->middleware('permission:employees');
    Route::post('org-structure/job/{id}/duplicate', [OrgStructureController::class, 'jobDuplicate'])->name('org.job.duplicate')->middleware('permission:employees');
    Route::post('org-structure/job/{id}/send-sap', [OrgStructureController::class, 'jobSendSap'])->name('org.job.send-sap')->middleware('permission:employees');

    Route::get('org-structure/ecn', [OrgStructureController::class, 'ecnIndex'])->name('org.ecn.index')->middleware('permission:employees');
    Route::post('org-structure/ecn', [OrgStructureController::class, 'ecnStore'])->name('org.ecn.store')->middleware('permission:employees');
    Route::post('org-structure/ecn/{id}/complete', [OrgStructureController::class, 'ecnComplete'])->name('org.ecn.complete')->middleware('permission:employees');
    Route::post('org-structure/ecn/{id}/send-sap', [OrgStructureController::class, 'ecnSendSap'])->name('org.ecn.send-sap')->middleware('permission:employees');

    // HCM Time Management & Evaluation (Absent Type, Schedules, Tolerances, Periods)
    Route::get('time-management/absent-types', [TimeManagementController::class, 'absentTypesIndex'])->name('org.absent-types.index')->middleware('permission:employees');
    Route::post('time-management/absent-types', [TimeManagementController::class, 'absentTypesStore'])->name('org.absent-types.store')->middleware('permission:employees');
    Route::put('time-management/absent-types/{id}', [TimeManagementController::class, 'absentTypesUpdate'])->name('org.absent-types.update')->middleware('permission:employees');
    Route::delete('time-management/absent-types/{id}', [TimeManagementController::class, 'absentTypesDestroy'])->name('org.absent-types.destroy')->middleware('permission:employees');

    Route::get('time-management/schedules', [TimeManagementController::class, 'schedulesIndex'])->name('org.schedules.index')->middleware('permission:schedules');
    Route::post('time-management/schedules/group', [TimeManagementController::class, 'scheduleGroupStore'])->name('org.schedules.group.store')->middleware('permission:schedules');
    Route::put('time-management/schedules/group/{id}', [TimeManagementController::class, 'scheduleGroupUpdate'])->name('org.schedules.group.update')->middleware('permission:schedules');
    Route::delete('time-management/schedules/group/{id}', [TimeManagementController::class, 'scheduleGroupDestroy'])->name('org.schedules.group.destroy')->middleware('permission:schedules');
    Route::post('time-management/schedules/assign', [TimeManagementController::class, 'scheduleAssignStore'])->name('org.schedules.assign.store')->middleware('permission:schedules');
    Route::delete('time-management/schedules/assign/{id}', [TimeManagementController::class, 'scheduleAssignDestroy'])->name('org.schedules.assign.destroy')->middleware('permission:schedules');

    Route::get('time-management/evaluations', [TimeManagementController::class, 'evaluationIndex'])->name('org.evaluations.index')->middleware('permission:employees');
    Route::post('time-management/evaluations', [TimeManagementController::class, 'evaluationStore'])->name('org.evaluations.store')->middleware('permission:employees');
    Route::put('time-management/evaluations/{id}', [TimeManagementController::class, 'evaluationUpdate'])->name('org.evaluations.update')->middleware('permission:employees');
    Route::delete('time-management/evaluations/{id}', [TimeManagementController::class, 'evaluationDestroy'])->name('org.evaluations.destroy')->middleware('permission:employees');

    Route::get('time-management/periods', [TimeManagementController::class, 'periodsIndex'])->name('org.periods.index')->middleware('permission:employees');
    Route::post('time-management/periods', [TimeManagementController::class, 'periodStore'])->name('org.periods.store')->middleware('permission:employees');
    Route::get('time-management/periods/{id}', [TimeManagementController::class, 'periodShow'])->name('org.periods.show')->middleware('permission:employees');
    Route::post('time-management/periods/{id}/calculate', [TimeManagementController::class, 'periodCalculate'])->name('org.periods.calculate')->middleware('permission:employees');
    Route::delete('time-management/periods/{id}', [TimeManagementController::class, 'periodDestroy'])->name('org.periods.destroy')->middleware('permission:employees');

    // HCM Period Payroll & Component Formulation
    Route::get('payrolls', [\App\Http\Controllers\PayrollController::class, 'index'])->name('payrolls.index')->middleware('permission:payroll');
    Route::post('payrolls', [\App\Http\Controllers\PayrollController::class, 'store'])->name('payrolls.store')->middleware('permission:payroll');
    Route::get('payrolls/{id}', [\App\Http\Controllers\PayrollController::class, 'show'])->name('payrolls.show')->middleware('permission:payroll');
    Route::post('payrolls/{id}/calculate', [\App\Http\Controllers\PayrollController::class, 'calculate'])->name('payrolls.calculate')->middleware('permission:payroll');
    Route::post('payrolls/{id}/copy-formula', [\App\Http\Controllers\PayrollController::class, 'copyFormula'])->name('payrolls.copy-formula')->middleware('permission:payroll');
    Route::post('payrolls/{id}/post-sap', [\App\Http\Controllers\PayrollController::class, 'postSap'])->name('payrolls.post-sap')->middleware('permission:payroll');

    // PS Posting Payroll General Ledger
    Route::get('posting-payrolls', [\App\Http\Controllers\PostingPayrollController::class, 'index'])->name('posting_payrolls.index')->middleware('permission:payroll');
    Route::get('posting-payrolls/{id}/journal', [\App\Http\Controllers\PostingPayrollController::class, 'showJournal'])->name('posting_payrolls.journal')->middleware('permission:payroll');
    Route::post('posting-payrolls/{id}/upload', [\App\Http\Controllers\PostingPayrollController::class, 'uploadPFile'])->name('posting_payrolls.upload')->middleware('permission:payroll');
    Route::post('posting-payrolls/{id}/void', [\App\Http\Controllers\PostingPayrollController::class, 'voidJournal'])->name('posting_payrolls.void')->middleware('permission:payroll');

    // PS Billing & Pranota
    Route::get('billing', [\App\Http\Controllers\BillingController::class, 'index'])->name('billing.index')->middleware('permission:invoices');
    Route::post('billing/pranota', [\App\Http\Controllers\BillingController::class, 'storePranota'])->name('billing.pranota.store')->middleware('permission:invoices');
    Route::post('billing/pranota/{id}/approve', [\App\Http\Controllers\BillingController::class, 'approvePranota'])->name('billing.pranota.approve')->middleware('permission:invoices');
    Route::post('billing/nota', [\App\Http\Controllers\BillingController::class, 'doNota'])->name('billing.nota.store')->middleware('permission:invoices');
    Route::post('billing/nota/{id}/post-sap', [\App\Http\Controllers\BillingController::class, 'postNota'])->name('billing.nota.post-sap')->middleware('permission:invoices');

    Route::get('notifications', [NotificationController::class, 'page'])->name('notifications.page');
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('notifications-clear-all', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');
    Route::get('api/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('api/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::post('api/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // copilot
    Route::get('copilot', [CopilotController::class, 'index'])->name('copilot.index')->middleware('permission:dashboard');
    Route::post('copilot/chat', [CopilotController::class, 'chat'])->name('copilot.chat')->middleware('permission:dashboard');

    // employee self service (ess) — batasi ke yg pegang attendance
    Route::get('ess', [EssController::class, 'index'])->name('ess.index')->middleware('permission:attendance');
    Route::post('ess/leave', [EssController::class, 'storeLeave'])->name('ess.leave.store')->middleware('permission:attendance');
    Route::post('ess/cico', [EssController::class, 'storeCico'])->name('ess.cico.store')->middleware('permission:attendance');
    Route::get('admin/ess', [EssController::class, 'adminIndex'])->name('ess.admin.index')->middleware('permission:attendance');
    Route::post('admin/ess/leave/{id}/{status}', [EssController::class, 'actionLeave'])->name('ess.admin.leave.action')->middleware('permission:attendance');
    Route::post('admin/ess/cico/{id}/{status}', [EssController::class, 'actionCico'])->name('ess.admin.cico.action')->middleware('permission:attendance');
});