<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Major;
use App\Models\School;

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

        Major::create($validated);

        return redirect()->route('majors.index');
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

        return redirect()->route('majors.index');
    }

    public function destroy(string $id)
    {
        $major = Major::findOrFail($id);
        $major->delete();
        return redirect()->route('majors.index');
    }
}
