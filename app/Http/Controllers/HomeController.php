<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    /**
     * Display the SPA home page with published ebooks.
     */
    public function index()
    {
        $publishedEbooks = collect();
        $topRecommendedSlugs = [
            'laut-bercerita',
            'atomic-habits',
            'psychology-of-money',
            'mindset',
            'clean-code',
            'sapiens',
        ];

        if (Schema::hasTable('ebooks')) {
            $publishedEbooks = Ebook::published()
                ->latest()
                ->get()
                ->sortBy(function (Ebook $ebook) use ($topRecommendedSlugs) {
                    $position = array_search($ebook->slug, $topRecommendedSlugs, true);

                    return $position === false ? count($topRecommendedSlugs) + $ebook->id : $position;
                })
                ->map(fn (Ebook $ebook) => [
                    'id' => $ebook->slug,
                    'title' => $ebook->title,
                    'author' => $ebook->author,
                    'description' => $ebook->description,
                    'category' => $ebook->category,
                    'cover' => $ebook->cover_url,
                    'price' => (float) $ebook->price,
                    'isFree' => (float) $ebook->price <= 0,
                    'pdfUrl' => $ebook->file_path ? route('ebooks.read-pdf', $ebook) : null,
                    'downloadUrl' => $ebook->file_path ? route('ebooks.download-pdf', $ebook) : null,
                    'createdAt' => $ebook->created_at?->toIso8601String(),
                ])
                ->values();
        }

        return view('spa', compact('publishedEbooks'));
    }
}
