@extends('layouts.app')

@section('title', 'Edit Jenis Produk')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark mb-0">Edit Jenis Produk</h1>
        <a href="{{ route('jenis.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
        <form action="{{ route('jenis.update', $jenis) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama_jenis" class="form-label fw-medium">Nama Jenis Produk</label>
                <input
                    type="text"
                    name="nama_jenis"
                    id="nama_jenis"
                    class="form-control @error('nama_jenis') is-invalid @enderror"
                    value="{{ old('nama_jenis', $jenis->nama_jenis) }}"
                    placeholder="Contoh: Makanan, Minuman, Elektronik"
                    required
                >
                @error('nama_jenis')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('jenis.index') }}" class="btn btn-light px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection