<?php

namespace App\Filament\Widgets;

use App\Models\DanaMasuk;
use App\Models\Pengeluaran;
use App\Models\ProyekKampung;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatistikDanaWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $danaMasuk = (float) DanaMasuk::query()->sum('nominal');
        $pengeluaran = (float) Pengeluaran::query()->sum('nominal');

        return [
            Stat::make('Dana Masuk', 'Rp '.number_format($danaMasuk, 0, ',', '.'))->description('+12% vs tahun lalu')->color('primary')->icon('heroicon-o-arrow-trending-up'),
            Stat::make('Pengeluaran', 'Rp '.number_format($pengeluaran, 0, ',', '.'))->description('Terverifikasi')->color('warning')->icon('heroicon-o-receipt-percent'),
            Stat::make('Dana Tersisa', 'Rp '.number_format($danaMasuk - $pengeluaran, 0, ',', '.'))->description('Saldo berjalan')->color('success')->icon('heroicon-o-wallet'),
            Stat::make('Total Proyek', ProyekKampung::query()->count().' Proyek')->description('Monitoring aktif')->color('info')->icon('heroicon-o-wrench-screwdriver'),
        ];
    }
}
