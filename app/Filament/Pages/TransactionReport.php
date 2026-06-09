<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RecentTransactions;
use App\Filament\Widgets\TransactionMonthlyChart;
use App\Filament\Widgets\TransactionReportStats;
use Filament\Pages\Page;

class TransactionReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Transaction Report';

    protected static ?string $title = 'Transaction Report';

    protected static string $view = 'filament.pages.transaction-report';

    protected function getHeaderWidgets(): array
    {
        return [
            TransactionReportStats::class,
            TransactionMonthlyChart::class,
            RecentTransactions::class,
        ];
    }
}
