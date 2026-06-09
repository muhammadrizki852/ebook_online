<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransactionReportStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Transactions', Purchase::count())
                ->icon('heroicon-o-receipt-percent')
                ->color('primary'),
            Stat::make('Paid Transactions', Purchase::where('payment_status', 'approved')->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Pending Transactions', Purchase::where('payment_status', 'pending')->count())
                ->icon('heroicon-o-clock')
                ->color('warning'),
        ];
    }
}
