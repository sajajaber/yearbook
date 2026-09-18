<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Major;
use App\Models\Graduate;
use Illuminate\Database\QueryException;
use App\Models\School;
use App\Models\AuditLog;

class MajorController extends Controller
{
    public function index()
    {
        $majors = Major::all();
        return view('majors.index', ['majors' => $majors]);
    }

    public function create()
    {
        $schools = School::where('status', 'active')->get();

        return view('majors.create', ['schools' => $schools]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $major = Major::findOrFail($id);
        $schools = School::where('status', 'active')->get();

        return view('majors.edit', ['major' => $major, 'schools' => $schools]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:majors,code',
            'school_id' => 'required|exists:schools,id',
        ]);

        $major = Major::create($validated);
        AuditLog::record('created', $major);
        return redirect()->back()
            ->with('success', 'Major added.')
            ->with('active_tab', $request->input('tab', 'majors'));
    }

    public function update(Request $request, string $id)
    {
        $major = Major::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:majors,code,' . $id,
            'school_id' => 'required|exists:schools,id',
        ]);

        $major->update($validated);
        AuditLog::record('updated', $major);
        return redirect()->back()
            ->with('success', 'Major added.')
            ->with('active_tab', $request->input('tab', 'majors'));
    }

    public function destroy(string $id)
    {
        $major = Major::findOrFail($id);

        if (Graduate::where('major_id', $major->id)->exists()) {
            return redirect()->back()
                ->with('error', 'This major cannot be deleted because it is assigned to one or more graduates.')
                ->with('active_tab', request()->input('tab', 'majors'));
        }

        try {
            $major->delete();
        } catch (QueryException $e) {
            return redirect()->back()
                ->with('error', 'This major cannot be deleted because it is still being used by other records.')
                ->with('active_tab', request()->input('tab', 'majors'));
        }

        AuditLog::record('deleted', $major);
        return redirect()->back()
            ->with('success', 'Major deleted.')
            ->with('active_tab', request()->input('tab', 'majors'));
    }
}
