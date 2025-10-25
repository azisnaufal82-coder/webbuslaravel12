@extends('layouts.app')

@section('title', 'Kelola Pemesanan')

@section('content')
<div class="container-xxl py-4" style="max-width:1400px;">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">Kelola Pemesanan</h1>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- Filter --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header fw-semibold bg-white">Filter Pemesanan</div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="" {{ ($status ?? '')==='' ? 'selected' : '' }}>Semua</option>
                        <option value="pending" {{ ($status ?? '')==='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ ($status ?? '')==='confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ ($status ?? '')==='cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal Berangkat</label>
                    <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal Berangkat</label>
                    <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Pemesanan --}}
    <div class="card shadow-sm border-0">
        <div class="card-header fw-semibold bg-white">Daftar Pemesanan</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:56px">#</th>
                            <th style="width:180px">Kode</th>
                            <th>Pemesan</th>
                            <th>Bus & Rute</th>
                            <th style="width:170px">Berangkat</th>
                            <th style="width:80px">Kursi</th>
                            <th style="width:140px">Total</th>
                            <th style="width:120px">Status</th>
                            <th class="text-end" style="width:180px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $i => $b)
                            <tr class="align-middle">
                                <td class="text-muted">{{ $bookings->firstItem() + $i }}</td>

                                {{-- Badge kode: biru kalem --}}
                                <td>
                                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                                        {{ $b->booking_code }}
                                    </span>
                                </td>

                                <td>
                                    <div class="fw-semibold">{{ $b->user->name ?? '-' }}</div>
                                    <div class="text-muted small">{{ $b->user->email ?? '' }}</div>
                                </td>

                                <td>
                                    <div class="fw-semibold">{{ $b->schedule->bus->name ?? '-' }}</div>
                                    <div class="text-muted small">
                                        {{ $b->schedule->route->origin ?? '-' }} → {{ $b->schedule->route->destination ?? '-' }}
                                    </div>
                                </td>

                                <td>{{ optional($b->schedule->departure_time)->format('d M Y H:i') }}</td>
                                <td>{{ $b->num_of_seats }}</td>
                                <td>Rp{{ number_format($b->total_price,0,',','.') }}</td>

                                <td>
                                    @php
                                        $badge = [
                                            'pending'   => 'bg-warning text-dark',
                                            'confirmed' => 'bg-success',
                                            'cancelled' => 'bg-secondary'
                                        ][$b->status] ?? 'bg-light text-dark';
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($b->status) }}</span>
                                </td>

                                <td class="text-end">
                                    <div class="btn-group" role="group" aria-label="Aksi">

                                        {{-- Ubah Status (dropdown ikon) --}}
                                        <div class="btn-group" role="group">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false"
                                                    title="Ubah Status">
                                                <i class="bi bi-gear"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li class="px-3 pt-2 pb-1 text-muted small">Ubah Status</li>
                                                <li><hr class="dropdown-divider"></li>

                                                <li>
                                                    <form action="{{ route('admin.bookings.update', $b) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="confirmed">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="bi bi-check2-circle me-2"></i>Set Confirmed
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.bookings.update', $b) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="bi bi-hourglass-split me-2"></i>Set Pending
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.bookings.update', $b) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="bi bi-x-circle me-2"></i>Set Cancelled
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>

                                        {{-- Detail --}}
                                        <a href="{{ route('admin.bookings.show', $b) }}"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted">Belum ada pemesanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $bookings->links() }}</div>
        </div>
    </div>
</div>
@endsection
