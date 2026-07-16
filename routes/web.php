<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\ProjectController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Dashboard Routes
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/api/dashboard-data', [DashboardController::class, 'apiData'])->name('dashboard.api');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


// route testing

Route::get('/project-config', function () { return view('dashboard.project-config'); })->name('project.config');
Route::get('/access-controls', function () { return view('dashboard.access-controls'); })->name('access.controls');

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvoiceController;

// Project CRUD
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

// Employee CRUD
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

// Invoice CRUD
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

// New Routes
Route::get('/reports', function () { return view('dashboard.reports'); })->name('reports.index');
Route::get('/users', function () { return view('dashboard.users'); })->name('users.index');