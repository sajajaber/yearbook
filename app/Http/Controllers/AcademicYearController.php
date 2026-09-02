<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\AuditLog;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $academicYears = AcademicYear::all();
        return view('academic-years.index', ['academicYears' => $academicYears]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('academic-years.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:academic_years,title',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:draft,active,archived',
        ]);

        if ($validated['status'] === 'active') {
            $this->ensureNoOtherActiveYear();
        }

        $academicYear = AcademicYear::create($validated);
        AuditLog::record('created', $academicYear);

        return redirect()->back()->with('success', 'Academic year added.');
    }

    public function update(Request $request, string $id)
    {
        $academicYear = AcademicYear::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:academic_years,title,' . $id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:draft,active,archived',
        ]);

        if ($validated['status'] === 'active') {
            $this->ensureNoOtherActiveYear($id);
        }

        $academicYear->update($validated);
        AuditLog::record('updated', $academicYear);

        return redirect()->back()->with('success', 'Academic year updated.');
    }

    public function destroy(string $id)
    {
        $academicYear = AcademicYear::findOrFail($id);

        try {
            $academicYear->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->back()->with('error', 'This academic year cannot be deleted because it still has events, graduations, or graduates linked to it. Archive it instead, or remove those records first.');
            }

            throw $e;
        }

        AuditLog::record('deleted', $academicYear);

        return redirect()->back()->with('success', 'Academic year deleted.');
    }

    private function ensureNoOtherActiveYear(?string $excludeId = null): void
    {
        $alreadyActive = AcademicYear::where('status', 'active')
            ->when($excludeId, fn($query) => $query->where('id', '!=', $excludeId))
            ->exists();

        if ($alreadyActive) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'status' => 'Another academic year is already active. Archive or change it before activating this one.',
            ]);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $academicYear = AcademicYear::findOrFail($id);

        return view('academic-years.edit', ['academicYear' => $academicYear]);
    }
}
