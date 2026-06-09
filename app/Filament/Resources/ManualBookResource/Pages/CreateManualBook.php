<?php

namespace App\Filament\Resources\ManualBookResource\Pages;

use App\Filament\Resources\ManualBookResource;
use App\Models\ManualBook;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateManualBook extends CreateRecord
{
    protected static string $resource = ManualBookResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($currentManualBook = ManualBook::latestManual()) {
            Storage::disk('public')->delete($currentManualBook->file_path);
        }

        $data['uploaded_by'] = Auth::id();

        return $data;
    }
}
