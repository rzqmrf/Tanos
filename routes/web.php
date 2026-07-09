<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Dashboard Routes
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/api/dashboard-data', [DashboardController::class, 'apiData'])->name('dashboard.api');
