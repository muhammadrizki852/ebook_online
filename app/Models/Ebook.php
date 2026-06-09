<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'author',
        'price',
        'cover_image',
        'file_path',
        'category',
        'status',
    ];

    protected $appends = [
        'cover_url',
        'cover_fallback_url',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($ebook) {
            if (empty($ebook->slug)) {
                $ebook->slug = Str::slug($ebook->title);
            }
        });
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function approvedPurchases()
    {
        return $this->hasMany(Purchase::class)->where('payment_status', 'approved');
    }

    public function isPurchasedBy(User $user): bool
    {
        return $this->approvedPurchases()->where('user_id', $user->id)->exists();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getCoverUrlAttribute(): ?string
    {
        if (empty($this->cover_image)) {
            return $this->known_cover_url ?? $this->cover_fallback_url;
        }

        if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
            return $this->cover_image;
        }

        if (Storage::disk('public')->exists($this->cover_image)) {
            return asset('storage/' . $this->cover_image);
        }

        if (file_exists(public_path($this->cover_image))) {
            return asset($this->cover_image);
        }

        return $this->cover_fallback_url;
    }

    public function getKnownCoverUrlAttribute(): ?string
    {
        $covers = [
            'laravel-up-running' => 'https://covers.openlibrary.org/b/isbn/9781492041214-L.jpg',
            'clean-code' => 'https://covers.openlibrary.org/b/isbn/9780132350884-L.jpg',
            'the-pragmatic-programmer' => 'https://covers.openlibrary.org/b/isbn/9780135957059-L.jpg',
            'javascript-the-good-parts' => 'https://covers.openlibrary.org/b/isbn/9780596517748-L.jpg',
            'python-crash-course' => 'https://covers.openlibrary.org/b/isbn/9781593279288-L.jpg',
            'design-patterns' => 'https://covers.openlibrary.org/b/isbn/9780201633610-L.jpg',
            'css-the-definitive-guide' => 'https://covers.openlibrary.org/b/isbn/9781449393199-L.jpg',
            'domain-driven-design' => 'https://covers.openlibrary.org/b/isbn/9780321125217-L.jpg',
        ];

        return $covers[$this->slug] ?? null;
    }

    public function getCoverFallbackUrlAttribute(): string
    {
        $title = e($this->title ?: 'Ebook');
        $author = e($this->author ?: 'Ebook Store');
        $category = e($this->category ?: 'Digital Book');

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="320" height="480" viewBox="0 0 320 480">
  <defs>
    <linearGradient id="coverGradient" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#2563eb"/>
      <stop offset="1" stop-color="#7c3aed"/>
    </linearGradient>
  </defs>
  <rect width="320" height="480" rx="24" fill="url(#coverGradient)"/>
  <rect x="28" y="34" width="264" height="412" rx="18" fill="rgba(255,255,255,0.13)" stroke="rgba(255,255,255,0.32)" stroke-width="2"/>
  <text x="160" y="92" fill="#dbeafe" font-family="Arial, sans-serif" font-size="18" font-weight="700" text-anchor="middle">{$category}</text>
  <foreignObject x="44" y="150" width="232" height="138">
    <div xmlns="http://www.w3.org/1999/xhtml" style="font-family:Arial,sans-serif;color:white;font-size:34px;font-weight:700;line-height:1.12;text-align:center;word-wrap:break-word;">{$title}</div>
  </foreignObject>
  <foreignObject x="50" y="318" width="220" height="56">
    <div xmlns="http://www.w3.org/1999/xhtml" style="font-family:Arial,sans-serif;color:#dbeafe;font-size:18px;line-height:1.25;text-align:center;word-wrap:break-word;">{$author}</div>
  </foreignObject>
  <text x="160" y="410" fill="#ffffff" font-family="Arial, sans-serif" font-size="16" font-weight="700" text-anchor="middle">EBOOK STORE</text>
</svg>
SVG;

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }
}
