<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManualBookResource\Pages;
use App\Models\ManualBook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class ManualBookResource extends Resource
{
    protected static ?string $model = ManualBook::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Help';

    protected static ?string $navigationLabel = 'Manual Book';

    protected static ?string $modelLabel = 'Manual Book';

    protected static ?string $pluralModelLabel = 'Manual Books';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->default('Manual Book')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('file_path')
                    ->label('File PDF')
                    ->disk('public')
                    ->directory('manual-books')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240)
                    ->downloadable()
                    ->openable()
                    ->required(),
                Forms\Components\TextInput::make('original_filename')
                    ->label('Nama File Asli')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('original_filename')
                    ->label('File'),
                Tables\Columns\TextColumn::make('readable_file_size')
                    ->label('Ukuran'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Update')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn () => route('manual-book.show'))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(fn (ManualBook $record) => Storage::disk('public')->delete($record->file_path)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListManualBooks::route('/'),
            'create' => Pages\CreateManualBook::route('/create'),
            'edit' => Pages\EditManualBook::route('/{record}/edit'),
        ];
    }
}
