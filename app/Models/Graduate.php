<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Campus;
use App\Models\School;
use App\Models\Major;
use App\Models\Graduation;
use App\Contracts\PublishableInterface;
use App\Models\AiGeneration;

class Graduate extends Model implements PublishableInterface
{
  protected $fillable = [
    'student_reference',
    'name',
    'school_id',
    'major_id',
    'campus_id',
    'graduation_id',
    'profile_text',
    'achievements',
    'activities',
    'projects',
    'internships',
    'future_plans',
    'quote',
    'consent_status',
    'publish_status',
    'portrait_media_id',
  ];

  protected function casts(): array
  {
    return [
      'achievements' => 'array',
      'activities' => 'array',
      'projects' => 'array',
      'internships' => 'array',
    ];
  }

  public function school()
  {
    return $this->belongsTo(School::class);
  }

  public function major()
  {
    return $this->belongsTo(Major::class);
  }

  public function campus()
  {
    return $this->belongsTo(Campus::class);
  }

  public function graduation()
  {
    return $this->belongsTo(Graduation::class);
  }

  public function isPublished(): bool
  {
    return $this->publish_status === 'published';
  }

  public function aiGenerations()
  {
    return $this->hasMany(AiGeneration::class, 'source_record_id')
      ->where('source_record_type', 'graduate');
  }

  public function canBePublished(): bool
  {
    return static::consentAllowsPublishing($this->consent_status);
  }

  public static function consentAllowsPublishing(?string $consentStatus): bool
  {
    return $consentStatus === 'granted';
  }

  public function media()
  {
    return $this->belongsToMany(Media::class, 'graduate_media')
      ->withPivot('display_order')
      ->orderBy('graduate_media.display_order');
  }
}
