<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Graduation extends Model
{
    protected $fillable = [
        'academic_year_id',
        'ceremony_date',
        'venue',
        'description',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function campuses()
    {
        return $this->belongsToMany(Campus::class, 'graduation_campuses')->withTimestamps();
    }
}