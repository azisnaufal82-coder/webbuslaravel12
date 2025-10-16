<!-- resources/views/home.blade.php -->

@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container mt-4">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded">
                <h1 class="display-4">Selamat Datang di BusTicket</h1>
                <p class="lead">Sistem pemesanan tiket bus terpercaya dan nyaman</p>
                <a href="/schedules" class="btn btn-light btn-lg">Cari Jadwal</a>
            </div>
        </div>
    </div>

    <!-- Popular Schedules -->
    <div class="row mt-5">
        <div class="col-12">
            <h2>Jadwal Populer</h2>
            <div class="row">
                @foreach($popularSchedules as $schedule)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $schedule->route->origin }} - {{ $schedule->route->destination }}</h5>
                            <p class="card-text">
                                <strong>Bus:</strong> {{ $schedule->bus->name }} ({{ $schedule->bus->bus_class }})<br>
                                <strong>Berangkat:</strong> {{ $schedule->departure_time->format('d M Y H:i') }}<br>
                                <strong>Harga:</strong> Rp {{ number_format($schedule->price, 0, ',', '.') }}<br>
                                <strong>Kursi Tersedia:</strong> {{ $schedule->available_seats }}
                            </p>
                        </div>
                        <div class="card-footer">
                            @auth
                                @if(!Auth::user()->isAdmin())
                                    <a href="/schedules" class="btn btn-primary w-100">Pesan Sekarang</a>
                                @endif
                            @else
                                <a href="/login" class="btn btn-primary w-100">Login untuk Pesan</a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="text-center mb-4">Mengapa Memilih Kami?</h2>
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fs-4">🚌</i>
                    </div>
                    <h4>Armada Nyaman</h4>
                    <p>Bus terbaru dengan fasilitas lengkap untuk kenyamanan perjalanan Anda</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fs-4">💰</i>
                    </div>
                    <h4>Harga Terjangkau</h4>
                    <p>Harga kompetitif dengan kualitas pelayanan terbaik</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fs-4">⚡</i>
                    </div>
                    <h4>Pemesanan Mudah</h4>
                    <p>Proses pemesanan cepat dan mudah melalui platform online</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection