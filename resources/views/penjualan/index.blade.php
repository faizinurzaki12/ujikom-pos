@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')

@if(session('errors'))
<div class="alert alert-danger">
    {{session('errors')}}
</div>
@endif
<h1>Halaman Penjualan</h1>
<a href="{{ route('penjualan.create') }}" class="btn btn-primary mb-3">Create</a>

<form action="{{ route('penjualan.index') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input 
            type="text" 
            name="search" 
            value="{{ request()->search }}" 
            class="form-control" 
            placeholder="Search Penjualan"
        >
        <button class="btn btn-outline-secondary" type="submit">
            Search
        </button>
        @if(request('search'))
            <a href="{{ route('penjualan.index') }}" class="btn btn-outline-primary">Reset</a>
        @endif
    </div>
</form>

<table class="table">
  <thead>
    <tr>
    <th scope="col">#</th>
    <th scope="col">Tanggal</th>
    <th scope="col">Kasir</th>
    <th scope="col">Total Pembayaran</th>
    <th scope="col">Metode Pembayaran</th>
    <th scope="col">Status</th>
    <th scope="col">Aksi</th>

    </tr>
  </thead>
  <tbody>
    @forelse ($sales as $sale)
        <th scope="row">{{ $sales->firstItem() + $loop->index }}</th>
        <td>{{ $sale->created_at->translatedFormat('d-m-Y H:i:s') }}</td>
        <td>{{ $sale->user->name }}</td>
        <td>Rp. {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</td>
        <td>{{ $sale->metode_pembayaran }}</td>
        <td>{{ $sale->status }}</td>
        <td class="d-flex gap-1">
                <a title="Detail" href="{{ route('penjualan.show', $sale) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
            @can('view', $sale)
            ||
            <a title="Edit" href="{{ route('penjualan.edit', $sale)}}" class="btn btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
            @endcan 
            ||
            @can('delete', $sale)
            <form action="{{ route('penjualan.destroy' , $sale)}}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button title="Hapus" class="btn btn-outline-danger" onclick="return confirm('Apakah anda yakin akan menghapus produk ini?')">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
            @endcan
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="text-center"><h1><i class="bi bi-cart-check"></i> Data tidak tersedia.</h1></td>
    </tr>
    @endforelse
  </tbody>
</table>
{{ $sales->links() }}
  </tbody>
</table>

@endsection