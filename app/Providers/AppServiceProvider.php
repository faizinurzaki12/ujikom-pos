<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider AS ServiceProvider;
// use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\ItemPenjualan;
use App\Policies\ProdukPolicy;
use App\Policies\ItemPenjualanPolicy;
use App\Policies\PenjualanPolicy;
use App\Policies\DashboardPolicy;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => DashboardPolicy::class,
        Produk::class => ProdukPolicy::class,
        Penjualan::class => PenjualanPolicy::class,
        ItemPenjualan::class => ItemPenjualanPolicy::class
    ];
    
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any applicati on services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Carbon::setLocale('id');
        $this->registerPolicies();
    }
}
