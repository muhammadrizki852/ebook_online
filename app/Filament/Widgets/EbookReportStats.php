<?php

namespace App\Filament\Widgets;

use App\Models\Ebook;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EbookReportStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Ebooks', Ebook::count())
                ->icon('heroicon-o-book-open')
                ->color('primary'),
            Stat::make('Total Free Ebooks', Ebook::where('price', '<=', 0)->count())
                ->icon('heroicon-o-gift')
                ->color('success'),
            Stat::make('Total Paid Ebooks', Ebook::where('price', '>', 0)->count())
                ->icon('heroicon-o-tag')
                ->color('warning'),
        ];
    }
}
