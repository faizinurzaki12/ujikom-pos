@extends('layouts.auth')

@section('title', 'Login - CellularPOS')

@section('content')
<div class="row justify-content-center align-items-center">
  <div class="col-auto">
    <div class="card login-card-anim text-center shadow-lg p-4 bg-white rounded-4" style="width: 23rem; border: none;">

      <div class="brand-icon text-white">
        <i class="bi bi-phone-vibrate fs-2"></i>
      </div>

      <h4 class="fw-bold mb-1 text-dark">POS Handphone Danzz</h4>
      <p class="text-muted small mb-4">Sistem Kasir & Operasional Konter HP</p>

      <div class="card-body p-0">
        <form action="{{ route('auth') }}" method="POST" id="formLogin">
          @csrf

          <div class="mb-3 text-start">
            <label for="email" class="form-label small fw-semibold">Email</label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person"></i></span>
              <input type="email" name="email" value="{{ old('email') }}" placeholder="kasir@konter.com" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" required />
            </div>
            @error('email')
            <div class="badge text-bg-danger mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3 text-start">
            <label for="password" class="form-label small fw-semibold">Password</label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-lock"></i></span>
              <input type="password" name="password" placeholder="Minimal 8 karakter" class="form-control border-start-0 @error('password') is-invalid @enderror" id="password" required />
            </div>
            @error('password')
            <div class="badge text-bg-danger mt-1">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" id="btnSubmit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
          </button>
        </form>
      </div>

      <!-- <div class="mt-4 pt-3 border-top">
        <small class="text-muted" style="font-size: 0.75rem;">CellularPOS v1.0 &bull; Akses Kasir & Teknisi</small>
      </div> -->

    </div>
  </div>
</div>

<script>
  document.getElementById('formLogin').addEventListener('submit', function(e) {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerHTML = `
    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
    Tunggu bentar....
    `;
  });
</script>
@endsection