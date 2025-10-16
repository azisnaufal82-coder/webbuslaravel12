<!-- resources/views/user/booking-confirmation.blade.php -->

@extends('layouts.app')

@section('title', 'Konfirmasi Pemesanan')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Success Card -->
            <div class="card card-hover border-0 shadow-lg">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-check-circle fa-3x text-success"></i>
                        </div>
                        <h2 class="text-success mb-3">Pemesanan Berhasil!</h2>
                        <p class="text-muted mb-4">Tiket Anda telah berhasil dipesan. Detail pemesanan telah dikirim ke email Anda.</p>
                    </div>

                    <!-- Booking Details -->
                    <div class="card border-0 bg-light mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-4">Detail Pemesanan</h5>
                            <div class="row text-start">
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Kode Booking</small>
                                    <h4 class="text-primary">{{ $booking->booking_code }}</h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Status</small>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success fs-6">
                                        <i class="fas fa-check me-1"></i>Confirmed
                                    </span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Rute</small>
                                    <p class="fw-bold mb-0">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Tanggal Berangkat</small>
                                    <p class="fw-bold mb-0">{{ $booking->schedule->departure_time->format('d M Y H:i') }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Jumlah Kursi</small>
                                    <p class="fw-bold mb-0">{{ $booking->num_of_seats }} Kursi</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Total Pembayaran</small>
                                    <h5 class="text-success mb-0">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                        <a href="{{ route('user.bookings') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-list me-2"></i>Lihat Riwayat
                        </a>
                        <a href="/schedules" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Pesan Lagi
                        </a>
                        <button class="btn btn-success btn-lg">
                            <i class="fas fa-print me-2"></i>Cetak Tiket
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection