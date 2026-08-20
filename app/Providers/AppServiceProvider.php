<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Jenis;
use App\Policies\JenisPolicy;
use App\Models\ItemPenjualan;
use App\Policies\ProdukPolicy;
use App\Policies\ItemPenjualanPolicy;
use App\Policies\PenjualanPolicy;
use App\Policies\DashboardPolicy;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class          => DashboardPolicy::class,
        Jenis::class         => JenisPolicy::class,
        Produk::class        => ProdukPolicy::class,
        Penjualan::class     => PenjualanPolicy::class,
        ItemPenjualan::class => ItemPenjualanPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Carbon::setLocale('id');
        $this->registerPolicies();
    }
}