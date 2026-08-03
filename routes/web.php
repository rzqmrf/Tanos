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

// auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
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
    });

    Route::resource('projects', ProjectController::class)->middleware('permission:projects');
    Route::resource('invoices', InvoiceController::class)->middleware('permission:invoices');
    Route::resource('employees', EmployeeController::class)->middleware('permission:employees');

    Route::get('reports', function () {
        return view('analytics.reports');
    })->name('reports.index')->middleware('permission:reports');

    Route::get('clients', function () {
        return view('operations.clients');
    })->name('clients.index')->middleware('permission:clients');

    Route::resource('attendances', AttendanceController::class)->middleware('permission:attendance');

    Route::get('payrolls', function () {
        return view('finance.payrolls');
    })->name('payrolls.index')->middleware('permission:payroll');

    Route::get('expenses', function () {
        return view('finance.expenses');
    })->name('expenses.index')->middleware('permission:expenses');

    Route::get('recruitment', function () {
        return view('hr.recruitment');
    })->name('recruitment.index')->middleware('permission:recruitment');

    Route::get('evaluations', function () {
        return view('hr.evaluations');
    })->name('evaluations.index')->middleware('permission:evaluations');

    Route::get('certifications', function () {
        return view('hr.certifications');
    })->name('certifications.index')->middleware('permission:certifications');

    Route::get('schedules', function () {
        return view('operations.schedules');
    })->name('schedules.index')->middleware('permission:schedules');

    Route::get('notifications', [NotificationController::class, 'page'])->name('notifications.page');
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('notifications-clear-all', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');
    Route::get('api/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('api/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::post('api/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // copilot
    Route::get('copilot', [CopilotController::class, 'index'])->name('copilot.index')->middleware('permission:dashboard');
    Route::post('copilot/chat', [CopilotController::class, 'chat'])->name('copilot.chat')->middleware('permission:dashboard');
});