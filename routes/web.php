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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:admin,editor'])->group(function () {
    Route::resource('academic-years', AcademicYearController::class);
    Route::resource('majors', MajorController::class);
    Route::resource('campuses', CampusController::class);
    Route::resource('schools', SchoolController::class);
    Route::resource('event-categories', EventCategoryController::class);
    Route::resource('events', EventController::class);
    Route::resource('graduations', GraduationController::class);
    Route::resource('graduates', GraduateController::class);
    });
    

require __DIR__.'/auth.php';
