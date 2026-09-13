<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\School;
use App\Models\Major;
use App\Models\EventCategory;
use App\Models\User;
use App\Models\Role;
use App\Models\Media;
use App\Models\HeroImage;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index', [
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(),
            'campuses' => Campus::orderBy('name')->get(),
            'schools' => School::orderBy('name')->get(),
            'majors' => Major::with('school')->orderBy('name')->get(),
            'eventCategories' => EventCategory::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
            'roles' => Role::get(),
            'heroImagePool' => Media::where('type', 'image')
                ->where(function ($q) {
                    $q->whereJsonDoesntContain('tags', 'graduate-portrait')
                        ->orWhereNull('tags');
                })
                ->latest()
                ->get(),
            'selectedHeroImages' => HeroImage::orderedMedia(),
        ]);
    }

    public function updateHeroImages(Request $request)
    {
        $validated = $request->validate([
            'media_ids' => 'nullable|array',
            'media_ids.*' => 'integer|distinct|exists:media,id',
        ]);

        // The submitted array order is the canonical slideshow order.
        // HeroImage::syncOrdered() writes that exact sequence to display_order.
        HeroImage::syncOrdered(array_values($validated['media_ids'] ?? []));

        AuditLog::record('updated', new HeroImage());

        return redirect()->back()
            ->with('success', 'Hero slideshow order saved.')
            ->with('active_tab', 'hero-images');
    }
}
