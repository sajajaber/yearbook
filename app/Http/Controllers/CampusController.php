<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    public function index()
    {
        $campuses = Campus::all();
        return view('campuses.index', ['campuses' => $campuses]);
    }

    public function create()
    {
        return view('campuses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:campuses,code',
            'status' => 'required|in:active,archived',
        ]);

        Campus::create($validated);

        return redirect()->route('campuses.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $campus = Campus::findOrFail($id);
        return view('campuses.edit', ['campus' => $campus]);
    }

    public function update(Request $request, string $id)
    {
        $campus = Campus::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:campuses,code,' . $id,
            'status' => 'required|in:active,archived',
        ]);

        $campus->update($validated);

        return redirect()->route('campuses.index');
    }

    public function destroy(string $id)
    {
        $campus = Campus::findOrFail($id);
        $campus->delete();
        return redirect()->route('campuses.index');
    }
}