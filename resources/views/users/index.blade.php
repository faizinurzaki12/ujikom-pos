@extends('layouts.app')

@section('title', 'Halaman Users')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/users/index.css') }}">
@endpush

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark mb-0">Pengguna</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pengguna
        </a>
    </div>
    <!-- tombol untuk search atau cari -->
    <form action="{{ route('admin.users') }}" method="GET" class="d-flex mb-4">
        <div class="input-group shadow-sm">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                class="form-control" 
                placeholder="Cari Username"
            >
            <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search me-1"></i> Search
            </button>   
            @if(request('search'))
            <a class="btn btn-outline-primary" href="{{ route('admin.users')}}">Reset</a>
            @endif
        </div>
    </form>
    <!-- tabel -->
    <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
        <div class="users-container">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="py-3 ps-3" style="width: 60px;">#</th>
                            <th scope="col" class="py-3">Name</th>
                            <th scope="col" class="py-3">Email</th>
                            <th scope="col" class="py-3">Role</th>
                            <th scope="col" class="py-3 text-end pe-3" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $item)
                            <tr>
                                <td data-label="#" class="ps-3">{{ $loop->iteration }}</td>
                                <td data-label="Name" class="fw-bold">{{ $item->name }}</td>
                                <td data-label="Email">{{ $item->email }}</td>
                                <td data-label="Role">
                                    <span class="badge {{ $item->role == 'admin' ? 'bg-info' : 'bg-secondary' }}">
                                        {{ $item->role->name ?? $item->role }}
                                    </span>
                                </td>
                                <td data-label="Aksi" class="text-end pe-3">
                                    <div class="d-inline-flex align-items-center justify-content-end gap-1">
                                        <a href="{{ route('admin.users.edit', $item->id) }}" title="edit" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button title="hapus" type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- pagination -->
        @if(method_exists($users, 'links'))
        <div class="mt-3">
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection