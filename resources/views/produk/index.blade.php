@extends('layouts.app')

@section('title', 'Produk')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold text-dark mb-0">Produk</h1>
    @can('create', App\Models\Produk::class)
        <a href="{{ route('produk.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah
        </a>
    @endcan
</div>

<form action="{{ route('produk.index')}}" method="GET" class="d-flex mb-3">
    <div class="input-group">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            class="form-control" 
            placeholder="Cari Produk"
        >
        <button class="btn btn-outline-secondary" type="submit">
            <i class="bi bi-search me-1"></i> Search
        </button>
        @if(request('search'))
            <a href="{{ route('produk.index') }}" class="btn btn-outline-primary">Reset</a>
        @endif
    </div>
</form>

<div class="table-responsive rounded-3 border shadow-sm">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center" style="width: 50px;">#</th>
                <th scope="col">User</th>
                <th scope="col">Jenis</th>
                <th scope="col" class="text-center" style="width: 80px;">Foto</th>
                <th scope="col">Nama</th>
                <th scope="col" class="text-end">Harga Beli</th>
                <th scope="col" class="text-end">Harga Jual</th>
                <th scope="col" class="text-center">Stok</th>
                <th scope="col" class="text-center" style="width: 120px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
            <tr>
                <th scope="row" class="text-center text-muted fw-normal">{{ $products->firstItem() + $loop->index }}</th>
                <td class="fw-medium">{{ $product->user->name }}</td>
                <td><span class="badge bg-light text-dark border">{{ $product->jenis->nama_jenis ?? '-' }}</span></td>
                <td class="text-center">
                    @if($product->foto)
                        <img src="{{ asset('storage/' . $product->foto) }}" width="45" height="45" alt="{{ $product->nama }}" class="img-thumbnail object-fit-cover rounded-2">
                    @else
                        <span class="badge bg-secondary-subtle text-secondary fs-7"><i class="bi bi-image"></i></span>
                    @endif
                </td>
                <td class="fw-semibold">{{ $product->nama }}</td>
                <td class="text-end text-nowrap">Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</td>
                <td class="text-end text-nowrap fw-semibold text-primary">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                <td class="text-center">
                    <span class="badge {{ $product->stok > 0 ? 'bg-info-subtle text-info-emphasis' : 'bg-danger-subtle text-danger' }} px-2 py-1">
                        {{ $product->stok }} Pcs
                    </span>
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center align-items-center gap-1">
                        @can('view', $product)
                            <a title="Detail" href="{{ route('produk.show', $product) }}" class="btn btn-outline-primary btn-sm rounded-2"><i class="bi bi-eye"></i></a>
                        @endcan
                        @can('update', $product)
                            <a title="Edit" href="{{ route('produk.edit', $product) }}" class="btn btn-outline-warning btn-sm rounded-2"><i class="bi bi-pencil-square"></i></a>
                        @endcan
                        @can('delete', $product)
                            <form action="{{ route('produk.destroy', $product)}}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button title="Hapus" type="submit" class="btn btn-outline-danger btn-sm rounded-2" onclick="return confirm('Apakah anda yakin akan menghapus produk ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5 bg-light">
                    <div class="text-muted">
                        <i class="bi bi-box-seam display-6 d-block mb-2 text-secondary"></i>
                        <h6 class="mb-0 fw-normal">Produk tidak tersedia.</h6>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $products->links() }}
</div>
@endsection