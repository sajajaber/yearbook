<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AcademicYear;
use App\Models\EventCategory;
use App\Models\Campus;
use App\Models\School;
use App\Contracts\PublishableInterface;
use App\Models\AiGeneration;
use App\Models\Concerns\HasPublishingWorkflow;

class Event extends Model implements PublishableInterface
{
    use HasPublishingWorkflow;

    protected $fillable = [
        'academic_year_id',
        'category_id',
        'title',
        'event_date',
        'description',
        'location',
        'status',
        'featured',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class);
    }

    public function campuses()
    {
        return $this->belongsToMany(Campus::class, 'event_campuses')->withTimestamps();
    }

    public function schools()
    {
        return $this->belongsToMany(School::class, 'event_schools')->withTimestamps();
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function aiGenerations()
    {
        return $this->hasMany(AiGeneration::class, 'source_record_id')
            ->where('source_record_type', 'event');
    }

    public function media()
    {
        return $this->belongsToMany(Media::class, 'event_media')
            ->withPivot('display_order')
            ->orderBy('event_media.display_order');
    }
}
