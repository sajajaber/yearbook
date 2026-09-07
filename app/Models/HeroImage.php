<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroImage extends Model
{
    protected $fillable = ['media_id', 'display_order'];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    /**
     * Replace the full ordered set of hero images in one go.
     * Accepts an ordered array of media IDs.
     */
    public static function syncOrdered(array $mediaIds): void
    {
        static::query()->delete();

        foreach (array_values($mediaIds) as $index => $mediaId) {
            static::create([
                'media_id' => $mediaId,
                'display_order' => $index,
            ]);
        }
    }

    public static function orderedMedia()
    {
        return Media::query()
            ->join('hero_images', 'media.id', '=', 'hero_images.media_id')
            ->orderBy('hero_images.display_order')
            ->select('media.*')
            ->get();
    }
}
