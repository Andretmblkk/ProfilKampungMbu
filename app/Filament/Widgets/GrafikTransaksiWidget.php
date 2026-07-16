<?php

namespace App\Filament\Widgets;

use App\Models\DanaMasuk;
use App\Models\Pengeluaran;
use Filament\Widgets\ChartWidget;

class GrafikTransaksiWidget extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penggunaan Dana';

    protected function getData(): array
    {
        $year = max(
            (int) (DanaMasuk::query()->max('tanggal') ? substr((string) DanaMasuk::query()->max('tanggal'), 0, 4) : 0),
            (int) (Pengeluaran::query()->max('tanggal') ? substr((string) Pengeluaran::query()->max('tanggal'), 0, 4) : 0),
        ) ?: (int) now()->year;

        $income = DanaMasuk::query()
            ->where('status', 'terverifikasi')
            ->whereYear('tanggal', $year)
            ->get()
            ->groupBy(fn (DanaMasuk $item) => $item->tanggal->month)
            ->map(fn ($items) => round((float) $items->sum('nominal') / 1000000, 2));
        $expense = Pengeluaran::query()
            ->where('status', 'terverifikasi')
            ->whereYear('tanggal', $year)
            ->get()
            ->groupBy(fn (Pengeluaran $item) => $item->tanggal->month)
            ->map(fn ($items) => round((float) $items->sum('nominal') / 1000000, 2));

        return [
            'datasets' => [
                ['label' => 'Dana Masuk (juta)', 'data' => collect(range(1, 12))->map(fn ($month) => $income[$month] ?? 0)->all(), 'backgroundColor' => '#0d4aaa'],
                ['label' => 'Pengeluaran (juta)', 'data' => collect(range(1, 12))->map(fn ($month) => $expense[$month] ?? 0)->all(), 'backgroundColor' => '#f59e0b'],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
