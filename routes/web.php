<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScheduleController; // publik

// User
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\BookingController as UserBookingController;

// Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\BusController; // <= penting

// =======================
// Public
// =======================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.public');

// =======================
// Auth
// =======================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =======================
// User (harus login)
// =======================
Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('user.dashboard');

    Route::get('/my-bookings', [UserBookingController::class, 'index'])->name('user.bookings');
    Route::post('/bookings', [UserBookingController::class, 'store'])->name('user.bookings.store');
});

// =======================
// Admin (harus login + admin)
// =======================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // ====== Manajemen Bus (CRUD lengkap, tanpa show) ======
    // menghasilkan:
    // admin.buses.index   GET    /admin/buses
    // admin.buses.create  GET    /admin/buses/create
    // admin.buses.store   POST   /admin/buses
    // admin.buses.edit    GET    /admin/buses/{bus}/edit
    // admin.buses.update  PUT|PATCH /admin/buses/{bus}
    // admin.buses.destroy DELETE /admin/buses/{bus}
    Route::resource('buses', BusController::class)
        ->except(['show'])
        ->names('buses');

    // (opsional) Kalau nanti ada controller Routes sendiri, tinggal aktifkan:
    // use App\Http\Controllers\Admin\RouteController as AdminRouteController;
    // Route::resource('routes', AdminRouteController::class)->except(['show'])->names('routes');

    // ====== Kelola Jadwal ======
    Route::get('/schedules', [AdminScheduleController::class, 'index'])->name('schedules.index');
    Route::post('/schedules', [AdminScheduleController::class, 'store'])->name('schedules.store');
    Route::match(['put','patch'], '/schedules/{schedule}', [AdminScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [AdminScheduleController::class, 'destroy'])->name('schedules.destroy');

    // ====== Kelola Pemesanan ======
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::match(['put','patch'], '/bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');

    // ====== Users ======
    Route::resource('users', AdminUserController::class)
        ->except(['show', 'create', 'edit'])
        ->names('users');
});
