<!-- memanggil file app.blade.php -->
@extends('layouts.app')

<!-- mengirimkan nilai title ke title untuk di tampilkan -->
@section('title', 'users')

<!-- batas awal konten  -->
@section('content')
<h1>Halaman Users</h1>
<a href="{{ route('admin.users.create')}}" class="btn btn-primary">Create</a>
<!-- button seacrh -->
<form action="{{ route('admin.users') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            class="form-control" 
            placeholder="Search username or email"
        >
        <button class="btn btn-outline-secondary" type="submit">
            Search
        </button>
    </div>
</form>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Role</th>
      <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @foreach($users as $user)
    <tr>
      <td>{{ $users->firstItem() + $loop->index }}</td>
      <td>{{ $user->name }}</td>
      <td>{{ $user->email }}</td>
      <td>{{ $user->role->name }}</td>
      <td>
        <a href="{{ route('admin.users.edit', $user )}}" class="btn btn-sm btn-warning">
            Edit Akun
        </a>
        ||
        <form action="{{ route('admin.users.destroy', $user )}}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin Hapus user ini?')">
                Hapus
            </button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<!-- batas akhir konten -->
@endsection