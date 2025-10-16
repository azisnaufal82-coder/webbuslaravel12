<!-- resources/views/schedules/index.blade.php -->

@extends('layouts.app')

@section('title', 'Cari Tiket Bus')

@section('content')
<!-- Search Section -->
<div class="search-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="text-center mb-4">Temukan Perjalanan Anda</h2>
                <form method="GET" action="{{ route('schedules.public') }}">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="form-label fw-bold">Dari</label>
                                    <input type="text" class="form-control form-control-lg" name="origin" 
                                           value="{{ request('origin') }}" placeholder="Kota asal">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-bold">Ke</label>
                                    <input type="text" class="form-control form-control-lg" name="destination" 
                                           value="{{ request('destination') }}" placeholder="Kota tujuan">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-search me-2"></i>Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    @if(request()->has('origin') || request()->has('destination'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-light">
                <i class="fas fa-filter me-2"></i>
                Menampilkan hasil dari 
                @if(request('origin')) <strong>{{ request('origin') }}</strong> @endif
                @if(request('destination')) ke <strong>{{ request('destination') }}</strong> @endif
                • <strong>{{ $schedules->total() }}</strong> jadwal ditemukan
            </div>
        </div>
    </div>
    @endif

    @if($schedules->count() > 0)
        @foreach($schedules as $schedule)
        <div class="schedule-card">
            <div class="row align-items-center">
                <!-- Route & Time Info -->
                <div class="col-md-6">
                    <div class="row align-items-center mb-3">
                        <div class="col-auto">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="fas fa-bus text-primary fa-lg"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="mb-1">{{ $schedule->route->origin }} <span class="route-arrow">→</span> {{ $schedule->route->destination }}</h5>
                            <p class="text-muted mb-0">{{ $schedule->bus->name }} • {{ $schedule->bus->bus_class }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <small class="text-muted d-block">Berangkat</small>
                                <strong>{{ $schedule->departure_time->format('d M Y') }}</strong>
                                <div class="text-primary">{{ $schedule->departure_time->format('H:i') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <small class="text-muted d-block">Tiba</small>
                                <strong>{{ $schedule->arrival_time->format('d M Y') }}</strong>
                                <div class="text-success">{{ $schedule->arrival_time->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    @if($schedule->bus->facilities)
                    <div class="mt-2">
                        @php
                            $facilities = is_array($schedule->bus->facilities) 
                                ? $schedule->bus->facilities 
                                : explode(',', $schedule->bus->facilities);
                        @endphp
                        @foreach(array_slice($facilities, 0, 3) as $facility)
                            <span class="badge bg-light text-dark border me-1">{{ trim($facility) }}</span>
                        @endforeach
                        @if(count($facilities) > 3)
                            <span class="badge bg-light text-dark border">+{{ count($facilities) - 3 }}</span>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Availability & Price -->
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <small class="text-muted d-block">Kursi Tersedia</small>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-users text-muted me-2"></i>
                            <span class="fw-bold">{{ $schedule->available_seats }}</span>
                            <span class="text-muted ms-1">/ {{ $schedule->bus->capacity }}</span>
                        </div>
                    </div>
                    @if($schedule->available_seats > 0)
                        <span class="badge bg-success">Tersedia</span>
                    @else
                        <span class="badge bg-danger">Habis</span>
                    @endif
                </div>

                <!-- Price & Action -->
                <div class="col-md-3 text-center">
                    <div class="price-tag mb-2">Rp {{ number_format($schedule->price, 0, ',', '.') }}</div>
                    <small class="text-muted d-block mb-3">per kursi</small>
                    
                    @auth
                        @if(!Auth::user()->isAdmin())
                            @if($schedule->available_seats > 0)
                                <button type="button" class="btn btn-primary w-100 mb-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#bookingModal{{ $schedule->id }}">
                                    Pesan Sekarang
                                </button>
                            @else
                                <button class="btn btn-secondary w-100 mb-2" disabled>
                                    Habis
                                </button>
                            @endif
                        @endif
                    @else
                        <a href="/login" class="btn btn-outline-primary w-100 mb-2">
                            Login untuk Pesan
                        </a>
                    @endauth
                    
                    <button type="button" class="btn btn-outline-secondary btn-sm w-100" 
                            data-bs-toggle="modal" 
                            data-bs-target="#detailModal{{ $schedule->id }}">
                        <i class="fas fa-info-circle me-1"></i>Detail
                    </button>
                </div>
            </div>
        </div>

        <!-- Booking Modal -->
        @auth
            @if(!Auth::user()->isAdmin())
                @include('schedules.partials.booking-modal', ['schedule' => $schedule])
            @endif
        @endauth

        <!-- Detail Modal -->
        @include('schedules.partials.detail-modal', ['schedule' => $schedule])
        @endforeach

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $schedules->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Tidak ada jadwal ditemukan</h4>
                <p class="text-muted mb-4">Coba ubah pencarian Anda atau lihat rute lainnya</p>
                <a href="{{ route('schedules.public') }}" class="btn btn-primary">
                    <i class="fas fa-redo me-2"></i>Coba Lagi
                </a>
            </div>
        </div>
    @endif
</div>
@endsection