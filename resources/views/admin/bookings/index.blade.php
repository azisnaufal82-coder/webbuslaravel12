<!-- resources/views/admin/bookings/index.blade.php -->

@extends('layouts.admin')

@section('title', 'Manajemen Pemesanan')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Manajemen Pemesanan</h1>
            <p class="text-muted">Kelola semua pemesanan tiket dari pengguna</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Pemesanan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>User</th>
                            <th>Rute</th>
                            <th>Tanggal</th>
                            <th>Kursi</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $bookings = \App\Models\Booking::with(['user', 'schedule.bus', 'schedule.route'])
                                ->latest()
                                ->take(10)
                                ->get();
                        @endphp
                        
                        @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $booking->booking_code }}</strong>
                            </td>
                            <td>{{ $booking->user->name }}</td>
                            <td>
                                {{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}
                                <br>
                                <small class="text-muted">{{ $booking->schedule->bus->name }}</small>
                            </td>
                            <td>
                                {{ $booking->schedule->departure_time->format('d M Y') }}
                                <br>
                                <small class="text-muted">{{ $booking->schedule->departure_time->format('H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $booking->num_of_seats }}</span>
                            </td>
                            <td>
                                <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @if($booking->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($booking->status == 'confirmed')
                                    <span class="badge bg-success">Confirmed</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary" title="Edit Status">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada data pemesanan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $bookings->count() }} dari {{ \App\Models\Booking::count() }} pemesanan
                </div>
                <nav>
                    <ul class="pagination mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Previous</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection