<!-- resources/views/user/bookings.blade.php -->

@extends('layouts.app')

@section('title', 'Riwayat Pemesanan')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2">Riwayat Pemesanan</h1>
                    <p class="text-muted">Semua tiket yang pernah Anda pesan</p>
                </div>
                <a href="/schedules" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Pesan Tiket Baru
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($bookings->count() > 0)
        <!-- Bookings Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Pemesanan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kode Booking</th>
                                <th>Rute</th>
                                <th>Tanggal</th>
                                <th class="text-center">Kursi</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                                <th>Pemesanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
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
                                <td>
                                    <small class="text-muted">{{ $booking->created_at->format('d M Y') }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Belum ada pemesanan</h4>
                <p class="text-muted mb-4">Mulai pesan tiket pertama Anda</p>
                <a href="/schedules" class="btn btn-primary">Pesan Tiket</a>
            </div>
        </div>
    @endif
</div>
@endsection