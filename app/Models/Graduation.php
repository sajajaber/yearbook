<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Graduation extends Model
{
    protected $fillable = [
        'academic_year_id',
        'ceremony_date',
        'venue',
        'description',
        'status',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function campuses()
    {
        return $this->belongsToMany(Campus::class, 'graduation_campuses')->withTimestamps();
    }

    public function schools()
    {
        return $this->belongsToMany(School::class, 'graduation_schools')->withTimestamps();
    }

    public function media()
    {
        return $this->belongsToMany(Media::class, 'graduation_media')
            ->withPivot('display_order')
            ->orderBy('graduation_media.display_order');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', 'archived');
    }

    public function archive(): bool
    {
        return $this->update(['status' => 'archived']);
    }

    public function unarchive(): bool
    {
        return $this->update(['status' => 'active']);
    }
}
