<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ManualBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'file_path',
        'original_filename',
        'file_size',
        'uploaded_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public static function latestManual(): ?self
    {
        return static::latest()->first();
    }

    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_path || !Storage::disk('public')->exists($this->file_path)) {
            return null;
        }

        return route('manual-book.file', ['v' => $this->updated_at?->timestamp ?? now()->timestamp]);
    }

    public function getDownloadUrlAttribute(): ?string
    {
        if (!$this->file_path || !Storage::disk('public')->exists($this->file_path)) {
            return null;
        }

        return route('manual-book.download', ['v' => $this->updated_at?->timestamp ?? now()->timestamp]);
    }

    public function getReadableFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return '-';
        }

        return number_format($this->file_size / 1024 / 1024, 2) . ' MB';
    }
}
