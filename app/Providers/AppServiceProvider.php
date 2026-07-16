<?php

namespace App\Providers;

use App\Models\Berita;
use App\Models\DanaMasuk;
use App\Models\LaporanKeuangan;
use App\Models\LaporanWarga;
use App\Models\Pengeluaran;
use App\Models\ProyekKampung;
use App\Models\User;
use App\Observers\ActivityLogObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ([
            Berita::class,
            DanaMasuk::class,
            Pengeluaran::class,
            ProyekKampung::class,
            LaporanKeuangan::class,
            LaporanWarga::class,
            User::class,
        ] as $model) {
            $model::observe(ActivityLogObserver::class);
        }
    }
}
