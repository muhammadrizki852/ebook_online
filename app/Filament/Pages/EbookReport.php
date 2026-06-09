<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\EbookCategoryChart;
use App\Filament\Widgets\EbookReportStats;
use App\Filament\Widgets\TopSellingEbooks;
use Filament\Pages\Page;

class EbookReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Ebook Report';

    protected static ?string $title = 'Ebook Report';

    protected static string $view = 'filament.pages.ebook-report';

    protected function getHeaderWidgets(): array
    {
        return [
            EbookReportStats::class,
            EbookCategoryChart::class,
            TopSellingEbooks::class,
        ];
    }
}
