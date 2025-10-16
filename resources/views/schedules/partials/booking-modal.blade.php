<!-- resources/views/schedules/partials/booking-modal.blade.php -->

<div class="modal fade" id="bookingModal{{ $schedule->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pesan Tiket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('user.bookings.store') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                    
                    <!-- Trip Summary -->
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Ringkasan Perjalanan</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Rute</small>
                                    <p class="mb-2 fw-bold">{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Tanggal</small>
                                    <p class="mb-2 fw-bold">{{ $schedule->departure_time->format('d M Y H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Bus</small>
                                    <p class="mb-0 fw-bold">{{ $schedule->bus->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Harga per kursi</small>
                                    <p class="mb-0 fw-bold text-primary">Rp {{ number_format($schedule->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seat Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Jumlah Kursi</label>
                        <div class="input-group input-group-lg">
                            <input type="number" class="form-control" name="num_of_seats" 
                                   min="1" max="{{ $schedule->available_seats }}" value="1" required>
                            <span class="input-group-text">Kursi</span>
                        </div>
                        <div class="form-text">
                            Maksimal {{ $schedule->available_seats }} kursi tersedia
                        </div>
                    </div>

                    <!-- Price Calculation -->
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="card-title text-primary mb-3">Detail Harga</h6>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <span>Harga per kursi</span>
                                </div>
                                <div class="col-6 text-end">
                                    <span>Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <span>Jumlah kursi</span>
                                </div>
                                <div class="col-6 text-end">
                                    <span id="seatCount">1</span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Total</strong>
                                </div>
                                <div class="col-6 text-end">
                                    <strong class="text-primary" id="totalPrice">Rp {{ number_format($schedule->price, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi Pemesanan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const numOfSeats = document.querySelector('input[name="num_of_seats"]');
    const totalPrice = document.getElementById('totalPrice');
    const seatCount = document.getElementById('seatCount');
    const pricePerSeat = {{ $schedule->price }};
    
    numOfSeats.addEventListener('input', function() {
        const seats = parseInt(this.value) || 0;
        const total = pricePerSeat * seats;
        totalPrice.textContent = 'Rp ' + total.toLocaleString('id-ID');
        seatCount.textContent = seats;
    });
});
</script>