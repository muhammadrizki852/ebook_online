<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store(Request $request, Ebook $ebook)
    {
        if ($ebook->status !== 'published') {
            abort(404);
        }

        $request->user()->favoriteEbooks()->syncWithoutDetaching([$ebook->id]);

        return response()->json([
            'message' => 'Buku ditambahkan ke favorit.',
            'slug' => $ebook->slug,
        ]);
    }

    public function destroy(Request $request, Ebook $ebook)
    {
        $request->user()->favoriteEbooks()->detach($ebook->id);

        return response()->json([
            'message' => 'Buku dihapus dari favorit.',
            'slug' => $ebook->slug,
        ]);
    }
}
