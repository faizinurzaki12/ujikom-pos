@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<h4>Edit Produk</h4>

<form action="{{ route('produk.update', $produk)}}"
      method="POST"
      enctype="multipart/form-data">
      @method('PUT')
@include('Produk._form')
</form>
@endsection
