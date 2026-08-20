@extends('layouts.app')

@section('title', 'Produk')

@section('content')

<h1>Produk</h1>

@can('create', App\Models\Produk::class)
<a href="{{ route('produk.create') }}" class="btn btn-primary mb-3">create</a>
@endcan

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
            Search
        </button>
        @if(request('search'))
        <a href="{{ route('produk.index') }}" class="btn btn-outline-primary">Reset</a>
        @endif
    </div>
</form>

<table class="table">
  <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">User</th>
        <th scope="col">Jenis</th>
        <th scope="col">Foto</th>
        <th scope="col">Nama</th>
        <th scope="col">Harga Beli</th>
        <th scope="col">Harga Jual</th>
        <th scope="col">Stok</th>
        <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($products as $product)
    <tr>
        <th scope="row">{{ $products->firstItem() + $loop->index }}</th>
        <td>{{ $product->user->name }}</td>
        <td>{{ $product->jenis->nama_jenis ?? '-' }}</td>
        <td>
            <img src="{{ asset('storage/' .$product->foto) }}" width="100" alt="" class="img-thumbnail">
        </td>
        <td>{{ $product->nama }}</td>
        <td>{{ $product->harga_beli }}</td>
        <td>{{ $product->harga_jual }}</td>
        <td>{{ $product->stok }}</td>
        <td>
            <div class="d-flex align-items-center gap-1">
                @can('view', $product)
                    <a title="Detail" href="{{ route('produk.show', $product) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i></a>
                @endcan
                @can('update', $product)
                    <a title="Edit" href="{{ route('produk.edit', $product) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                @endcan
                @can('delete', $product)
                    <form action="{{ route('produk.destroy', $product)}}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button title="Hapus" type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah anda yakin akan menghapus produk ini?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                @endcan
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="text-center py-4">
            <h5 class="mb-0 text-muted"> <i class="bi bi-box-seam"></i> Produk Tidak tersedia.</h5>
        </td>
    </tr>
    @endforelse
  </tbody>
</table>

{{ $products->links() }}
@endsection