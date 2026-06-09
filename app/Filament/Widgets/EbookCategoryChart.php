<?php

namespace App\Filament\Widgets;

use App\Models\Ebook;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class EbookCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Ebooks by Category';

    protected function getData(): array
    {
        $stats = Ebook::query()
            ->select('category', DB::raw('COUNT(*) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $stats->pluck('total'),
                    'backgroundColor' => ['#4f46e5', '#14b8a6', '#f59e0b', '#ef4444', '#8b5cf6', '#0ea5e9', '#22c55e'],
                ],
            ],
            'labels' => $stats->pluck('category'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
