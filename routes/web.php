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

// auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/api/dashboard-data', [DashboardController::class, 'apiData'])->name('dashboard.api');

// profile routes (using manual session check in controller)
Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

// crud grop
Route::prefix('dashboard')->group(function () {
    Route::resource('project-config', ProjectConfigController::class)->names([
        'index' => 'project.config'
    ]);
    Route::resource('access-controls', AccessControlController::class)->names([
        'index' => 'access.controls'
    ]);
    Route::resource('projects', ProjectController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('employees', EmployeeController::class);

    // Placeholder routes for reports and users
    Route::get('reports', function () {
        return view('dashboard.reports');
    })->name('reports.index');

    Route::get('users', function () {
        return view('dashboard.users');
    })->name('users.index');

    Route::get('clients', function () {
        return view('dashboard.clients');
    })->name('clients.index');

    Route::get('attendances', function () {
        return view('dashboard.attendances');
    })->name('attendances.index');

    Route::get('payrolls', function () {
        return view('dashboard.payrolls');
    })->name('payrolls.index');

    Route::get('expenses', function () {
        return view('dashboard.expenses');
    })->name('expenses.index');

    Route::get('recruitment', function () {
        return view('dashboard.recruitment');
    })->name('recruitment.index');

    Route::get('evaluations', function () {
        return view('dashboard.evaluations');
    })->name('evaluations.index');

    Route::get('certifications', function () {
        return view('dashboard.certifications');
    })->name('certifications.index');

    Route::get('schedules', function () {
        return view('dashboard.schedules');
    })->name('schedules.index');

    Route::get('api/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('api/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::post('api/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});
