<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiGeneration extends Model
{
  use HasFactory;

  protected $fillable = [
    'content_type',
    'source_record_id',
    'source_record_type',
    'prompt_version',
    'generated_text',
    'reviewed_text',
    'reviewer_id',
    'status',
  ];

  public function reviewer()
  {
    return $this->belongsTo(User::class, 'reviewer_id');
  }

  /*
     Fetch the actual source model (Event or Graduate) this generation
     was drafted from based on the source_record_type and source_record_id.
  */
  public function sourceRecord(): ?Model
  {
    return match ($this->source_record_type) {
      'event' => Event::find($this->source_record_id),
      'graduate' => Graduate::with(['school', 'major'])->find($this->source_record_id),
      default => null,
    };
  }
}
