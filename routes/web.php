<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AiGenerationController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GraduationController;
use App\Http\Controllers\GraduateController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PublicYearbookController;
use App\Http\Controllers\YearbookPdfController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public Yearbook
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicYearbookController::class, 'index'])->name('public.home');

Route::prefix('yearbook')->name('public.')->group(function () {
  Route::get('/', [PublicYearbookController::class, 'archive'])->name('archive');
  Route::get('/events/{id}', [PublicYearbookController::class, 'eventDetail'])->name('event.detail');
  Route::get('/events', [PublicYearbookController::class, 'events'])->name('events');
  Route::get('/graduates', [PublicYearbookController::class, 'graduates'])->name('graduates');
  Route::get('/graduates/{id}', [PublicYearbookController::class, 'graduateDetail'])->name('graduate.detail');
  Route::get('/timeline', [PublicYearbookController::class, 'timeline'])->name('timeline');
  Route::get('/graduates/{id}/pdf', [YearbookPdfController::class, 'graduate'])->name('graduate.pdf');
  Route::get('/{academicYear}/pdf', [YearbookPdfController::class, 'book'])->name('book.pdf');
  Route::get('/{academicYear}', [PublicYearbookController::class, 'book'])->name('book');
  Route::get('/graduations/{id}', [PublicYearbookController::class, 'graduationDetail'])->name('graduation.detail');
  Route::get('/graduations', [PublicYearbookController::class, 'graduations'])->name('graduations');
});

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

Route::get('/search', [SearchController::class, 'index'])
  ->name('search.index');

Route::post('/search', [SearchController::class, 'search'])
  ->name('search.perform');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
  ->middleware(['auth', 'verified', 'role:admin,editor,reviewer'])
  ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

  Route::get('/profile', [ProfileController::class, 'edit'])
    ->name('profile.edit');

  Route::patch('/profile', [ProfileController::class, 'update'])
    ->name('profile.update');

  Route::delete('/profile', [ProfileController::class, 'destroy'])
    ->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Admin Settings
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {

  Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
  Route::post('settings/hero-images', [SettingsController::class, 'updateHeroImages'])->name('settings.hero-images.update');
});


/*
|--------------------------------------------------------------------------
| Full CRUD
|--------------------------------------------------------------------------
|
| Admin and Editor:
| - Academic years
| - Majors
| - Campuses
| - Schools
| - Event categories
| - Graduations
| - Media
| - Events
| - Graduates
|
*/

Route::middleware(['auth', 'verified', 'role:admin,editor'])->group(function () {

  Route::resource('academic-years', AcademicYearController::class);

  Route::resource('majors', MajorController::class);

  Route::resource('campuses', CampusController::class);

  Route::resource('schools', SchoolController::class);

  Route::resource('event-categories', EventCategoryController::class);

  Route::resource('graduations', GraduationController::class);

  Route::resource('media', MediaController::class)
    ->except(['show']);

  Route::resource('events', EventController::class)
    ->except(['index', 'show', 'edit']);

  Route::resource('graduates', GraduateController::class)
    ->except(['index', 'show', 'edit']);
});


/*
|--------------------------------------------------------------------------
| Read-Only Access
|--------------------------------------------------------------------------
|
| Admin, Editor, Reviewer:
| - View events
| - View/edit events
| - View graduates
| - View/edit graduates
| - View media
|
*/

Route::middleware(['auth', 'verified', 'role:admin,editor,reviewer'])->group(function () {

  Route::resource('events', EventController::class)
    ->only(['index', 'show', 'edit']);

  Route::resource('graduates', GraduateController::class)
    ->only(['index', 'show', 'edit']);

  Route::resource('media', MediaController::class)
    ->only(['index', 'show']);
});


/*
|--------------------------------------------------------------------------
| Workflow Actions
|--------------------------------------------------------------------------
|
| Submit:
| - Admin
| - Editor
|
| Approve / Reject / Publish:
| - Admin
| - Reviewer
|
*/

Route::middleware(['auth', 'verified'])->group(function () {

  /*
    |--------------------------------------------------------------------------
    | Event Workflow
    |--------------------------------------------------------------------------
    */

  Route::post(
    'events/{event}/submit',
    [EventController::class, 'submitForReview']
  )
    ->middleware('role:admin,editor')
    ->name('events.submit');

  Route::post(
    'events/{event}/approve',
    [EventController::class, 'approve']
  )
    ->middleware('role:admin,reviewer')
    ->name('events.approve');

  Route::post(
    'events/{event}/reject',
    [EventController::class, 'reject']
  )
    ->middleware('role:admin,reviewer')
    ->name('events.reject');

  Route::post(
    'events/{event}/publish',
    [EventController::class, 'publish']
  )
    ->middleware('role:admin,reviewer')
    ->name('events.publish');


  /*
    |--------------------------------------------------------------------------
    | Graduate Workflow
    |--------------------------------------------------------------------------
    */

  Route::post(
    'graduates/{graduate}/submit',
    [GraduateController::class, 'submitForReview']
  )
    ->middleware('role:admin,editor')
    ->name('graduates.submit');

  Route::post(
    'graduates/{graduate}/approve',
    [GraduateController::class, 'approve']
  )
    ->middleware('role:admin,reviewer')
    ->name('graduates.approve');

  Route::post(
    'graduates/{graduate}/reject',
    [GraduateController::class, 'reject']
  )
    ->middleware('role:admin,reviewer')
    ->name('graduates.reject');

  Route::post(
    'graduates/{graduate}/publish',
    [GraduateController::class, 'publish']
  )
    ->middleware('role:admin,reviewer')
    ->name('graduates.publish');
});


/*
|--------------------------------------------------------------------------
| User Management
|--------------------------------------------------------------------------
|
| Admin only
|
*/

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {

  Route::resource('users', UserController::class);
});


/*
|--------------------------------------------------------------------------
| AI Generation
|--------------------------------------------------------------------------
|
| Admin and Editor can generate AI content.
|
*/

Route::middleware([
  'auth',
  'verified',
  'role:admin,editor',
  'throttle:6,1'
])->group(function () {

  Route::post(
    'events/{event}/generate-summary',
    [EventController::class, 'generateSummary']
  )
    ->name('events.generate-summary');

  Route::post(
    'graduates/{graduate}/generate-biography',
    [GraduateController::class, 'generateBiography']
  )
    ->name('graduates.generate-biography');
});


/*
|--------------------------------------------------------------------------
| AI Generation Review
|--------------------------------------------------------------------------
|
| Admin and Reviewer can review generated content.
|
*/

Route::middleware([
  'auth',
  'verified',
  'role:admin,reviewer'
])->group(function () {

  Route::get(
    'ai-generations',
    [AiGenerationController::class, 'index']
  )
    ->name('ai-generations.index');

  Route::post(
    'ai-generations/{aiGeneration}/review',
    [AiGenerationController::class, 'review']
  )
    ->name('ai-generations.review');
});


/*
|--------------------------------------------------------------------------
| Graduation Actions
|--------------------------------------------------------------------------
*/

Route::post(
  'graduations/{graduation}/unarchive',
  [GraduationController::class, 'unarchive']
)
  ->middleware(['auth', 'verified', 'role:admin,editor'])
  ->name('graduations.unarchive');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
