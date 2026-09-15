<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Graduate;

class ReviewController extends Controller
{
    public function graduate(string $id)
    {
        $graduate = Graduate::with(['school', 'major', 'campus', 'graduation', 'portraitMedia', 'resumeMedia', 'reviewFeedback' => fn ($query) => $query->latest()])->findOrFail($id);
        return view('reviews.graduate', compact('graduate'));
    }

    public function event(string $id)
    {
        $event = Event::with(['academicYear', 'category', 'campuses', 'schools', 'media', 'reviewFeedback' => fn ($query) => $query->latest()])->findOrFail($id);
        return view('reviews.event', compact('event'));
    }
}
