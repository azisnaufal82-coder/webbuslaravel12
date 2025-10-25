@extends('layouts.app')

@section('title', 'Kelola Jadwal')

@section('content')
<div class="container-xxl py-4" style="max-width:1400px;">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Kelola Jadwal</h1>
    </div>

    @if(session('success')) 
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- Filter --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header fw-semibold bg-white">Filter Jadwal</div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.schedules.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Asal</label>
                    <input type="text" name="origin" value="{{ $origin ?? '' }}" class="form-control" placeholder="cth: Jakarta">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tujuan</label>
                    <input type="text" name="destination" value="{{ $destination ?? '' }}" class="form-control" placeholder="cth: Bandung">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Berangkat</label>
                    <input type="date" name="date" value="{{ $date ?? '' }}" class="form-control">
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Form Tambah Jadwal --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header fw-semibold bg-white">Tambah Jadwal</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.schedules.store') }}" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <label class="form-label">Bus</label>
                    <select name="bus_id" class="form-select" required>
                        <option value="" hidden>Pilih bus</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}">{{ $bus->name }} ({{ $bus->plate_number }}) - Kap {{ $bus->capacity }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Rute</label>
                    <select name="route_id" class="form-select" required>
                        <option value="" hidden>Pilih rute</option>
                        @foreach($routes as $r)
                            <option value="{{ $r->id }}">{{ $r->origin }} → {{ $r->destination }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Waktu Berangkat</label>
                    <input type="datetime-local" name="departure_time" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Waktu Tiba</label>
                    <input type="datetime-local" name="arrival_time" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Harga</label>
                    <input type="number" name="price" class="form-control" min="0" step="1000" required>
                </div>
                <div class="col-12">
                    <button class="btn btn-success" type="submit">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Jadwal --}}
    <div class="card shadow-sm border-0">
        <div class="card-header fw-semibold bg-white">Daftar Jadwal</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Bus</th>
                            <th>Rute</th>
                            <th>Waktu Berangkat</th>
                            <th>Waktu Tiba</th>
                            <th>Harga</th>
                            <th>Sisa Kursi</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $i => $schedule)
                            @php
                                $capacity = $schedule->bus->capacity ?? 0;
                                $booked = $schedule->bookings()->sum('num_of_seats');
                                $sisa = $capacity - $booked;
                            @endphp
                            <tr>
                                <td>{{ $schedules->firstItem() + $i }}</td>
                                <td>{{ $schedule->bus->name }} ({{ $schedule->bus->plate_number }})</td>
                                <td>{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</td>
                                <td>{{ $schedule->departure_time }}</td>
                                <td>{{ $schedule->arrival_time }}</td>
                                <td>Rp{{ number_format($schedule->price, 0, ',', '.') }}</td>
                                <td>
                                    @if($sisa > 0)
                                        <span class="badge bg-success">{{ $sisa }}</span>
                                    @else
                                        <span class="badge bg-danger">Penuh</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#edit{{ $schedule->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- Edit Form --}}
                            <tr class="collapse bg-light" id="edit{{ $schedule->id }}">
                                <td colspan="8">
                                    <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" class="row g-3 p-3">
                                        @csrf
                                        @method('PATCH')
                                        <div class="col-md-3">
                                            <select name="bus_id" class="form-select">
                                                @foreach($buses as $bus)
                                                    <option value="{{ $bus->id }}" @selected($schedule->bus_id == $bus->id)>
                                                        {{ $bus->name }} ({{ $bus->plate_number }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="route_id" class="form-select">
                                                @foreach($routes as $r)
                                                    <option value="{{ $r->id }}" @selected($schedule->route_id == $r->id)>
                                                        {{ $r->origin }} → {{ $r->destination }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="datetime-local" name="departure_time" value="{{ $schedule->departure_time }}" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="datetime-local" name="arrival_time" value="{{ $schedule->arrival_time }}" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="price" value="{{ $schedule->price }}" class="form-control">
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-primary">
                                                <i class="bi bi-check2-circle"></i> Simpan
                                            </button>
                                            <button class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#edit{{ $schedule->id }}">Batal</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada data jadwal.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
x`