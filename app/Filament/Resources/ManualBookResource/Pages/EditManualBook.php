<?php

namespace App\Filament\Resources\ManualBookResource\Pages;

use App\Filament\Resources\ManualBookResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditManualBook extends EditRecord
{
    protected static string $resource = ManualBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(fn () => Storage::disk('public')->delete($this->record->file_path)),
        ];
    }
}
