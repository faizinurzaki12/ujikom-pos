@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')

<!-- Header dengan Judul di Kiri dan Tombol Create di Kanan -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold text-dark mb-0">Penjualan</h1>
    <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah
    </a>
</div>

@if(session('errors'))
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
    {{ session('errors') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<form action="{{ route('penjualan.index') }}" method="GET" class="mb-3">
    <div class="input-group shadow-sm">
        <input 
            type="text" 
            name="search" 
            value="{{ request()->search }}" 
            class="form-control" 
            placeholder="Cari Kasir"
        >
        <button class="btn btn-outline-secondary" type="submit">
            <i class="bi bi-search me-1"></i> Search
        </button>
        @if(request('search'))
            <a href="{{ route('penjualan.index') }}" class="btn btn-outline-primary">Reset</a>
        @endif
    </div>
</form>

<div class="table-responsive rounded-3 border shadow-sm mb-3">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center" style="width: 50px;">#</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Kasir</th>
                <th scope="col" class="text-end">Total Pembayaran</th>
                <th scope="col" class="text-center">Metode</th>
                <th scope="col" class="text-center">Status</th>
                <th scope="col" class="text-center" style="width: 120px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $sale)
            <tr>
                <th scope="row" class="text-center text-muted fw-normal">{{ $sales->firstItem() + $loop->index }}</th>
                <td class="text-nowrap">{{ $sale->created_at->translatedFormat('d-m-Y H:i') }}</td>
                <td class="fw-medium">{{ $sale->user->name }}</td>
                <td class="text-end text-nowrap fw-bold text-success">
                    Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}
                </td>
                <td class="text-center">
                    <span class="badge {{ strtolower($sale->metode_pembayaran) == 'tunai' ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-info-subtle text-info-emphasis border border-info-subtle' }}">
                        {{ strtoupper($sale->metode_pembayaran) }}
                    </span>
                </td>
                <td class="text-center">
                    <span class="badge {{ strtolower($sale->status) == 'selesai' || strtolower($sale->status) == 'lunas' || strtolower($sale->status) == 'success' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis' }} px-2 py-1">
                        {{ ucfirst($sale->status) }}
                    </span>
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center align-items-center gap-1">
                        <a title="Detail" href="{{ route('penjualan.show', $sale) }}" class="btn btn-outline-primary btn-sm rounded-2">
                            <i class="bi bi-eye"></i>
                        </a>

                        @can('update', $sale)
                            <a title="Edit" href="{{ route('penjualan.edit', $sale) }}" class="btn btn-outline-warning btn-sm rounded-2">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        @endcan 

                        @can('delete', $sale)
                            <form action="{{ route('penjualan.destroy', $sale) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button title="Hapus" type="submit" class="btn btn-outline-danger btn-sm rounded-2" onclick="return confirm('Apakah anda yakin akan menghapus data penjualan ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5 bg-light">
                    <div class="text-muted">
                        <i class="bi bi-cart-x display-6 d-block mb-2 text-secondary"></i>
                        <h6 class="mb-0 fw-normal">Data penjualan tidak tersedia.</h6>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
<div class="d-flex justify-content-end">
    {{ $sales->links() }}
</div>

@endsection