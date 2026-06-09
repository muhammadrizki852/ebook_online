<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class UserRegistrationHistory extends BaseWidget
{
    protected static ?string $heading = 'User Registration History';

    protected function getTableQuery(): Builder
    {
        return User::query()->latest();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Registered')->dateTime(),
            ])
            ->paginated([10, 25, 50]);
    }
}
