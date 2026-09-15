<?php

namespace App\Http\Controllers;

use App\Models\ReviewFeedback;
use Illuminate\Http\Request;

class ReviewFeedbackController extends Controller
{
    public function resolve(ReviewFeedback $feedback)
    {
        abort_unless($feedback->reviewer_id === auth()->id() || auth()->user()->role?->role_name === 'admin', 403);

        $feedback->update(['status' => 'resolved']);

        return back()->with('success', 'Review note marked as resolved.');
    }
}
