<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class GrafikTransaksiWidget extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penggunaan Dana';

    protected function getData(): array
    {
        return [
            'datasets' => [
                ['label' => 'Dana Masuk', 'data' => [350, 520, 480, 690, 780, 600], 'backgroundColor' => '#0d4aaa'],
                ['label' => 'Pengeluaran', 'data' => [120, 260, 310, 420, 500, 380], 'backgroundColor' => '#f59e0b'],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
