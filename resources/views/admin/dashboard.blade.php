<!-- resources/views/admin/dashboard.blade.php -->

@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Dashboard Admin</h1>
            <p class="text-muted">Ringkasan sistem dan statistik</p>
        </div>
        <div class="text-muted">
            <i class="fas fa-calendar me-2"></i> {{ now()->format('d F Y') }}
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="number">{{ $stats['users_count'] }}</div>
                            <div>Total Pengguna</div>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="number">{{ $stats['buses_count'] }}</div>
                            <div>Total Bus</div>
                        </div>
                        <i class="fas fa-bus fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="number">{{ $stats['bookings_count'] }}</div>
                            <div>Total Pemesanan</div>
                        </div>
                        <i class="fas fa-ticket-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="number">{{ $stats['schedules_count'] }}</div>
                            <div>Total Jadwal</div>
                        </div>
                        <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Statistik Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="text-primary fw-bold fs-4">{{ $stats['routes_count'] }}</div>
                                <div class="text-muted">Total Rute</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="text-success fw-bold fs-4">{{ \App\Models\Booking::where('status', 'confirmed')->count() }}</div>
                                <div class="text-muted">Pemesanan Confirmed</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="text-warning fw-bold fs-4">{{ \App\Models\Booking::where('status', 'pending')->count() }}</div>
                                <div class="text-muted">Pemesanan Pending</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="text-info fw-bold fs-4">Rp {{ number_format(\App\Models\Booking::sum('total_price'), 0, ',', '.') }}</div>
                                <div class="text-muted">Total Pendapatan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/admin/buses" class="btn btn-outline-primary text-start">
                            <i class="fas fa-bus me-2"></i> Kelola Bus
                        </a>
                        <a href="/admin/schedules" class="btn btn-outline-success text-start">
                            <i class="fas fa-calendar-alt me-2"></i> Kelola Jadwal
                        </a>
                        <a href="/admin/bookings" class="btn btn-outline-warning text-start">
                            <i class="fas fa-ticket-alt me-2"></i> Kelola Pemesanan
                        </a>
                        <a href="/admin/users" class="btn btn-outline-info text-start">
                            <i class="fas fa-users me-2"></i> Kelola Pengguna
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Jenis</th>
                                    <th>Deskripsi</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-ticket-alt text-primary me-2"></i> Pemesanan</td>
                                    <td>Pemesanan tiket baru dibuat</td>
                                    <td>{{ now()->subMinutes(15)->format('d M Y H:i') }}</td>
                                    <td><span class="badge bg-success">Baru</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-bus text-success me-2"></i> Bus</td>
                                    <td>Bus baru ditambahkan ke sistem</td>
                                    <td>{{ now()->subHours(2)->format('d M Y H:i') }}</td>
                                    <td><span class="badge bg-info">Selesai</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-calendar text-warning me-2"></i> Jadwal</td>
                                    <td>Jadwal keberangkatan diperbarui</td>
                                    <td>{{ now()->subDays(1)->format('d M Y H:i') }}</td>
                                    <td><span class="badge bg-warning">Updated</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection