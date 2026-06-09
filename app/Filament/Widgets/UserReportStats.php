<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserReportStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make('New Users', User::where('created_at', '>=', now()->subDays(30))->count())
                ->description('Last 30 days')
                ->icon('heroicon-o-user-plus')
                ->color('success'),
        ];
    }
}
