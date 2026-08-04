@extends('layouts.app')

@section('title', 'Halaman Users')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark mb-0">Halaman Users</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Create User
        </a>
    </div>

    <!-- Button Search -->
    <form action="{{ route('admin.users') }}" method="GET" class="d-flex mb-4">
        <div class="input-group shadow-sm">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                class="form-control" 
                placeholder="Search username"
            >
            <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search me-1"></i> Search
            </button>
            @if(request('search'))
            <a class="btn btn-outline-primary" href="{{ route('admin.users')}}">Reset</a>
            @endif
        </div>
    </form>

    <!-- Tabel Data (Dibungkus Card dengan desain modern) -->
    <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td class="fw-medium">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if(isset($user->role->name))
                                <span class="badge {{ strtolower($user->role->name) == 'admin' ? 'bg-info text-dark' : 'bg-secondary' }}">
                                    {{ $user->role->name }}
                                </span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin Hapus user ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Tidak ada data user yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination (Opsional jika Anda menggunakan pagination di controller) -->
        @if(method_exists($users, 'links'))
        <div class="mt-3">
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection