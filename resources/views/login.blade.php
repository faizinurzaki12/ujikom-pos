@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row mt-5 justify-content-center align-items-center" style="height: 85vh;">
  <div class="col-auto">
    <div class="card text-center shadow p-3 bg-white bg-opacity-75 backdrop-blur" style="width: 22rem; border-radius: 12px">
      <h4 class="card-header bg-transparent border-0 fw-bold pt-3">Login POS</h4>
      <div class="card-body">
        <form action="{{ route('auth') }}" method="POST">
          @csrf

          <div class="mb-3 text-start">
            <label for="email" class="form-label">Email Anda</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@gmail.com" class="form-control" id="email" required />
            @error('email')
            <div class="badge text-bg-danger mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3 text-start">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" placeholder="Minimal 8 karakter" class="form-control" id="password" required />
            @error('password')
            <div class="badge text-bg-danger mt-1">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Masuk Sekarang</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection