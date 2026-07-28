@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')

@include('layouts.navbar')
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
                <a href="{{ route('penjualan.show', $sale) }}" class="btn btn-primary">Detail</a>
            @can('view', $sale)
            ||
            <a href="{{ route('penjualan.edit', $sale)}}" class="btn btn-warning">Edit</a>
            @endcan 
            ||
            @can('delete', $sale)
            <form action="{{ route('penjualan.destroy' , $sale)}}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" onclick="return confirm('Apakah anda yakin akan menghapus produk ini?')">
                    Hapus
                </button>
            </form>
            @endcan
        </td>
    </tr>
    @empty
    <tr>
        <td collspan="7"><h1>Data tidak tersedia.</h1></td>
    </tr>
    @endforelse
  </tbody>
</table>
{{ $sales->links() }}
  </tbody>
</table>

@endsection