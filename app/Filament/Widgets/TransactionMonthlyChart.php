<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use Filament\Widgets\ChartWidget;

class TransactionMonthlyChart extends ChartWidget
{
    protected static ?string $heading = 'Transactions per Month';

    protected function getData(): array
    {
        $months = collect(range(11, 0))->mapWithKeys(function (int $monthsAgo) {
            $month = now()->subMonths($monthsAgo);

            return [$month->format('Y-m') => $month->format('M Y')];
        });

        $data = Purchase::query()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return [
            'datasets' => [
                [
                    'label' => 'Transactions',
                    'data' => $months->keys()->map(fn (string $month) => (int) ($data[$month] ?? 0))->values(),
                    'backgroundColor' => '#14b8a6',
                ],
            ],
            'labels' => $months->values(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
