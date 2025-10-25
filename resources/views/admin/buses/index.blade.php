<!-- resources/views/admin/buses/index.blade.php -->

@extends('layouts.app')

@section('title', 'Manajemen Bus')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Manajemen Bus</h1>
            <p class="text-muted">Kelola data armada bus</p>
        </div>

        {{-- Tombol Tambah: HARUS ada href ke route create --}}
        <a href="{{ route('admin.buses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Tambah Bus
        </a>
    </div>

    {{-- Flash success/error (opsional) --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nama Bus</th>
                            <th>Kelas</th>
                            <th>Kapasitas</th>
                            <th>Plat Nomor</th>
                            <th>Fasilitas</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Lebih baik kirim $buses dari Controller. 
                             Tapi kalau belum, fallback ke Bus::all() --}}
                        @php
                            use Illuminate\Support\Str;
                            $rows = isset($buses) ? $buses : \App\Models\Bus::all();
                        @endphp

                        @forelse($rows as $bus)
                        <tr>
                            <td>{{ $bus->name }}</td>
                            <td>
                                <span class="badge bg-info">{{ $bus->bus_class }}</span>
                            </td>
                            <td>{{ $bus->capacity }} kursi</td>
                            <td>{{ $bus->plate_number }}</td>
                            <td><small>{{ Str::limit($bus->facilities, 50) }}</small></td>
                            <td class="text-end">
                                <div class="btn-group" role="group" aria-label="Aksi">
                                    {{-- Edit: pakai href ke edit --}}
                                    <a href="{{ route('admin.buses.edit', $bus->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Hapus: pakai FORM method DELETE + CSRF --}}
                                    <form action="{{ route('admin.buses.destroy', $bus->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin hapus bus ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data bus.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination kalau dari controller pakai paginate() --}}
                @isset($buses)
                    <div class="mt-3">{{ $buses->links() }}</div>
                @endisset
            </div>
        </div>
    </div>
</div>
@endsection
