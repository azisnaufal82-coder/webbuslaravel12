@extends('layouts.app')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="container-xxl py-4" style="max-width: 900px;">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Detail Pemesanan</h1>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            {{-- Kode booking: badge biru kalem --}}
            <div class="mb-3">
                <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                    {{ $booking->booking_code }}
                </span>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="small text-muted">Pemesan</div>
                    <div class="fw-semibold">{{ $booking->user->name ?? '-' }}</div>
                    <div class="text-muted">{{ $booking->user->email ?? '' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Status</div>
                    @php
                        $badge = [
                            'pending'   => 'bg-warning text-dark',
                            'confirmed' => 'bg-success',
                            'cancelled' => 'bg-secondary'
                        ][$booking->status] ?? 'bg-light text-dark';
                    @endphp
                    <span class="badge {{ $badge }}">{{ ucfirst($booking->status) }}</span>
                </div>

                <div class="col-md-6">
                    <div class="small text-muted">Bus</div>
                    <div class="fw-semibold">{{ $booking->schedule->bus->name ?? '-' }}</div>
                    <div class="text-muted small">{{ $booking->schedule->bus->plate_number ?? '' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Rute</div>
                    <div>{{ $booking->schedule->route->origin ?? '-' }} → {{ $booking->schedule->route->destination ?? '-' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="small text-muted">Berangkat</div>
                    <div>{{ optional($booking->schedule->departure_time)->format('d M Y H:i') }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Tiba</div>
                    <div>{{ optional($booking->schedule->arrival_time)->format('d M Y H:i') }}</div>
                </div>

                <div class="col-md-6">
                    <div class="small text-muted">Jumlah Kursi</div>
                    <div class="fw-semibold">{{ $booking->num_of_seats }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Total</div>
                    <div class="fw-semibold">Rp{{ number_format($booking->total_price,0,',','.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
