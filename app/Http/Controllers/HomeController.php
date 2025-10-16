<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Redirect user yang sudah login ke dashboard
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/dashboard');
            }
        }

        // User belum login - tampilkan landing page
        $popularSchedules = Schedule::with(['bus', 'route'])
            ->where('departure_time', '>', now())
            ->orderBy('departure_time', 'asc')
            ->take(6)
            ->get();

        return view('home', compact('popularSchedules'));
    }
}