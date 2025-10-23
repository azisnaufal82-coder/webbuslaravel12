@extends('layouts.app')

@section('styles')
<!-- Tambahkan Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}
.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}
.btn i {
    font-size: 0.9rem;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Manajemen Pengguna</h1>
            <p class="text-muted">Kelola data pengguna sistem WebBus</p>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-plus-circle me-1"></i>Tambah User
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $users->count() }}</h4>
                            <p>Total Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $users->where('role', 'user')->count() }}</h4>
                            <p>Regular Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $users->where('role', 'admin')->count() }}</h4>
                            <p>Admin Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-gear fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $users->where('created_at', '>=', now()->subDays(30))->count() }}</h4>
                            <p>User Baru (30 hari)</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-plus fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Daftar Pengguna Sistem</h5>
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" placeholder="Cari pengguna..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button" id="searchButton">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nama Pengguna</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td><strong>#{{ $user->id }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <span class="text-white fw-bold">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                            <small class="text-muted">ID: {{ $user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }} p-2">
                                        <i class="bi bi-{{ $user->role == 'admin' ? 'shield-check' : 'person' }} me-1"></i>
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-nowrap">
                                        <div>{{ $user->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $user->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success p-2">
                                        <i class="bi bi-check-circle me-1"></i>Aktif
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Tombol Detail -->
                                        <button class="btn btn-outline-primary btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailModal{{ $user->id }}"
                                                title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-outline-warning btn-sm"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal{{ $user->id }}"
                                                title="Edit User">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        
                                        <!-- Tombol Hapus (hanya untuk user lain) -->
                                        @if($user->id !== Auth::id())
                                        <button class="btn btn-outline-danger btn-sm"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $user->id }}"
                                                title="Hapus User">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        @else
                                        <!-- Untuk user sendiri, tombol disabled -->
                                        <button class="btn btn-outline-secondary btn-sm" disabled
                                                title="User Aktif (Anda)">
                                            <i class="bi bi-person-check"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-people display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">Belum ada data pengguna</h4>
                    <p class="text-muted">Tidak ada pengguna yang terdaftar dalam sistem.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-plus-circle me-1"></i>Tambah User Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Detail -->
@foreach($users as $user)
<div class="modal fade" id="detailModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-circle me-2"></i>Detail Pengguna
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <span class="text-white fw-bold fs-3">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                </div>
                <div class="row">
                    <div class="col-6">
                        <strong><i class="bi bi-person-badge me-1"></i>Role:</strong><br>
                        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }} mt-1">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    <div class="col-6">
                        <strong><i class="bi bi-circle-fill me-1"></i>Status:</strong><br>
                        <span class="badge bg-success mt-1">Aktif</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong><i class="bi bi-calendar-plus me-1"></i>Tanggal Daftar:</strong><br>
                        {{ $user->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="col-6">
                        <strong><i class="bi bi-arrow-clockwise me-1"></i>Terakhir Update:</strong><br>
                        {{ $user->updated_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Tutup
                </button>
                <button type="button" class="btn btn-warning" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editModal{{ $user->id }}"
                        data-bs-dismiss="modal">
                    <i class="bi bi-pencil-square me-1"></i>Edit User
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Edit -->
@foreach($users as $user)
<div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2"></i>Edit Pengguna
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm{{ $user->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name{{ $user->id }}" class="form-label">
                            <i class="bi bi-person me-1"></i>Nama
                        </label>
                        <input type="text" class="form-control" id="name{{ $user->id }}" 
                               name="name" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email{{ $user->id }}" class="form-label">
                            <i class="bi bi-envelope me-1"></i>Email
                        </label>
                        <input type="email" class="form-control" id="email{{ $user->id }}" 
                               name="email" value="{{ $user->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="role{{ $user->id }}" class="form-label">
                            <i class="bi bi-person-badge me-1"></i>Role
                        </label>
                        <select class="form-select" id="role{{ $user->id }}" name="role">
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary" onclick="handleEditUser({{ $user->id }})">
                        <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Hapus -->
@foreach($users as $user)
@if($user->id !== Auth::id())
<div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>Hapus Pengguna
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center text-danger mb-3">
                    <i class="bi bi-exclamation-triangle display-4"></i>
                </div>
                <p class="text-center">Apakah Anda yakin ingin menghapus pengguna ini?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-person me-1"></i><strong>{{ $user->name }}</strong><br>
                    <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                </div>
                <p class="text-muted small text-center">
                    <i class="bi bi-info-circle me-1"></i>Data yang dihapus tidak dapat dikembalikan.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-danger" onclick="handleDeleteUser({{ $user->id }})">
                    <i class="bi bi-trash me-1"></i>Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

<!-- Modal Tambah User -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus me-2"></i>Tambah Pengguna Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-1"></i>Nama Lengkap
                        </label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               placeholder="Masukkan alamat email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-1"></i>Password
                        </label>
                        <input type="password" class="form-control" id="password" name="password" required 
                               placeholder="Masukkan password">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">
                            <i class="bi bi-person-badge me-1"></i>Role
                        </label>
                        <select class="form-select" id="role" name="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary" onclick="handleAddUser()">
                        <i class="bi bi-plus-circle me-1"></i>Tambah User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Inisialisasi ketika dokumen siap
document.addEventListener('DOMContentLoaded', function() {
    console.log('Halaman Manajemen User dimuat');
    
    // Fungsi pencarian real-time
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    
    if (searchInput && searchButton) {
        const tableRows = document.querySelectorAll('tbody tr');
        
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase();
            
            tableRows.forEach(row => {
                const userName = row.cells[1].textContent.toLowerCase();
                const userEmail = row.cells[2].textContent.toLowerCase();
                
                if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        searchInput.addEventListener('input', performSearch);
        searchButton.addEventListener('click', performSearch);
    }
    
    // Inisialisasi tooltip
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Fungsi untuk handle edit user
function handleEditUser(userId) {
    const form = document.getElementById('editForm' + userId);
    if (!form) {
        console.error('Form edit tidak ditemukan untuk user ID:', userId);
        return;
    }
    
    const formData = new FormData(form);
    const userData = {
        name: formData.get('name'),
        email: formData.get('email'),
        role: formData.get('role')
    };
    
    // Validasi sederhana
    if (!userData.name || !userData.email) {
        showNotification('Harap isi semua field yang required!', 'warning');
        return;
    }
    
    // Simulasi update user
    console.log('Update user:', userId, userData);
    showNotification(`User "${userData.name}" berhasil diupdate!`, 'success');
    
    // Tutup modal
    const modalElement = document.getElementById('editModal' + userId);
    if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        }
    }
}

// Fungsi untuk handle hapus user
function handleDeleteUser(userId) {
    const modalElement = document.getElementById('deleteModal' + userId);
    if (!modalElement) {
        console.error('Modal hapus tidak ditemukan untuk user ID:', userId);
        return;
    }
    
    const userName = modalElement.querySelector('.alert strong')?.textContent || 'User';
    
    // Konfirmasi sekali lagi
    if (!confirm(`Anda yakin ingin menghapus user "${userName}"?`)) {
        return;
    }
    
    // Simulasi delete user
    console.log('Delete user:', userId);
    showNotification(`User "${userName}" berhasil dihapus!`, 'success');
    
    // Tutup modal
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
}

// Fungsi untuk handle tambah user
function handleAddUser() {
    const form = document.getElementById('addUserForm');
    if (!form) {
        console.error('Form tambah user tidak ditemukan');
        return;
    }
    
    const formData = new FormData(form);
    const userData = {
        name: formData.get('name'),
        email: formData.get('email'),
        password: formData.get('password'),
        role: formData.get('role')
    };
    
    // Validasi
    if (!userData.name || !userData.email || !userData.password) {
        showNotification('Harap isi semua field yang required!', 'warning');
        return;
    }
    
    // Simulasi tambah user
    console.log('Add user:', userData);
    showNotification(`User "${userData.name}" berhasil ditambahkan!`, 'success');
    
    // Tutup modal dan reset form
    const modalElement = document.getElementById('addUserModal');
    if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        }
    }
    form.reset();
}

// Fungsi notifikasi
function showNotification(message, type = 'success') {
    // Hapus notifikasi sebelumnya
    const existingNotifications = document.querySelectorAll('.alert.position-fixed');
    existingNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    
    let iconClass = 'bi-check-circle';
    if (type === 'warning') iconClass = 'bi-exclamation-triangle';
    if (type === 'danger') iconClass = 'bi-x-circle';
    
    notification.innerHTML = `
        <i class="bi ${iconClass} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove setelah 5 detik
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}
</script>
@endsection