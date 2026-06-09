<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EbookController extends Controller
{
    public function index(Request $request)
    {
        $query = Ebook::withCount([
            'approvedPurchases as sales_count',
        ]);

        if ($search = $request->get('search')) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $ebooks = $query->latest()->paginate(15);
        return view('admin.ebooks.index', compact('ebooks'));
    }

    public function create()
    {
        $categories = $this->categories();

        return view('admin.ebooks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['title']);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('ebooks', 'public');
        }

        Ebook::create($data);

        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook berhasil ditambahkan dan sudah tersambung ke aplikasi.');
    }

    public function edit(Ebook $ebook)
    {
        $categories = $this->categories($ebook->category);

        return view('admin.ebooks.edit', compact('ebook', 'categories'));
    }

    public function update(Request $request, Ebook $ebook)
    {
        $data = $this->validatedData($request, $ebook);
        $data['slug'] = $this->uniqueSlug($data['title'], $ebook);

        if ($request->hasFile('cover_image')) {
            $this->deletePublicFile($ebook->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        } elseif ($request->boolean('remove_cover_image')) {
            $this->deletePublicFile($ebook->cover_image);
            $data['cover_image'] = null;
        } else {
            unset($data['cover_image']);
        }

        if ($request->hasFile('file_path')) {
            $this->deletePublicFile($ebook->file_path);
            $data['file_path'] = $request->file('file_path')->store('ebooks', 'public');
        } elseif ($request->boolean('remove_file_path')) {
            $this->deletePublicFile($ebook->file_path);
            $data['file_path'] = null;
        } else {
            unset($data['file_path']);
        }

        $ebook->update($data);

        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook berhasil diupdate dan perubahan langsung tampil di aplikasi.');
    }

    public function destroy(Ebook $ebook)
    {
        $this->deletePublicFile($ebook->cover_image);
        $this->deletePublicFile($ebook->file_path);
        $ebook->delete();

        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook berhasil dihapus dari aplikasi.');
    }

    private function categories(?string $currentCategory = null): array
    {
        $categories = [
            'Novel',
            'Fiksi',
            'Seri Novel',
            'Motivasi',
            'Bisnis',
            'Ekonomi',
            'Pengembangan Diri',
            'Pendidikan',
            'Sejarah',
            'Teknologi',
            'Kesehatan',
        ];

        $databaseCategories = Ebook::query()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->all();

        $categories = array_values(array_unique(array_merge($categories, $databaseCategories)));

        if ($currentCategory && !in_array($currentCategory, $categories, true)) {
            array_unshift($categories, $currentCategory);
        }

        return $categories;
    }

    private function validatedData(Request $request, ?Ebook $ebook = null): array
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'author'      => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric', 'min:0'],
            'category'    => ['required', 'string', 'max:100'],
            'status'      => ['required', 'in:draft,published'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'file_path'   => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'remove_cover_image' => ['nullable', 'boolean'],
            'remove_file_path' => ['nullable', 'boolean'],
        ]);

        $data['category'] = trim((string) $data['category']);
        unset($data['remove_cover_image'], $data['remove_file_path']);

        return $data;
    }

    private function uniqueSlug(string $title, ?Ebook $ebook = null): string
    {
        $base = Str::slug($title) ?: 'ebook';
        $slug = $base;
        $count = 1;

        while (Ebook::where('slug', $slug)
            ->when($ebook, fn ($query) => $query->whereKeyNot($ebook->id))
            ->exists()) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }

    private function deletePublicFile(?string $path): void
    {
        if (!$path || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
