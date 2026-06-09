<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManualBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManualBookController extends Controller
{
    public function index()
    {
        $manualBook = ManualBook::latestManual();

        return view('admin.manual-books.index', compact('manualBook'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'manual_pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $currentManualBook = ManualBook::latestManual();
        if ($currentManualBook) {
            Storage::disk('public')->delete($currentManualBook->file_path);
        }

        $file = $request->file('manual_pdf');
        $path = $file->store('manual-books', 'public');

        $payload = [
            'title' => $data['title'] ?: 'Manual Book',
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'uploaded_by' => $request->user()?->id,
        ];

        if ($currentManualBook) {
            $currentManualBook->update($payload);
        } else {
            ManualBook::create($payload);
        }

        return redirect()
            ->route('admin.manual-books.index')
            ->with('success', 'Manual Book berhasil diupload. File di halaman Bantuan user sudah diperbarui.');
    }

    public function destroy(ManualBook $manualBook)
    {
        Storage::disk('public')->delete($manualBook->file_path);
        $manualBook->delete();

        return redirect()
            ->route('admin.manual-books.index')
            ->with('success', 'Manual Book berhasil dihapus dari sistem.');
    }
}
