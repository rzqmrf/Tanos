<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthController;

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
Route::get('/projects', function () { return view('dashboard.projects'); })->name('projects.index');
Route::get('/employees', function () { return view('dashboard.employees'); })->name('employees.index');
Route::get('/invoices', function () { return view('dashboard.invoices'); })->name('invoices.index');