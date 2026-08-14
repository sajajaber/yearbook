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
        $schools = School::all();

        return view('majors.create', ['schools' => $schools]);
    }

    public function store(Request $request)
    {
        Major::create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'school_id' => $request->input('school_id'),
        ]);
        return redirect()->route('majors.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $major = Major::findOrFail($id);
        $schools = School::all();

        return view('majors.edit', ['major' => $major, 'schools' => $schools]);
    }

    public function update(Request $request, string $id)
    {
        $major = Major::findOrFail($id);
        $major->update([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'school_id' => $request->input('school_id'),
        ]);
        return redirect()->route('majors.index');
    }

    public function destroy(string $id)
    {
        $major = Major::findOrFail($id);
        $major->delete();
        return redirect()->route('majors.index');
    }
}

