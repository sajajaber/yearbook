<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;

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
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:draft,active,archived',
        ]);

        AcademicYear::create($validated);

        return redirect()->route('academic-years.index');
    }

    public function update(Request $request, string $id)
    {
        $academicYear = AcademicYear::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:draft,active,archived',
        ]);

        $academicYear->update($validated);

        return redirect()->route('academic-years.index');
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $academicYear = AcademicYear::findOrFail($id);
        $academicYear->delete();

        return redirect()->route('academic-years.index');
    }
}
