<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Graduate;
use App\Models\School;
use App\Models\Campus;
use App\Models\Major;
use App\Models\Graduation;
use App\Models\AuditLog;

class GraduateController extends Controller
{
    public function index()
    {
        $graduates = Graduate::all();
        return view('graduates.index', ['graduates' => $graduates]);
    }

    public function create()
    {
        $schools = School::where('status', 'active')->get();
        $majors = Major::all();
        $campuses = Campus::where('status', 'active')->get();
        $graduations = Graduation::all();

        return view('graduates.create', [
            'schools' => $schools,
            'majors' => $majors,
            'campuses' => $campuses,
            'graduations' => $graduations,
        ]);
    }

    public function store(Request $request)
    {
        // we validate here so we can catch the violation before it reaches the DB
        $validated = $request->validate([
            'student_reference' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'major_id' => 'required|exists:majors,id',
            'campus_id' => 'required|exists:campuses,id',
            'graduation_id' => 'required|exists:graduations,id',
            'profile_text' => 'nullable|string',
            'future_plans' => 'nullable|string',
            'quote' => 'nullable|string|max:255',
            'consent_status' => 'required|in:pending,granted,declined',
            'publish_status' => [
                'required',
                'in:draft,reviewed,approved,published,archived',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === 'published' && $request->input('consent_status') !== 'granted') {
                        $fail('A graduate cannot be published without granted consent.');
                    }
                },
            ],
        ]);

        $graduate = Graduate::create($validated);
        AuditLog::record('created', $graduate);

        return redirect()->route('graduates.index');
    }

    public function edit(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $schools = School::all();
        $majors = Major::all();
        $campuses = Campus::all();
        $graduations = Graduation::all();

        return view('graduates.edit', [
            'graduate' => $graduate,
            'schools' => $schools,
            'majors' => $majors,
            'campuses' => $campuses,
            'graduations' => $graduations,
        ]);
    }

    public function update(Request $request, string $id)
    {

        $graduate = Graduate::findOrFail($id);

        $validated = $request->validate([
            'student_reference' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'major_id' => 'required|exists:majors,id',
            'campus_id' => 'required|exists:campuses,id',
            'graduation_id' => 'required|exists:graduations,id',
            'profile_text' => 'nullable|string',
            'future_plans' => 'nullable|string',
            'quote' => 'nullable|string|max:255',
            'consent_status' => 'required|in:pending,granted,declined',
            'publish_status' => [
                'required',
                'in:draft,reviewed,approved,published,archived',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === 'published' && $request->input('consent_status') !== 'granted') {
                        $fail('A graduate cannot be published without granted consent.');
                    }
                },
            ],
        ]);

        $graduate->update($validated);
        AuditLog::record('updated', $graduate);

        return redirect()->route('graduates.index');
    }

    public function destroy(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $graduate->delete();
        AuditLog::record('deleted', $graduate);
        return redirect()->route('graduates.index');
    }

    public function submitForReview(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $graduate->update(['publish_status' => 'reviewed']);
        AuditLog::record('submitted_for_review', $graduate);
        return redirect()->route('graduates.index');
    }

    public function approve(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $graduate->update(['publish_status' => 'approved']);
        AuditLog::record('approved', $graduate);
        return redirect()->route('graduates.index');
    }

    public function reject(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $graduate->update(['publish_status' => 'draft']);
        AuditLog::record('rejected', $graduate);
        return redirect()->route('graduates.index');
    }
}
