<!-- resources/views/user/dashboard.blade.php -->

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <!-- Welcome Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2">Halo, {{ Auth::user()->name }}</h1>
                    <p class="text-muted">Kelola perjalanan dan pemesanan tiket bus Anda</p>
                </div>
                <a href="/schedules" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Pesan Tiket Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-5">
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="mb-3">
                    <i class="fas fa-ticket-alt fa-2x text-primary"></i>
                </div>
                <div class="number">{{ Auth::user()->bookings->count() }}</div>
                <p class="text-muted mb-0">Total Pemesanan</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="mb-3">
                    <i class="fas fa-clock fa-2x text-warning"></i>
                </div>
                <div class="number">{{ Auth::user()->bookings()->where('status', 'pending')->count() }}</div>
                <p class="text-muted mb-0">Pending</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="mb-3">
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                </div>
                <div class="number">{{ Auth::user()->bookings()->where('status', 'confirmed')->count() }}</div>
                <p class="text-muted mb-0">Confirmed</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="mb-3">
                    <i class="fas fa-wallet fa-2x text-info"></i>
                </div>
                <div class="number">Rp {{ number_format(Auth::user()->bookings()->sum('total_price'), 0, ',', '.') }}</div>
                <p class="text-muted mb-0">Total Pengeluaran</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Aksi Cepat</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="/schedules" class="btn btn-outline-primary w-100 py-3 d-flex align-items-center justify-content-center">
                                <i class="fas fa-search me-3 fa-lg"></i>
                                <div class="text-start">
                                    <div class="fw-bold">Cari Tiket</div>
                                    <small class="text-muted">Temukan jadwal bus</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('user.bookings') }}" class="btn btn-outline-success w-100 py-3 d-flex align-items-center justify-content-center">
                                <i class="fas fa-history me-3 fa-lg"></i>
                                <div class="text-start">
                                    <div class="fw-bold">Riwayat</div>
                                    <small class="text-muted">Lihat pemesanan</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#" class="btn btn-outline-info w-100 py-3 d-flex align-items-center justify-content-center">
                                <i class="fas fa-user me-3 fa-lg"></i>
                                <div class="text-start">
                                    <div class="fw-bold">Profil</div>
                                    <small class="text-muted">Kelola akun</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pemesanan Terbaru</h5>
            <a href="{{ route('user.bookings') }}" class="btn btn-sm btn-outline-primary">
                Lihat Semua
            </a>
        </div>
        <div class="card-body">
            @if($recentBookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kode Booking</th>
                                <th>Rute</th>
                                <th>Tanggal</th>
                                <th class="text-center">Kursi</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBookings as $booking)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $booking->booking_code }}</strong>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</div>
                                        <small class="text-muted">{{ $booking->schedule->bus->name }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $booking->schedule->departure_time->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $booking->schedule->departure_time->format('H:i') }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $booking->num_of_seats }}</span>
                                </td>
                                <td class="text-end">
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada pemesanan</h5>
                    <p class="text-muted mb-4">Mulai pesan tiket pertama Anda</p>
                    <a href="/schedules" class="btn btn-primary">Pesan Tiket</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection