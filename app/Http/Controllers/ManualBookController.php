<?php

namespace App\Http\Controllers;

use App\Models\ManualBook;
use Illuminate\Support\Facades\Storage;

class ManualBookController extends Controller
{
    public function show()
    {
        $manualBook = ManualBook::latestManual();

        return view('manual-books.viewer', compact('manualBook'));
    }

    public function file()
    {
        $manualBook = $this->availableManualBook();

        return response()->file(Storage::disk('public')->path($manualBook->file_path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . addslashes($this->filename($manualBook)) . '"',
        ]);
    }

    public function download()
    {
        $manualBook = $this->availableManualBook();

        return Storage::disk('public')->download($manualBook->file_path, $this->filename($manualBook));
    }

    private function availableManualBook(): ManualBook
    {
        $manualBook = ManualBook::latestManual();

        if (!$manualBook || !Storage::disk('public')->exists($manualBook->file_path)) {
            abort(404);
        }

        return $manualBook;
    }

    private function filename(ManualBook $manualBook): string
    {
        return $manualBook->original_filename ?: 'manual-book.pdf';
    }
}
