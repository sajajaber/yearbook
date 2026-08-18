<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Graduation;
use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\AuditLog;
class GraduationController extends Controller
{
    public function index()
    {
        $graduations = Graduation::all();
        return view('graduations.index', ['graduations' => $graduations]);
    }

    public function create()
    {
        $academicYears = AcademicYear::where('status', '!=', 'archived')->get();
        $campuses = Campus::where('status', 'active')->get();

        return view('graduations.create', [
            'academicYears' => $academicYears,
            'campuses' => $campuses,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'ceremony_date' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $graduation = Graduation::create($validated);
        $graduation->campuses()->sync($request->input('campus_ids', []));
        AuditLog::record('created', $graduation);

        return redirect()->route('graduations.index');
    }

    public function edit(string $id)
    {
        $graduation = Graduation::findOrFail($id);
        $academicYears = AcademicYear::all();
        $campuses = Campus::all();

        return view('graduations.edit', [
            'graduation' => $graduation,
            'academicYears' => $academicYears,
            'campuses' => $campuses,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $graduation = Graduation::findOrFail($id);

        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'ceremony_date' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string', 
        ]);

        $graduation->update($validated);
        $graduation->campuses()->sync($request->input('campus_ids', []));
        AuditLog::record('updated', $graduation);

        return redirect()->route('graduations.index');
    }

    public function destroy(string $id)
    {
        $graduation = Graduation::findOrFail($id);
        $graduation->delete();
        AuditLog::record('deleted', $graduation);
        return redirect()->route('graduations.index');
    }
}
