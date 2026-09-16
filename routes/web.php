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

Route::get('/', [PublicYearbookController::class, 'index'])->name('public.home');
Route::prefix('yearbook')->name('public.')->group(function () {
    Route::get('/', [PublicYearbookController::class, 'archive'])->name('archive');
    Route::get('/hero-images', function () {
        return HeroImage::orderedMedia()->map(fn($media) => ['id' => $media->id, 'url' => asset('storage/' . $media->path)])->values();
    })->name('hero-images');
    Route::get('/events/{id}', [PublicYearbookController::class, 'eventDetail'])->name('event.detail');
    Route::get('/events', [PublicYearbookController::class, 'events'])->name('events');
    Route::get('/graduates', [PublicYearbookController::class, 'graduates'])->name('graduates');
    Route::get('/graduates/{id}', [PublicYearbookController::class, 'graduateDetail'])->name('graduate.detail');
    Route::get('/graduates/{id}/resume', [PublicGraduateResumeController::class, 'show'])->name('graduate.resume');
    Route::get('/timeline', [PublicYearbookController::class, 'timeline'])->name('timeline');
    Route::get('/graduates/{id}/pdf', [YearbookPdfController::class, 'graduate'])->name('graduate.pdf');
    Route::get('/graduations/{id}', [PublicYearbookController::class, 'graduationDetail'])->name('graduation.detail');
    Route::get('/graduations', [PublicYearbookController::class, 'graduations'])->name('graduations');
    Route::get('/{academicYear}/pdf', [YearbookPdfController::class, 'book'])->name('book.pdf');
    Route::get('/{academicYear}', [PublicYearbookController::class, 'book'])->name('book');
});
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::post('/search', [SearchController::class, 'search'])->name('search.perform');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'role:admin,editor,reviewer'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/hero-images', [SettingsController::class, 'updateHeroImages'])->name('settings.hero-images.update');
    Route::resource('users', UserController::class);
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});
Route::middleware(['auth', 'verified', 'role:admin,editor'])->group(function () {
    Route::resource('academic-years', AcademicYearController::class);
    Route::resource('majors', MajorController::class);
    Route::resource('campuses', CampusController::class);
    Route::resource('schools', SchoolController::class);
    Route::resource('event-categories', EventCategoryController::class);
    Route::resource('graduations', GraduationController::class);
    Route::resource('media', MediaController::class)->except(['show']);
    Route::resource('events', EventController::class)->except(['index', 'show']);
    Route::resource('graduates', GraduateController::class)->except(['index', 'show']);

    Route::get('graduates/import', [GraduateImportController::class, 'create'])
        ->name('graduates.import');
    Route::get('graduates/import/template', [GraduateImportController::class, 'template'])
        ->name('graduates.import.template');
    Route::post('graduates/import/preview', [GraduateImportController::class, 'preview'])
        ->name('graduates.import.preview');
    Route::post('graduates/import', [GraduateImportController::class, 'store'])
        ->name('graduates.import.store');

    Route::post('media/{media}/generate-caption', [MediaController::class, 'generateCaption'])
        ->name('media.generate-caption');

    Route::post('media/{media}/generate-tags', [MediaController::class, 'generateTags'])
        ->name('media.generate-tags');
});
Route::post('graduations/{graduation}/unarchive', [GraduationController::class, 'unarchive'])->middleware(['auth', 'verified', 'role:admin,editor'])->name('graduations.unarchive');
require __DIR__ . '/auth.php';
