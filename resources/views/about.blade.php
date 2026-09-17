@extends('layouts.app')

@section('title', 'Tentang Kami - Toko Handphone Danzz')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/about/index.css') }}">
@endpush

@section('content')
<div class="container-fluid px-0">
    <div class="card border-0 bg-dark text-white rounded-3 mb-4 shadow-sm about-hero">
        <div class="card-body p-4 p-md-5">
            <span class="badge bg-secondary text-white mb-2 fw-semibold px-3 py-2">Profil Toko</span>
            <h1 class="fw-bold display-6 mb-2">Toko Handphone Danzz</h1>
            <p class="lead mb-0 text-white-50 fs-6">
                Pusat smartphone original, aksesoris terlengkap, serta layanan isi ulang pulsa dan paket data internet terpercaya.
            </p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <img src="{{ asset('assets/img/hp.jpg')}}" class="card-img-top" alt="Smartphone" style="height: 180px; object-fit: cover;">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-1">Smartphone & Gadget</h6>
                    <small class="text-muted d-block mb-2">Original & Bergaransi</small>
                    <p class="text-secondary fs-7 mb-0">Android & iOS terbaru dari berbagai brand resmi.</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <img src="{{ asset('assets/img/aksesoris.jpg')}}" class="card-img-top" alt="Aksesoris" style="height: 180px; object-fit: cover;">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-1">Aksesoris Lengkap</h6>
                    <small class="text-muted d-block mb-2">Kualitas Terjamin</small>
                    <p class="text-secondary fs-7 mb-0">Charger, tempered glass, casing, hingga powerbank.</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <img src="{{ asset('assets/img/data.jpg')}}" class="card-img-top" alt="Voucher" style="height: 180px; object-fit: cover;">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-1">Voucher & Kuota</h6>
                    <small class="text-muted d-block mb-2">Cepat & Praktis</small>
                    <p class="text-secondary fs-7 mb-0">Isi ulang pulsa dan paket data semua operator.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm p-3 p-md-4 h-100">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Lokasi Toko</h5>
                <div class="bg-light p-3 rounded-3 border mb-3 fs-7">
                    <strong>Alamat Lengkap:</strong><br>
                    Jl. Bebedahan 1 Kel. Sukanegara Kec. Purbaratu (Depan Qinimarket)
                </div>
                <div class="rounded-3 overflow-hidden border about-map-container flex-grow-1">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d206.42634540587002!2d108.2376098690712!3d-7.331117356006945!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1ssyifa%20qua%20galon%20!5e1!3m2!1sid!2sid!4v1789475483073!5m2!1sid!2sid" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm p-3 p-md-4 h-100">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-clock-fill text-secondary me-2"></i>Kontak & Jam Operasional</h5>
                <ul class="list-group list-group-flush mb-4 fs-7">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Senin - Sabtu</span>
                        <span class="fw-semibold">08:00 - 21:00 WIB</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Minggu</span>
                        <span class="fw-semibold">09:00 - 20:00 WIB</span>
                    </li>
                </ul>

                <h6 class="fw-bold mb-2 text-dark">Hubungi Kami</h6>
                <div class="d-flex flex-column gap-2">
                    <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-outline-dark btn-sm text-start rounded-2">
                        <i class="bi bi-whatsapp me-2"></i> WhatsApp CS: 0812-3456-7890
                    </a>
                    <a href="mailto:info@tokodanzz.com" class="btn btn-outline-dark btn-sm text-start rounded-2">
                        <i class="bi bi-envelope me-2"></i> Email: info@tokodanzz.com
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection