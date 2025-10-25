<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Tampilkan daftar jadwal + filter + data pendukung (bus, route) untuk form tambah/edit.
     */
    public function index(Request $request)
    {
        $origin     = $request->query('origin', '');
        $destination= $request->query('destination', '');
        $date       = $request->query('date', ''); // yyyy-mm-dd

        $query = Schedule::with(['bus', 'route'])->orderByDesc('id');

        // Filter berdasarkan asal/tujuan (dari tabel routes)
        if ($origin || $destination) {
            $query->whereHas('route', function ($q) use ($origin, $destination) {
                if ($origin) {
                    $q->where('origin', 'like', "%{$origin}%");
                }
                if ($destination) {
                    $q->where('destination', 'like', "%{$destination}%");
                }
            });
        }

        // Filter tanggal berangkat (kolom departure_time)
        if ($date) {
            $query->whereDate('departure_time', Carbon::parse($date)->toDateString());
        }

        $schedules = $query->paginate(10)->withQueryString();

        // Data pendukung form
        $buses  = Bus::orderBy('name')->get(['id','name','plate_number','capacity']);
        $routes = BusRoute::orderBy('origin')->orderBy('destination')->get(['id','origin','destination','distance_km']);

        return view('admin.schedules.index', compact('schedules', 'buses', 'routes', 'origin', 'destination', 'date'));
    }

    /**
     * Simpan jadwal baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'bus_id'          => ['required', 'exists:buses,id'],
            'route_id'        => ['required', 'exists:routes,id'],
            'departure_time'  => ['required', 'date'],
            'arrival_time'    => ['nullable', 'date', 'after_or_equal:departure_time'],
            'price'           => ['required', 'numeric', 'min:0'],
            'available_seats' => ['required', 'integer', 'min:0'],
        ]);

        // Normalisasi tanggal
        $data['departure_time'] = Carbon::parse($data['departure_time']);
        if (!empty($data['arrival_time'])) {
            $data['arrival_time'] = Carbon::parse($data['arrival_time']);
        }

        Schedule::create($data);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Update jadwal (inline modal atau form edit di halaman yang sama).
     */
    public function update(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'bus_id'          => ['required', 'exists:buses,id'],
            'route_id'        => ['required', 'exists:routes,id'],
            'departure_time'  => ['required', 'date'],
            'arrival_time'    => ['nullable', 'date', 'after_or_equal:departure_time'],
            'price'           => ['required', 'numeric', 'min:0'],
            'available_seats' => ['required', 'integer', 'min:0'],
        ]);

        $data['departure_time'] = Carbon::parse($data['departure_time']);
        if (!empty($data['arrival_time'])) {
            $data['arrival_time'] = Carbon::parse($data['arrival_time']);
        }

        $schedule->update($data);

        return back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Hapus jadwal.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
