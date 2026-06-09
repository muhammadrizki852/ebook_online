<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class UserGrowthChart extends ChartWidget
{
    protected static ?string $heading = 'User Growth';

    protected function getData(): array
    {
        $months = collect(range(11, 0))->mapWithKeys(function (int $monthsAgo) {
            $month = now()->subMonths($monthsAgo);

            return [$month->format('Y-m') => $month->format('M Y')];
        });

        $data = User::query()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return [
            'datasets' => [
                [
                    'label' => 'New Users',
                    'data' => $months->keys()->map(fn (string $month) => (int) ($data[$month] ?? 0))->values(),
                    'borderColor' => '#4f46e5',
                    'backgroundColor' => 'rgba(79, 70, 229, 0.12)',
                    'fill' => true,
                ],
            ],
            'labels' => $months->values(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
