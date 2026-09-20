@extends('layouts.app')

@section('title', 'Halaman Jenis Produk')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/jenis/index.css') }}">
@endpush
@section('content')
<div class="container-fluid px-0 jenis-container">
    <div class="row align-middle align-items-center mb-4 position-relative">
        <div class="col-12 text-center">
            <h1 class="h3 fw-bold text-dark mb-0">Jenis Produk</h1>
        </div>
        
        @can('create', App\Models\Jenis::class)
            <div class="position-absolute end-0 top-50 translate-middle-y w-auto pe-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createJenisModal">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Jenis
                </button>
            </div>
        @endcan
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            
            {{-- FORM SEARCH --}}
            <form action="{{ route('jenis.index') }}" method="GET" class="mb-3">
                <div class="input-group shadow-sm">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request()->search }}" 
                        class="form-control" 
                        placeholder="Cari Jenis Produk..."
                    >
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('jenis.index') }}" class="btn btn-outline-primary">Reset</a>
                    @endif
                </div>
            </form>

            <div class="card border-0 shadow-sm bg-white rounded-3 overflow-hidden p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 custom-responsive-table">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="ps-3" style="width: 50px;">#</th>
                                <th scope="col" class="text-center">Nama Jenis</th>
                                @can('create', App\Models\Jenis::class)
                                    <th scope="col" class="text-center pe-3" style="width: 200px;">Aksi</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jenis as $index => $item)
                                <tr>
                                    <!-- Tambah data-label dan class td-number -->
                                    <td data-label="#" class="ps-3 td-number">{{ $loop->iteration }}</td>
                                    <td data-label="NAMA JENIS" class="fw-medium text-center">{{ $item->nama_jenis }}</td>
                                    
                                    @can('update', $item)
                                    <td data-label="AKSI" class="text-center pe-3">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button 
                                                type="button" 
                                                class="btn btn-sm btn-outline-warning text-nowrap" 
                                                title="Edit"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editJenisModal-{{ $item->id }}"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            @can('delete', $item)
                                            <form action="{{ route('jenis.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    title="Hapus"
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger text-nowrap"
                                                    onclick="return confirm('Yakin hapus jenis &quot;{{ $item->nama_jenis }}&quot;?')"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        Tidak ada data jenis produk yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
@foreach($jenis as $item)
    @can('update', $item)
    <div class="modal fade" id="editJenisModal-{{ $item->id }}" tabindex="-1" aria-labelledby="editJenisModalLabel-{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('jenis.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="editJenisModalLabel-{{ $item->id }}">Edit Jenis Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body text-start">
                        <div class="mb-3">
                            <label for="nama_jenis_{{ $item->id }}" class="form-label fw-medium">Nama Jenis Produk</label>
                            <input
                                type="text"
                                name="nama_jenis"
                                id="nama_jenis_{{ $item->id }}"
                                class="form-control @error('nama_jenis') is-invalid @enderror"
                                value="{{ old('nama_jenis', $item->nama_jenis) }}"
                                placeholder="Contoh: Unit Handphone, Paket data & pulsa,dll"
                                required
                            >
                            @error('nama_jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
@endforeach

{{-- MODAL CREATE --}}
@can('create', App\Models\Jenis::class)
<div class="modal fade" id="createJenisModal" tabindex="-1" aria-labelledby="createJenisModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('jenis.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="createJenisModalLabel">Tambah Jenis Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label for="nama_create" class="form-label fw-medium">Nama Jenis Produk</label>
                        <input
                            type="text"
                            name="nama_jenis"
                            id="nama_create"
                            class="form-control @error('nama_jenis') is-invalid @enderror"
                            value="{{ old('nama_jenis') }}"
                            placeholder="Contoh: Unit Handphone, Paket data & pulsa,dll"
                            required
                        >
                        @error('nama_jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@endsection