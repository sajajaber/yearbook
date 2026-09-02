<?php

namespace App\Http\Controllers;

use App\Models\EventCategory;
use Illuminate\Http\Request;
use App\Models\AuditLog;

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $eventCategory = EventCategory::create($validated);
        AuditLog::record('created', $eventCategory);

        return redirect()->back()
            ->with('success', 'Category added.')
            ->with('active_tab', $request->input('tab', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $eventCategory = EventCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $eventCategory->update($validated);
        AuditLog::record('updated', $eventCategory);

        return redirect()->back()
            ->with('success', 'Category updated.')
            ->with('active_tab', $request->input('tab', 'categories'));
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

    public function destroy(string $id)
    {
        $eventCategory = EventCategory::findOrFail($id);
        $eventCategory->delete();
        AuditLog::record('deleted', $eventCategory);
        return redirect()->back()
            ->with('success', 'Category deleted.')
            ->with('active_tab', request()->input('tab', 'categories'));
    }
}
