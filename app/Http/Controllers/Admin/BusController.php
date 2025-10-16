<?php
// app/Http/Controllers/Admin/BusController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::all();
        return view('admin.buses.index', compact('buses'));
    }

    public function create()
    {
        return view('admin.buses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bus_class' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'plate_number' => 'required|string|unique:buses',
            'facilities' => 'nullable|string'
        ]);

        Bus::create($request->all());

        return redirect()->route('admin.buses.index')->with('success', 'Bus berhasil ditambahkan.');
    }

    public function edit(Bus $bus)
    {
        return view('admin.buses.edit', compact('bus'));
    }

    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bus_class' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'plate_number' => 'required|string|unique:buses,plate_number,' . $bus->id,
            'facilities' => 'nullable|string'
        ]);

        $bus->update($request->all());

        return redirect()->route('admin.buses.index')->with('success', 'Bus berhasil diperbarui.');
    }

    public function destroy(Bus $bus)
    {
        $bus->delete();
        return redirect()->route('admin.buses.index')->with('success', 'Bus berhasil dihapus.');
    }
}