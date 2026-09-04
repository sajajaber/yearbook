<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class AcademicYear extends Model
{
    protected $fillable = ['title', 'start_date', 'end_date', 'status'];

    /**
     * The "yearbook year" this academic year belongs to.
     *
     * Academic years run roughly October–August and span two calendar
     * years (e.g. Oct 2026–Aug 2027). The yearbook for that period is
     * published under the later calendar year — "yearbook27" for an
     * academic year that ends in 2027.
     */
    public function yearbookYear(): int
    {
        return Carbon::parse($this->end_date)->year;
    }

    /** Academic year IDs whose yearbook year matches the given year. */
    public static function idsForYearbookYear(int $yearbookYear): Collection
    {
        return static::all()
            ->filter(fn(self $ay) => $ay->yearbookYear() === $yearbookYear)
            ->pluck('id');
    }

    /** All yearbook years currently in use, newest first. */
    public static function allYearbookYears(): Collection
    {
        return static::all()
            ->map(fn(self $ay) => $ay->yearbookYear())
            ->unique()
            ->sortDesc()
            ->values();
    }
}
