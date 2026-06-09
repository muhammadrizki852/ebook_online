<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\TransactionActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    public function show(Ebook $ebook)
    {
        if ($ebook->status !== 'published') {
            abort(404);
        }

        $isPurchased = false;
        if (auth()->check()) {
            $isPurchased = $ebook->isPurchasedBy(auth()->user());
        }

        return view('ebooks.show', compact('ebook', 'isPurchased'));
    }

    public function readPdf(Ebook $ebook)
    {
        if ($ebook->status !== 'published') {
            abort(404);
        }

        $isFree = (float) $ebook->price <= 0;
        $isPurchased = auth()->check() && $ebook->isPurchasedBy(auth()->user());

        if (!$isFree && !$isPurchased) {
            abort(403);
        }

        if (!$ebook->file_path || !Storage::disk('public')->exists($ebook->file_path)) {
            abort(404);
        }

        $this->recordFreeEbookActivity($ebook, 'read');

        return response()->file(Storage::disk('public')->path($ebook->file_path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . addslashes($ebook->slug) . '.pdf"',
        ]);
    }

    public function downloadPdf(Ebook $ebook)
    {
        if ($ebook->status !== 'published') {
            abort(404);
        }

        $isFree = (float) $ebook->price <= 0;
        $isPurchased = auth()->check() && $ebook->isPurchasedBy(auth()->user());

        if (!$isFree && !$isPurchased) {
            abort(403);
        }

        if (!$ebook->file_path || !Storage::disk('public')->exists($ebook->file_path)) {
            abort(404);
        }

        $this->recordFreeEbookActivity($ebook, 'download');

        return Storage::disk('public')->download($ebook->file_path, $ebook->slug . '.pdf');
    }

    private function recordFreeEbookActivity(Ebook $ebook, string $activityType): void
    {
        if (!auth()->check() || (float) $ebook->price > 0) {
            return;
        }

        TransactionActivity::create([
            'user_id' => auth()->id(),
            'ebook_id' => $ebook->id,
            'activity_type' => $activityType,
            'description' => 'User ' . $activityType . ' free ebook.',
            'amount' => 0,
        ]);
    }
}
