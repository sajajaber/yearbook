<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'file_name',
        'path',
        'thumbnail_path',
        'type',
        'caption',
        'alt_text',
        'credit',
        'tags',
        'uploaded_by',
        'checksum',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_media')
            ->withPivot('display_order');
    }

    public function graduates()
    {
        return $this->belongsToMany(Graduate::class, 'graduate_media')
            ->withPivot('display_order');
    }

    public function portraitGraduates()
    {
        return $this->hasMany(Graduate::class, 'portrait_media_id');
    }

    /**
     * Find an existing media record with the same file checksum, if any.
     * Used to flag duplicate uploads before a second copy is stored.
     */
    public static function findByChecksum(?string $checksum): ?self
    {
        if (! $checksum) {
            return null;
        }

        return static::where('checksum', $checksum)->first();
    }

    /**
     * Public URL for the thumbnail if one exists, otherwise the
     * full-size image. Safe to use anywhere a preview image is needed
     * so callers don't have to null-check thumbnail_path themselves.
     */
    public function thumbnailUrl(): string
    {
        return Storage::disk('public')->url($this->thumbnail_path ?? $this->path);
    }
}
