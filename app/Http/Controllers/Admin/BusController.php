<?php
// app/Http/Controllers/Admin/BusController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BusController extends Controller
{
    /**
     * Tampilkan daftar bus (dengan pagination agar view bisa pakai ->links()).
     */
    public function index(Request $request)
    {
        // (opsional) pencarian sederhana pakai query ?q=
        $q = trim((string) $request->input('q', ''));

        $buses = Bus::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('bus_class', 'like', "%{$q}%")
                      ->orWhere('plate_number', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate(10)              // <<— PENTING: gunakan paginate, bukan all()/get()
            ->withQueryString();        // keep parameter q di pagination

        return view('admin.buses.index', compact('buses', 'q'));
    }

    /**
     * Form tambah bus.
     */
    public function create()
    {
        return view('admin.buses.create');
    }

    /**
     * Simpan data bus baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'bus_class'    => ['required', 'string', 'max:255'],
            'capacity'     => ['required', 'integer', 'min:1'],
            'plate_number' => ['required', 'string', 'max:50', 'unique:buses,plate_number'],
            'facilities'   => ['nullable', 'string'],
        ]);

        // Normalisasi sederhana
        $validated['name']         = trim($validated['name']);
        $validated['bus_class']    = trim($validated['bus_class']);
        $validated['plate_number'] = strtoupper(trim($validated['plate_number']));
        $validated['facilities']   = $validated['facilities'] ?? null;

        Bus::create($validated);

        return redirect()
            ->route('admin.buses.index')
            ->with('success', 'Bus berhasil ditambahkan.');
    }

    /**
     * Form edit bus.
     */
    public function edit(Bus $bus)
    {
        return view('admin.buses.edit', compact('bus'));
    }

    /**
     * Update data bus.
     */
    public function update(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'bus_class'    => ['required', 'string', 'max:255'],
            'capacity'     => ['required', 'integer', 'min:1'],
            'plate_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('buses', 'plate_number')->ignore($bus->id),
            ],
            'facilities'   => ['nullable', 'string'],
        ]);

        // Normalisasi sederhana
        $validated['name']         = trim($validated['name']);
        $validated['bus_class']    = trim($validated['bus_class']);
        $validated['plate_number'] = strtoupper(trim($validated['plate_number']));
        $validated['facilities']   = $validated['facilities'] ?? null;

        $bus->update($validated);

        return redirect()
            ->route('admin.buses.index')
            ->with('success', 'Bus berhasil diperbarui.');
    }

    /**
     * Hapus data bus.
     */
    public function destroy(Bus $bus)
    {
        $bus->delete();

        return redirect()
            ->route('admin.buses.index')
            ->with('success', 'Bus berhasil dihapus.');
    }
}
