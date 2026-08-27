<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GraduationController;
use App\Http\Controllers\GraduateController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AiGenerationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin,editor,reviewer'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Full CRUD — Admin and Editor only (master data + write access to events/graduates)
Route::middleware(['auth', 'verified', 'role:admin,editor'])->group(function () {
    Route::resource('academic-years', AcademicYearController::class);
    Route::resource('majors', MajorController::class);
    Route::resource('campuses', CampusController::class);
    Route::resource('schools', SchoolController::class);
    Route::resource('event-categories', EventCategoryController::class);
    Route::resource('graduations', GraduationController::class);
    Route::resource('media', MediaController::class)->except(['show']);

    Route::resource('events', EventController::class)->except(['index', 'show', 'edit']);
    Route::resource('graduates', GraduateController::class)->except(['index', 'show', 'edit']);
});

// Read-only access — Admin, Editor, AND Reviewer (so Reviewer can see the pending queue)
Route::middleware(['auth', 'verified', 'role:admin,editor,reviewer'])->group(function () {
    Route::resource('events', EventController::class)->only(['index', 'show', 'edit']);
    Route::resource('graduates', GraduateController::class)->only(['index', 'show', 'edit']);
});

// Workflow actions — submit (Admin/Editor), approve/reject/publish (Admin/Reviewer)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('events/{event}/submit', [EventController::class, 'submitForReview'])
        ->middleware('role:admin,editor')->name('events.submit');
    Route::post('events/{event}/approve', [EventController::class, 'approve'])
        ->middleware('role:admin,reviewer')->name('events.approve');
    Route::post('events/{event}/reject', [EventController::class, 'reject'])
        ->middleware('role:admin,reviewer')->name('events.reject');
    Route::post('events/{event}/publish', [EventController::class, 'publish'])
        ->middleware('role:admin,reviewer')->name('events.publish');

    Route::post('graduates/{graduate}/submit', [GraduateController::class, 'submitForReview'])
        ->middleware('role:admin,editor')->name('graduates.submit');
    Route::post('graduates/{graduate}/approve', [GraduateController::class, 'approve'])
        ->middleware('role:admin,reviewer')->name('graduates.approve');
    Route::post('graduates/{graduate}/reject', [GraduateController::class, 'reject'])
        ->middleware('role:admin,reviewer')->name('graduates.reject');
    Route::post('graduates/{graduate}/publish', [GraduateController::class, 'publish'])
        ->middleware('role:admin,reviewer')->name('graduates.publish');

    Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::middleware(['auth', 'verified', 'role:admin,editor', 'throttle:6,1'])->group(function () {
        Route::post('events/{event}/generate-summary', [EventController::class, 'generateSummary'])
            ->name('events.generate-summary');
        Route::post('graduates/{graduate}/generate-biography', [GraduateController::class, 'generateBiography'])
            ->name('graduates.generate-biography');
    });

    Route::middleware(['auth', 'verified', 'role:admin,reviewer'])->group(function () {
        Route::get('ai-generations', [AiGenerationController::class, 'index'])->name('ai-generations.index');
        Route::post('ai-generations/{aiGeneration}/review', [AiGenerationController::class, 'review'])->name('ai-generations.review');
    });

    Route::post('graduations/{graduation}/unarchive', [GraduationController::class, 'unarchive'])
        ->middleware('role:admin,editor')->name('graduations.unarchive');
});

require __DIR__ . '/auth.php';
