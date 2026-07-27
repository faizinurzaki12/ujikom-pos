@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<h4>Edit User</h4>
<form action="{{ route('admin.users.update', $user)}}" method="post">
    @include('users._form')
</form>
@endsection