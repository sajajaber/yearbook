<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AiGenerationController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GraduationController;
use App\Http\Controllers\GraduateController;
use App\Http\Controllers\GraduateImportController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicGraduateController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewFeedbackController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PublicYearbookController;
use App\Http\Controllers\PublicGraduateResumeController;
use App\Http\Controllers\YearbookPdfController;
use App\Models\HeroImage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Yearbook Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicYearbookController::class, 'index'])
    ->name('public.home');

Route::get('/archive', [PublicYearbookController::class, 'archive'])
    ->name('public.archive');

Route::get('/hero-images', function () {
    return HeroImage::orderedMedia()
        ->map(fn($media) => [
            'id' => $media->id,
            'url' => asset('storage/' . $media->path),
        ])
        ->values();
})->name('public.hero-images');

Route::get('/events', [PublicYearbookController::class, 'events'])
    ->name('public.events');

Route::get('/events/{id}', [PublicYearbookController::class, 'eventDetail'])
    ->name('public.event.detail');

Route::get('/graduates', [PublicYearbookController::class, 'graduates'])
    ->name('public.graduates');

Route::get('/graduates/{student_reference}', [PublicGraduateController::class, 'show'])
    ->name('public.graduate.detail');

Route::get('/graduates/{student_reference}/resume', [PublicGraduateResumeController::class, 'show'])
    ->name('public.graduate.resume');

Route::get('/graduates/{student_reference}/pdf', [YearbookPdfController::class, 'graduate'])
    ->name('public.graduate.pdf');

Route::get('/timeline', [PublicYearbookController::class, 'timeline'])
    ->name('public.timeline');

Route::get('/graduations', [PublicYearbookController::class, 'graduations'])
    ->name('public.graduations');

Route::get('/graduations/{id}', [PublicYearbookController::class, 'graduationDetail'])
    ->name('public.graduation.detail');

Route::get('/search', [SearchController::class, 'index'])
    ->name('search.index');

Route::post('/search', [SearchController::class, 'search'])
    ->middleware('throttle:20,1')
    ->name('search.perform');

