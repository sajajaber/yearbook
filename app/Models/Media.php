<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'file_name',
        'path',
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
}
