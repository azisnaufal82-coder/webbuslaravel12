<?php
// app/Http/Controllers/ScheduleController.php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['bus', 'route'])
            ->where('departure_time', '>', now())
            ->orderBy('departure_time', 'asc');

        if ($request->has('origin') && $request->origin) {
            $query->whereHas('route', function($q) use ($request) {
                $q->where('origin', 'like', '%' . $request->origin . '%');
            });
        }

        if ($request->has('destination') && $request->destination) {
            $query->whereHas('route', function($q) use ($request) {
                $q->where('destination', 'like', '%' . $request->destination . '%');
            });
        }

        $schedules = $query->paginate(10);

        return view('schedules.index', compact('schedules'));
    }
}