/*
|--------------------------------------------------------------------------
| Dashboard / Profile
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin,editor,reviewer'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin: Administration / Settings
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('settings', [SettingsController::class, 'index'])
        ->name('settings.index');

    Route::post('settings/hero-images', [SettingsController::class, 'updateHeroImages'])
        ->name('settings.hero-images.update');

    Route::resource('users', UserController::class);

    Route::get('audit-logs', [AuditLogController::class, 'index'])
        ->name('audit-logs.index');
});

/*
|--------------------------------------------------------------------------
| Admin: Academic Data + Content Creation
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:admin,editor'])->group(function () {

    Route::resource('academic-years', AcademicYearController::class);

    Route::resource('majors', MajorController::class);

    Route::resource('campuses', CampusController::class);

    Route::resource('schools', SchoolController::class);

    Route::resource('event-categories', EventCategoryController::class);

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    |
    | Kept under /admin so they cannot collide with:
    | /events/{id}
    |
    */

    Route::prefix('admin')->group(function () {
        Route::resource('events', EventController::class)
            ->except(['index', 'show']);
    });

    /*
    |--------------------------------------------------------------------------
    | Graduates
    |--------------------------------------------------------------------------
    |
    | Kept under /admin so they cannot collide with:
    | /graduates/{student_reference}
    | /graduates/{student_reference}/resume
    | /graduates/{student_reference}/pdf
    |
    */

    Route::prefix('admin')->group(function () {
        Route::resource('graduates', GraduateController::class)
            ->except(['index', 'show']);

        Route::get('graduates/import', [GraduateImportController::class, 'create'])
            ->name('graduates.import');

        Route::get('graduates/import/template', [GraduateImportController::class, 'template'])
            ->name('graduates.import.template');

        Route::post('graduates/import/preview', [GraduateImportController::class, 'preview'])
            ->name('graduates.import.preview');

        Route::post('graduates/import', [GraduateImportController::class, 'store'])
            ->name('graduates.import.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Graduations
    |--------------------------------------------------------------------------
    |
    | Kept under /admin so they cannot collide with:
    | /graduations
    | /graduations/{id}
    |
    */

    Route::prefix('admin')->group(function () {
        Route::resource('graduations', GraduationController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Media
    |--------------------------------------------------------------------------
    */

    Route::resource('media', MediaController::class)
        ->except(['show']);

    Route::post('media/ai-suggestions', [MediaController::class, 'aiSuggestions'])
        ->middleware('throttle:10,1')
        ->name('media.ai-suggestions');

    Route::post('media/{media}/generate-caption', [MediaController::class, 'generateCaption'])
        ->middleware('throttle:10,1')
        ->name('media.generate-caption');

    Route::post('media/{media}/generate-tags', [MediaController::class, 'generateTags'])
        ->middleware('throttle:10,1')
        ->name('media.generate-tags');
});

/*
|--------------------------------------------------------------------------
| Admin: Index / Review Access
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:admin,editor,reviewer'])->group(function () {

    Route::get('admin/events', [EventController::class, 'index'])
        ->name('events.index');

    Route::get('admin/graduates', [GraduateController::class, 'index'])
        ->name('graduates.index');

    Route::resource('media', MediaController::class)
        ->only(['index', 'show']);

    Route::get('reviews/events/{event}', [ReviewController::class, 'event'])
        ->name('reviews.events.show');

    Route::get('reviews/graduates/{graduate}', [ReviewController::class, 'graduate'])
        ->name('reviews.graduates.show');
});

/*
|--------------------------------------------------------------------------
| Review / Publishing Workflow
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::post('events/{event}/submit', [EventController::class, 'submitForReview'])
        ->middleware('role:admin,editor')
        ->name('events.submit');

    Route::post('events/{event}/approve', [EventController::class, 'approve'])
        ->middleware('role:admin,reviewer')
        ->name('events.approve');

    Route::post('events/{event}/request-changes', [EventController::class, 'requestChanges'])
        ->middleware('role:admin,reviewer')
        ->name('events.request-changes');

    Route::post('events/{event}/publish', [EventController::class, 'publish'])
        ->middleware('role:admin,reviewer')
        ->name('events.publish');

    Route::post('graduates/{graduate}/submit', [GraduateController::class, 'submitForReview'])
        ->middleware('role:admin,editor')
        ->name('graduates.submit');

    Route::post('graduates/{graduate}/approve', [GraduateController::class, 'approve'])
        ->middleware('role:admin,reviewer')
        ->name('graduates.approve');

    Route::post('graduates/{graduate}/request-changes', [GraduateController::class, 'requestChanges'])
        ->middleware('role:admin,reviewer')
        ->name('graduates.request-changes');

    Route::post('graduates/{graduate}/publish', [GraduateController::class, 'publish'])
        ->middleware('role:admin,reviewer')
        ->name('graduates.publish');

    Route::patch('review-feedback/{feedback}/resolve', [ReviewFeedbackController::class, 'resolve'])
        ->middleware('role:admin,reviewer')
        ->name('review-feedback.resolve');

    Route::get('notifications/{notification}/read', [NotificationController::class, 'redirect'])
        ->name('notifications.read');
});

/*
|--------------------------------------------------------------------------
| AI Generation
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:admin,editor'])->group(function () {

    Route::post('events/{event}/generate-summary', [EventController::class, 'generateSummary'])
        ->middleware('throttle:10,1')
        ->name('events.generate-summary');

    Route::post('graduates/{graduate}/generate-biography', [GraduateController::class, 'generateBiography'])
        ->middleware('throttle:10,1')
        ->name('graduates.generate-biography');
});

Route::middleware(['auth', 'verified', 'role:admin,reviewer'])->group(function () {

    Route::get('ai-generations', [AiGenerationController::class, 'index'])
        ->name('ai-generations.index');

    Route::post('ai-generations/{aiGeneration}/review', [AiGenerationController::class, 'review'])
        ->middleware('throttle:30,1')
        ->name('ai-generations.review');
});

/*
|--------------------------------------------------------------------------
| Graduation Actions
|--------------------------------------------------------------------------
*/

Route::post('admin/graduations/{graduation}/unarchive', [GraduationController::class, 'unarchive'])
    ->middleware(['auth', 'verified', 'role:admin,editor'])
    ->name('graduations.unarchive');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Academic Year Public Pages
|--------------------------------------------------------------------------
|
| Numeric constraint prevents these routes from catching:
| /dashboard
| /search
| /events
| /graduates
| /graduations
| etc.
|
*/

Route::get('/{academicYear}/pdf', [YearbookPdfController::class, 'book'])
    ->whereNumber('academicYear')
    ->name('public.book.pdf');

Route::get('/{academicYear}', [PublicYearbookController::class, 'book'])
    ->whereNumber('academicYear')
    ->name('public.book');
