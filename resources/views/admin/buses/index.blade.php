<!-- resources/views/admin/buses/index.blade.php -->

@extends('layouts.admin')

@section('title', 'Manajemen Bus')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Manajemen Bus</h1>
            <p class="text-muted">Kelola data armada bus</p>
        </div>
        <button class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Tambah Bus
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Bus</th>
                            <th>Kelas</th>
                            <th>Kapasitas</th>
                            <th>Plat Nomor</th>
                            <th>Fasilitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Bus::all() as $bus)
                        <tr>
                            <td>{{ $bus->name }}</td>
                            <td>
                                <span class="badge bg-info">{{ $bus->bus_class }}</span>
                            </td>
                            <td>{{ $bus->capacity }} kursi</td>
                            <td>{{ $bus->plate_number }}</td>
                            <td>
                                <small>{{ Str::limit($bus->facilities, 50) }}</small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection