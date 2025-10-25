@extends('layouts.app')

@push('styles')
<!-- Bootstrap Icons (jaga-jaga kalau belum global) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
    font-size: 1rem;
    line-height: 1;
}
</style>
@endpush

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

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('danger'))
        <div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>{{ session('danger') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>Periksa kembali input Anda.
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Urutkan berdasarkan ID desc (lebih ideal di controller) --}}
    @php
        $users = $users->sortByDesc('id');
    @endphp

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
                                        <!-- Detail -->
                                        <button class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal{{ $user->id }}"
                                                data-bs-toggle="tooltip" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <!-- Edit -->
                                        <button class="btn btn-outline-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $user->id }}"
                                                data-bs-toggle="tooltip" title="Edit User">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Hapus (selain diri sendiri) -->
                                        @if($user->id !== Auth::id())
                                        <button class="btn btn-outline-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $user->id }}"
                                                data-bs-toggle="tooltip" title="Hapus User">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled
                                                data-bs-toggle="tooltip" title="User Aktif (Anda)">
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
            <form id="editForm{{ $user->id }}" method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')
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
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Tambah User (BARU) -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus me-2"></i>Tambah Pengguna Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label"><i class="bi bi-person me-1"></i>Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="bi bi-envelope me-1"></i>Email</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Masukkan alamat email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="bi bi-lock me-1"></i>Password</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Masukkan password">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label"><i class="bi bi-person-badge me-1"></i>Role</label>
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
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Tambah User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection

@push('scripts')
<script>
// Pencarian real-time (pastikan script ini dimuat karena layout pakai @stack('scripts'))
document.addEventListener('DOMContentLoaded', function() {
    const searchInput  = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');

    if (searchInput && searchButton) {
        const tableRows = document.querySelectorAll('tbody tr');

        function performSearch() {
            const searchTerm = (searchInput.value || '').toLowerCase();
            tableRows.forEach(row => {
                const userName  = (row.cells[1]?.textContent || '').toLowerCase();
                const userEmail = (row.cells[2]?.textContent || '').toLowerCase();
                row.style.display = (userName.includes(searchTerm) || userEmail.includes(searchTerm)) ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', performSearch);
        searchButton.addEventListener('click', performSearch);
    }

    // Tooltip Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
});
</script>
@endpush
