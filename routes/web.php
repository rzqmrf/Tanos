<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthController;
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
use App\Http\Controllers\ReportController;

use App\Http\Controllers\PartnerController;
use App\Http\Controllers\FinanceMasterController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\P2pMonitoringController;
use App\Http\Controllers\PayrollBillingController;

// auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('permission:dashboard');
Route::get('/api/dashboard-data', [DashboardController::class, 'apiData'])->name('dashboard.api')->middleware('permission:dashboard');

// profile routes (using manual session check in controller)
Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
Route::put('profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings');

// crud group
Route::prefix('dashboard')->group(function () {
    // settings permission group
    Route::middleware('permission:settings')->group(function () {
        Route::resource('users', UserController::class);

        Route::get('peo-settings', [PeoSettingController::class, 'index'])->name('peo.index');
        Route::post('peo-settings', [PeoSettingController::class, 'store'])->name('peo.store');
        Route::get('peo-settings/{id}', [PeoSettingController::class, 'show'])->name('peo.show');
        Route::put('peo-settings/{id}', [PeoSettingController::class, 'update'])->name('peo.update');
        Route::delete('peo-settings/{id}', [PeoSettingController::class, 'destroy'])->name('peo.destroy');
    });

    // Project System: Master Data (8 Modules)
    Route::middleware('permission:projects')->prefix('projects/master')->group(function () {
        Route::get('feasibility-metrics', [\App\Http\Controllers\ProjectMasterController::class, 'feasibilityMetrics'])->name('project.master.feasibility-metrics');
        Route::get('categories', [\App\Http\Controllers\ProjectMasterController::class, 'projectCategories'])->name('project.master.categories');
        Route::get('types', [\App\Http\Controllers\ProjectMasterController::class, 'projectTypes'])->name('project.master.types');
        Route::get('object-types', [\App\Http\Controllers\ProjectMasterController::class, 'objectTypes'])->name('project.master.object-types');
        Route::get('statuses', [\App\Http\Controllers\ProjectMasterController::class, 'statuses'])->name('project.master.statuses');
        Route::get('codes', [\App\Http\Controllers\ProjectMasterController::class, 'masterCodes'])->name('project.master.codes');
        Route::get('roles', [\App\Http\Controllers\ProjectMasterController::class, 'projectRoles'])->name('project.master.roles');
        Route::get('wbs-payroll-categories', [\App\Http\Controllers\ProjectMasterController::class, 'wbsPayrollCategories'])->name('project.master.wbs-payroll-categories');
        Route::get('wbs-payroll-categories/{id}', [\App\Http\Controllers\ProjectMasterController::class, 'wbsPayrollCategoryShow'])->name('project.master.wbs-payroll-categories.show');
        Route::post('items', [\App\Http\Controllers\ProjectMasterController::class, 'store'])->name('project.master.store');
        Route::put('items/{id}', [\App\Http\Controllers\ProjectMasterController::class, 'update'])->name('project.master.update');
        Route::delete('items/{id}', [\App\Http\Controllers\ProjectMasterController::class, 'destroy'])->name('project.master.destroy');
        Route::get('export/{category}', [\App\Http\Controllers\ProjectMasterController::class, 'export'])->name('project.master.export');
    });

    Route::resource('projects', ProjectController::class)->middleware('permission:projects');
    Route::resource('invoices', InvoiceController::class)->middleware('permission:invoices');
    Route::resource('employees', EmployeeController::class)->middleware('permission:employees');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:reports');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export')->middleware('permission:reports');
    
    // Monitoring API & P2P Integration
    Route::get('monitoring/p2p', [P2pMonitoringController::class, 'index'])->name('p2p.index')->middleware('permission:settings');
    Route::post('monitoring/p2p/sync', [P2pMonitoringController::class, 'syncNow'])->name('p2p.sync')->middleware('permission:settings');

    // Payroll Tagihan TAD (Finance & Billing)
    Route::get('finance/payroll-billing', [PayrollBillingController::class, 'index'])->name('payroll.billing.index')->middleware('permission:invoices');
    Route::post('finance/payroll-billing/{id}/pranota', [PayrollBillingController::class, 'generatePranota'])->name('payroll.billing.pranota')->middleware('permission:invoices');
    Route::post('finance/payroll-billing/{id}/post', [PayrollBillingController::class, 'postToBilling'])->name('payroll.billing.post')->middleware('permission:invoices');

    Route::get('under-construction', function () {
        return view('general.under-construction');
    })->name('under.construction');

    // General Master Data: Partners & Bank ACS
    Route::get('general/partner-type', [PartnerController::class, 'partnerTypeIndex'])->name('general.partner-type')->middleware('permission:settings');
    Route::post('general/partner-type', [PartnerController::class, 'partnerTypeStore'])->name('general.partner-type.store')->middleware('permission:settings');
    Route::put('general/partner-type/{id}', [PartnerController::class, 'partnerTypeUpdate'])->name('general.partner-type.update')->middleware('permission:settings');
    Route::delete('general/partner-type/{id}', [PartnerController::class, 'partnerTypeDestroy'])->name('general.partner-type.destroy')->middleware('permission:settings');

    Route::get('general/partner', [PartnerController::class, 'partnerIndex'])->name('general.partner')->middleware('permission:settings');
    Route::get('general/partner/create', [PartnerController::class, 'partnerCreate'])->name('general.partner.create')->middleware('permission:settings');
    Route::post('general/partner', [PartnerController::class, 'partnerStore'])->name('general.partner.store')->middleware('permission:settings');
    Route::get('general/partner/{id}', [PartnerController::class, 'partnerShow'])->name('general.partner.show')->middleware('permission:settings');
    Route::get('general/partner/{id}/edit', [PartnerController::class, 'partnerEdit'])->name('general.partner.edit')->middleware('permission:settings');
    Route::put('general/partner/{id}', [PartnerController::class, 'partnerUpdate'])->name('general.partner.update')->middleware('permission:settings');
    Route::delete('general/partner/{id}', [PartnerController::class, 'partnerDestroy'])->name('general.partner.destroy')->middleware('permission:settings');
    Route::post('general/partner/{id}/banks', [PartnerController::class, 'partnerBankStore'])->name('general.partner.banks.store')->middleware('permission:settings');
    Route::delete('general/partner/{partnerId}/banks/{bankId}', [PartnerController::class, 'partnerBankDestroy'])->name('general.partner.banks.destroy')->middleware('permission:settings');
    Route::post('general/partner/{id}/segments', [PartnerController::class, 'partnerSegmentStore'])->name('general.partner.segments.store')->middleware('permission:settings');
    Route::delete('general/partner/{partnerId}/segments/{segmentId}', [PartnerController::class, 'partnerSegmentDestroy'])->name('general.partner.segments.destroy')->middleware('permission:settings');

    Route::get('general/bank-acs', [PartnerController::class, 'bankAcsIndex'])->name('general.bank-acs')->middleware('permission:settings');
    Route::post('general/bank-acs', [PartnerController::class, 'bankAcsStore'])->name('general.bank-acs.store')->middleware('permission:settings');
    Route::put('general/bank-acs/{id}', [PartnerController::class, 'bankAcsUpdate'])->name('general.bank-acs.update')->middleware('permission:settings');
    Route::delete('general/bank-acs/{id}', [PartnerController::class, 'bankAcsDestroy'])->name('general.bank-acs.destroy')->middleware('permission:settings');

    Route::get('general/calendar', fn() => view('general.calendar'))->name('general.calendar');

    // Finance & Accounting (FA) Masters
    Route::get('fa/tax', [FinanceMasterController::class, 'taxIndex'])->name('fa.tax')->middleware('permission:settings');
    Route::post('fa/tax', [FinanceMasterController::class, 'taxStore'])->name('fa.tax.store')->middleware('permission:settings');
    Route::put('fa/tax/{id}', [FinanceMasterController::class, 'taxUpdate'])->name('fa.tax.update')->middleware('permission:settings');
    Route::delete('fa/tax/{id}', [FinanceMasterController::class, 'taxDestroy'])->name('fa.tax.destroy')->middleware('permission:settings');

    Route::get('fa/account-group', [FinanceMasterController::class, 'accountGroupIndex'])->name('fa.account-group')->middleware('permission:settings');
    Route::post('fa/account-group', [FinanceMasterController::class, 'accountGroupStore'])->name('fa.account-group.store')->middleware('permission:settings');
    Route::put('fa/account-group/{id}', [FinanceMasterController::class, 'accountGroupUpdate'])->name('fa.account-group.update')->middleware('permission:settings');
    Route::delete('fa/account-group/{id}', [FinanceMasterController::class, 'accountGroupDestroy'])->name('fa.account-group.destroy')->middleware('permission:settings');

    Route::get('fa/coa', [FinanceMasterController::class, 'coaIndex'])->name('fa.coa')->middleware('permission:settings');
    Route::post('fa/coa', [FinanceMasterController::class, 'coaStore'])->name('fa.coa.store')->middleware('permission:settings');
    Route::put('fa/coa/{id}', [FinanceMasterController::class, 'coaUpdate'])->name('fa.coa.update')->middleware('permission:settings');
    Route::delete('fa/coa/{id}', [FinanceMasterController::class, 'coaDestroy'])->name('fa.coa.destroy')->middleware('permission:settings');

    // Phase 2 FA Masters
    Route::get('fa/profit-center', [FinanceMasterController::class, 'profitCenterIndex'])->name('fa.profit-center')->middleware('permission:settings');
    Route::post('fa/profit-center', [FinanceMasterController::class, 'profitCenterStore'])->name('fa.profit-center.store')->middleware('permission:settings');
    Route::put('fa/profit-center/{id}', [FinanceMasterController::class, 'profitCenterUpdate'])->name('fa.profit-center.update')->middleware('permission:settings');
    Route::delete('fa/profit-center/{id}', [FinanceMasterController::class, 'profitCenterDestroy'])->name('fa.profit-center.destroy')->middleware('permission:settings');

    Route::get('fa/cost-center', [FinanceMasterController::class, 'costCenterIndex'])->name('fa.cost-center')->middleware('permission:settings');
    Route::post('fa/cost-center', [FinanceMasterController::class, 'costCenterStore'])->name('fa.cost-center.store')->middleware('permission:settings');
    Route::put('fa/cost-center/{id}', [FinanceMasterController::class, 'costCenterUpdate'])->name('fa.cost-center.update')->middleware('permission:settings');
    Route::delete('fa/cost-center/{id}', [FinanceMasterController::class, 'costCenterDestroy'])->name('fa.cost-center.destroy')->middleware('permission:settings');

    Route::get('fa/fund-center', [FinanceMasterController::class, 'fundCenterIndex'])->name('fa.fund-center')->middleware('permission:settings');
    Route::post('fa/fund-center', [FinanceMasterController::class, 'fundCenterStore'])->name('fa.fund-center.store')->middleware('permission:settings');
    Route::put('fa/fund-center/{id}', [FinanceMasterController::class, 'fundCenterUpdate'])->name('fa.fund-center.update')->middleware('permission:settings');
    Route::delete('fa/fund-center/{id}', [FinanceMasterController::class, 'fundCenterDestroy'])->name('fa.fund-center.destroy')->middleware('permission:settings');

    Route::get('fa/currency', [FinanceMasterController::class, 'currencyIndex'])->name('fa.currency')->middleware('permission:settings');
    Route::post('fa/currency', [FinanceMasterController::class, 'currencyStore'])->name('fa.currency.store')->middleware('permission:settings');
    Route::put('fa/currency/{id}', [FinanceMasterController::class, 'currencyUpdate'])->name('fa.currency.update')->middleware('permission:settings');
    Route::delete('fa/currency/{id}', [FinanceMasterController::class, 'currencyDestroy'])->name('fa.currency.destroy')->middleware('permission:settings');

    Route::get('fa/currency-rate', [FinanceMasterController::class, 'currencyRateIndex'])->name('fa.currency-rate')->middleware('permission:settings');
    Route::post('fa/currency-rate', [FinanceMasterController::class, 'currencyRateStore'])->name('fa.currency-rate.store')->middleware('permission:settings');
    Route::put('fa/currency-rate/{id}', [FinanceMasterController::class, 'currencyRateUpdate'])->name('fa.currency-rate.update')->middleware('permission:settings');
    Route::delete('fa/currency-rate/{id}', [FinanceMasterController::class, 'currencyRateDestroy'])->name('fa.currency-rate.destroy')->middleware('permission:settings');

    Route::get('fa/bank-account', [FinanceMasterController::class, 'companyBankIndex'])->name('fa.bank-account')->middleware('permission:settings');
    Route::post('fa/bank-account', [FinanceMasterController::class, 'companyBankStore'])->name('fa.bank-account.store')->middleware('permission:settings');
    Route::put('fa/bank-account/{id}', [FinanceMasterController::class, 'companyBankUpdate'])->name('fa.bank-account.update')->middleware('permission:settings');
    Route::delete('fa/bank-account/{id}', [FinanceMasterController::class, 'companyBankDestroy'])->name('fa.bank-account.destroy')->middleware('permission:settings');

    Route::get('fa/period', [FinanceMasterController::class, 'fiscalPeriodIndex'])->name('fa.period')->middleware('permission:settings');
    Route::post('fa/period', [FinanceMasterController::class, 'fiscalPeriodStore'])->name('fa.period.store')->middleware('permission:settings');
    Route::post('fa/period/{id}/toggle-status', [FinanceMasterController::class, 'fiscalPeriodToggleStatus'])->name('fa.period.toggle-status')->middleware('permission:settings');

    Route::get('fa/fi-settings', fn() => view('finance.fa.fi-settings'))->name('fa.fi-settings');
    Route::get('fa/budget-management', fn() => view('finance.fa.budget-management'))->name('fa.budget-management');
    Route::get('fa/budget-tolerance', fn() => view('finance.fa.budget-tolerance'))->name('fa.budget-tolerance');
    Route::get('fa/cash-flow', fn() => view('finance.fa.cash-flow'))->name('fa.cash-flow');
    Route::get('fa/coa-mapping', fn() => view('finance.fa.coa-mapping'))->name('fa.coa-mapping');
    Route::get('fa/open-item', fn() => view('finance.fa.open-item'))->name('fa.open-item');

    // Material Management
    Route::get('material/equipment', [MaterialController::class, 'equipmentIndex'])->name('material.equipment')->middleware('permission:settings');
    Route::get('material/equipment/create', [MaterialController::class, 'equipmentCreate'])->name('material.equipment.create')->middleware('permission:settings');
    Route::post('material/equipment', [MaterialController::class, 'equipmentStore'])->name('material.equipment.store')->middleware('permission:settings');
    Route::get('material/equipment/{id}', [MaterialController::class, 'equipmentShow'])->name('material.equipment.show')->middleware('permission:settings');
    Route::get('material/equipment/{id}/edit', [MaterialController::class, 'equipmentEdit'])->name('material.equipment.edit')->middleware('permission:settings');
    Route::put('material/equipment/{id}', [MaterialController::class, 'equipmentUpdate'])->name('material.equipment.update')->middleware('permission:settings');
    Route::delete('material/equipment/{id}', [MaterialController::class, 'equipmentDestroy'])->name('material.equipment.destroy')->middleware('permission:settings');

    Route::get('material/outline-agreement', [MaterialController::class, 'outlineAgreementIndex'])->name('material.outline-agreement')->middleware('permission:settings');
    Route::get('material/outline-agreement/create', [MaterialController::class, 'outlineAgreementCreate'])->name('material.outline-agreement.create')->middleware('permission:settings');
    Route::post('material/outline-agreement', [MaterialController::class, 'outlineAgreementStore'])->name('material.outline-agreement.store')->middleware('permission:settings');
    Route::get('material/outline-agreement/{id}', [MaterialController::class, 'outlineAgreementShow'])->name('material.outline-agreement.show')->middleware('permission:settings');
    Route::get('material/outline-agreement/{id}/edit', [MaterialController::class, 'outlineAgreementEdit'])->name('material.outline-agreement.edit')->middleware('permission:settings');
    Route::put('material/outline-agreement/{id}', [MaterialController::class, 'outlineAgreementUpdate'])->name('material.outline-agreement.update')->middleware('permission:settings');
    Route::delete('material/outline-agreement/{id}', [MaterialController::class, 'outlineAgreementDestroy'])->name('material.outline-agreement.destroy')->middleware('permission:settings');

    Route::get('hc/search-employee', fn() => view('hr.search-employee'))->name('hc.search-employee');
    Route::get('ps/wbs-report-new', fn() => view('operations.wbs-report-new'))->name('ps.wbs-report-new');
    Route::get('ps/profit-loss-segment', fn() => view('operations.profit-loss-segment'))->name('ps.profit-loss-segment');
    Route::get('ps/procure-to-pay', fn() => view('operations.procure-to-pay'))->name('ps.procure-to-pay');

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