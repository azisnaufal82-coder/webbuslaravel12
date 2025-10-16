<!-- resources/views/schedules/partials/detail-modal.blade.php -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Bus</h6>
                        <p><strong>Nama Bus:</strong> <span id="modal-bus-name"></span></p>
                        <p><strong>Plat Nomor:</strong> <span id="modal-bus-license"></span></p>
                        <p><strong>Kelas:</strong> <span id="modal-bus-class"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Perjalanan</h6>
                        <p><strong>Rute:</strong> <span id="modal-route"></span></p>
                        <p><strong>Keberangkatan:</strong> <span id="modal-departure"></span></p>
                        <p><strong>Kedatangan:</strong> <span id="modal-arrival"></span></p>
                        <p><strong>Harga:</strong> Rp <span id="modal-price"></span></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Kursi Tersedia</h6>
                        <p><span id="modal-available-seats"></span> dari <span id="modal-total-seats"></span> kursi</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="modal-book-btn" class="btn btn-primary">Pesan Sekarang</a>
            </div>
        </div>
    </div>
</div>

<script>
function showDetailModal(schedule) {
    // Isi data ke modal
    document.getElementById('modal-bus-name').textContent = schedule.bus.name;
    document.getElementById('modal-bus-license').textContent = schedule.bus.license_plate;
    document.getElementById('modal-bus-class').textContent = schedule.bus.class;
    document.getElementById('modal-route').textContent = schedule.route.start_point + ' - ' + schedule.route.end_point;
    document.getElementById('modal-departure').textContent = schedule.departure_time;
    document.getElementById('modal-arrival').textContent = schedule.arrival_time;
    document.getElementById('modal-price').textContent = new Intl.NumberFormat('id-ID').format(schedule.price);
    document.getElementById('modal-available-seats').textContent = schedule.available_seats;
    document.getElementById('modal-total-seats').textContent = schedule.bus.capacity;
    
    // Set link booking
    document.getElementById('modal-book-btn').href = `/bookings/create?schedule_id=${schedule.id}`;
    
    // Tampilkan modal
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    modal.show();
}
</script>