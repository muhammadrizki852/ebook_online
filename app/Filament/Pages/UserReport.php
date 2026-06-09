<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\UserGrowthChart;
use App\Filament\Widgets\UserRegistrationHistory;
use App\Filament\Widgets\UserReportStats;
use Filament\Pages\Page;

class UserReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'User Report';

    protected static ?string $title = 'User Report';

    protected static string $view = 'filament.pages.user-report';

    protected function getHeaderWidgets(): array
    {
        return [
            UserReportStats::class,
            UserGrowthChart::class,
            UserRegistrationHistory::class,
        ];
    }
}
