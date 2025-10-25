<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $status     = $request->query('status', '');
        $startDate  = $request->query('start_date', '');
        $endDate    = $request->query('end_date', '');

        $query = Booking::with(['user', 'schedule.bus', 'schedule.route']);

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($startDate || $endDate) {
            $query->whereHas('schedule', function ($q) use ($startDate, $endDate) {
                if ($startDate) {
                    $q->whereDate('departure_time', '>=', Carbon::parse($startDate)->toDateString());
                }
                if ($endDate) {
                    $q->whereDate('departure_time', '<=', Carbon::parse($endDate)->toDateString());
                }
            });
        }

        $bookings = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.bookings.index', compact('bookings', 'status', 'startDate', 'endDate'));
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled'])],
        ]);

        $booking->update($data);

        return back()->with('success', 'Status pemesanan berhasil diperbarui.');
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'schedule.bus', 'schedule.route']);
        return view('admin.bookings.show', compact('booking'));
    }
}
