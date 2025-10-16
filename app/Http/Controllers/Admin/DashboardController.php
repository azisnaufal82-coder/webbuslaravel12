<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bus;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Route;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'buses_count' => Bus::count(),
            'bookings_count' => Booking::count(),
            'schedules_count' => Schedule::count(),
            'routes_count' => Route::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}