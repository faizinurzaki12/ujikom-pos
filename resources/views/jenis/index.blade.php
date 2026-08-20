@extends('layouts.app')

@section('title', 'Halaman Jenis Produk')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark mb-0">Jenis Produk</h1>
        <a href="{{ route('jenis.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Create Jenis
        </a>
    </div>

    {{-- Alert success/error TIDAK ditaruh di sini lagi -- sudah ditangani
         terpusat di layouts/app.blade.php, supaya tidak dobel dan berlaku
         otomatis untuk semua halaman (produk, penjualan, dll juga). --}}

    <!-- Button Search -->
    <form action="{{ route('jenis.index') }}" method="GET" class="d-flex mb-4">
        <div class="input-group shadow-sm">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Cari Nama Jenis"
            >
            <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search me-1"></i> Search
            </button>
            @if(request('search'))
                <a class="btn btn-outline-primary" href="{{ route('jenis.index') }}">Reset</a>
            @endif
        </div>
    </form>

    <!-- Tabel Data (dibungkus card, gaya sama dengan halaman Users) -->
    <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Jenis</th>
                        <th scope="col">Dibuat Oleh</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenis as $index => $item)
                        <tr>
                            <td>{{ $jenis->firstItem() + $index }}</td>
                            <td class="fw-medium">{{ $item->nama_jenis }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('jenis.edit', $item) }}" title="Edit" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('jenis.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        title="Hapus"
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Yakin hapus jenis &quot;{{ $item->nama_jenis }}&quot;?')"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Tidak ada data jenis produk yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($jenis, 'links'))
            <div class="mt-3">
                {{ $jenis->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection