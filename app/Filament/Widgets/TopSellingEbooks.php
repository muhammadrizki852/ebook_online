<?php

namespace App\Filament\Widgets;

use App\Models\Ebook;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopSellingEbooks extends BaseWidget
{
    protected static ?string $heading = 'Top Selling Ebooks';

    protected function getTableQuery(): Builder
    {
        return Ebook::query()
            ->withCount(['approvedPurchases as sales_count'])
            ->withSum(['approvedPurchases as revenue'], 'amount')
            ->having('sales_count', '>', 0)
            ->orderByDesc('sales_count')
            ->limit(10);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('category')->badge(),
                Tables\Columns\TextColumn::make('sales_count')->label('Sales')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('revenue')->money('IDR')->sortable(),
            ])
            ->paginated([10, 25, 50]);
    }
}
