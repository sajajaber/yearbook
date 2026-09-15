<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ReviewFeedback extends Model
{
    protected $table = 'review_feedback';

    protected $fillable = [
        'reviewable_type',
        'reviewable_id',
        'reviewer_id',
        'message',
        'status',
    ];

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}
