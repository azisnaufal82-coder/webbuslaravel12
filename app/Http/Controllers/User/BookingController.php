<?php
// app/Http/Controllers/User/BookingController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with([
                'schedule.bus', 
                'schedule.route'
            ])
            ->latest()
            ->paginate(10);

        return view('user.bookings', compact('bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'num_of_seats' => 'required|integer|min:1|max:10'
        ]);

        $schedule = Schedule::with(['bus', 'bookings'])->findOrFail($request->schedule_id);

        // Calculate available seats
        $bookedSeats = $schedule->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('num_of_seats');
        $availableSeats = $schedule->bus->capacity - $bookedSeats;

        if ($availableSeats < $request->num_of_seats) {
            return back()->with('error', 'Maaf, hanya tersisa ' . $availableSeats . ' kursi tersedia.');
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'schedule_id' => $request->schedule_id,
            'num_of_seats' => $request->num_of_seats,
            'total_price' => $schedule->price * $request->num_of_seats,
            'status' => 'pending'
        ]);

        return redirect()->route('user.bookings')
            ->with('success', 'Pemesanan berhasil! Kode booking: ' . $booking->booking_code);
    }
}