<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Graduation;
use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\School;
use App\Models\Major;
use App\Contracts\PublishableInterface;
use App\Models\AiGeneration;
use App\Models\ReviewFeedback;
use App\Models\Concerns\HasPublishingWorkflow;

class Graduate extends Model implements PublishableInterface
{
  use HasPublishingWorkflow;

  protected function statusColumn(): string
  {
    return 'publish_status';
  }

  protected $fillable = [
    'student_reference', 'name', 'school_id', 'major_id', 'campus_id',
    'academic_year_id', 'graduation_id', 'profile_text', 'achievements',
    'activities', 'projects', 'internships', 'future_plans', 'quote',
    'consent_status', 'publish_status', 'portrait_media_id', 'resume_media_id', 'degree_level',
    'gpa',
  ];

  protected function casts(): array
  {
    return [
      'achievements' => 'array', 'activities' => 'array', 'projects' => 'array', 'internships' => 'array',
      'gpa' => 'decimal:2',
    ];
  }

  public function school() { return $this->belongsTo(School::class); }
  public function major() { return $this->belongsTo(Major::class); }
  public function campus() { return $this->belongsTo(Campus::class); }
  public function academicYear() { return $this->belongsTo(AcademicYear::class); }
  public function graduation() { return $this->belongsTo(Graduation::class); }

  public function isPublished(): bool { return $this->publish_status === 'published'; }

  public function aiGenerations()
  {
    return $this->hasMany(AiGeneration::class, 'source_record_id')->where('source_record_type', 'graduate');
  }

  public function reviewFeedback()
  {
    return $this->morphMany(ReviewFeedback::class, 'reviewable');
  }

  /**
   * Consent controls the level of public detail, not whether the
   * directory entry itself may be published. Pending/declined
   * graduates can therefore be published as name-only entries.
   */
  public function canBePublished(): bool { return true; }
  protected function guardPublish(): bool { return true; }
  public static function consentAllowsPublishing(?string $consentStatus): bool { return $consentStatus === 'granted'; }

  public function media()
  {
    return $this->belongsToMany(Media::class, 'graduate_media')
      ->withPivot('display_order')->orderBy('graduate_media.display_order');
  }

  public function portraitMedia() { return $this->belongsTo(Media::class, 'portrait_media_id'); }

  public function resumeMedia() { return $this->belongsTo(Media::class, 'resume_media_id'); }
}
