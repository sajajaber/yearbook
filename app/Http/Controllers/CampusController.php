<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campus;

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
        Campus::create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
        ]);
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
        $campus->update([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
        ]);
        return redirect()->route('campuses.index');
    }

    public function destroy(string $id)
    {
        $campus = Campus::findOrFail($id);
        $campus->delete();
        return redirect()->route('campuses.index');
    }
}