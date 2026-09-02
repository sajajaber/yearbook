<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\School;
use App\Models\Major;
use App\Models\EventCategory;
use App\Models\User;
use App\Models\Role;

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
        ]);
    }
}
