<?php

namespace App\Http\Controllers;

use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventCategoryController extends Controller
{
    public function index()
    {
        $eventCategories = EventCategory::all();
        return view('event-categories.index', ['eventCategories' => $eventCategories]);
    }

    public function create()
    {
        return view('event-categories.create');
    }

    public function store(Request $request)
    {
        EventCategory::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);
        return redirect()->route('event-categories.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $eventCategory = EventCategory::findOrFail($id);
        return view('event-categories.edit', ['eventCategory' => $eventCategory]);
    }

    public function update(Request $request, string $id)
    {
        $eventCategory = EventCategory::findOrFail($id);
        $eventCategory->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);
        return redirect()->route('event-categories.index');
    }

    public function destroy(string $id)
    {
        $eventCategory = EventCategory::findOrFail($id);
        $eventCategory->delete();
        return redirect()->route('event-categories.index');
    }
}