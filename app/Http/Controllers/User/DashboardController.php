<?php
// app/Http/Controllers/User/DashboardController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Pastikan relationship 'schedule' dimuat dengan benar
        $recentBookings = $user->bookings()
            ->with([
                'schedule.bus', 
                'schedule.route'
            ])
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('recentBookings'));
    }
}