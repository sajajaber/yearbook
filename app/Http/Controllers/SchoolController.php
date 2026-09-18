<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Graduate;
use App\Models\Major;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::all();
        return view('schools.index', ['schools' => $schools]);
    }

    public function create()
    {
        return view('schools.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:schools,code',
            'status' => 'required|in:active,archived',
        ]);

        $school = School::create($validated);
        AuditLog::record('created', $school);
        return redirect()->back()
            ->with('success', 'School added.')
            ->with('active_tab', $request->input('tab', 'schools'));
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $school = School::findOrFail($id);
        return view('schools.edit', ['school' => $school]);
    }

    public function update(Request $request, string $id)
    {
        $school = School::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:schools,code,' . $id,
            'status' => 'required|in:active,archived',
        ]);

        $school->update($validated);
        AuditLog::record('updated', $school);

        return redirect()->back()
            ->with('success', 'School updated.')
            ->with('active_tab', $request->input('tab', 'schools'));
    }

    public function destroy(string $id)
    {
        $school = School::findOrFail($id);

        $isReferenced = Major::where('school_id', $school->id)->exists()
            || Graduate::where('school_id', $school->id)->exists()
            || DB::table('event_schools')->where('school_id', $school->id)->exists()
            || DB::table('graduation_schools')->where('school_id', $school->id)->exists();

        if ($isReferenced) {
            return redirect()->back()
                ->with('error', 'This school cannot be deleted because it is still assigned to majors, graduates, events, or graduation records.')
                ->with('active_tab', request()->input('tab', 'schools'));
        }

        try {
            $school->delete();
        } catch (QueryException $e) {
            return redirect()->back()
                ->with('error', 'This school cannot be deleted because it is still being used by other records.')
                ->with('active_tab', request()->input('tab', 'schools'));
        }

        AuditLog::record('deleted', $school);
        return redirect()->back()
            ->with('success', 'School deleted.')
            ->with('active_tab', request()->input('tab', 'schools'));
    }
}
