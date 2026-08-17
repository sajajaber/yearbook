<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

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

        School::create($validated);

        return redirect()->route('schools.index');
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

        return redirect()->route('schools.index');
    }

    public function destroy(string $id)
    {
        $school = School::findOrFail($id);
        $school->delete();
        return redirect()->route('schools.index');
    }
}