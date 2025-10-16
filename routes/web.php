<?php
// routes/web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

// Public routes - redirect jika sudah login
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.public');

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User routes (require auth)
Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/my-bookings', [UserBookingController::class, 'index'])->name('user.bookings');
    Route::post('/bookings', [UserBookingController::class, 'store'])->name('user.bookings.store');
});

// Admin routes (require auth and admin role)
// Admin routes (require auth and admin role)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::get('/buses', function () {
        return view('admin.buses.index');
    })->name('admin.buses.index');
    
    Route::get('/routes', function () {
        return view('admin.routes.index');
    })->name('admin.routes.index');
    
    Route::get('/schedules', function () {
        return view('admin.schedules.index');
    })->name('admin.schedules.index');
    
    Route::get('/bookings', function () {
        return view('admin.bookings.index');
    })->name('admin.bookings.index');
    
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('admin.users.index');
});