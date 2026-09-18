<?php

namespace App\Http\Controllers;

use App\Models\EventCategory;
use App\Models\Event;
use Illuminate\Database\QueryException;
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

        if (Event::where('category_id', $eventCategory->id)->exists()) {
            return redirect()->back()
                ->with('error', 'This category cannot be deleted because it is used by one or more events.')
                ->with('active_tab', request()->input('tab', 'categories'));
        }

        try {
            $eventCategory->delete();
        } catch (QueryException $e) {
            return redirect()->back()
                ->with('error', 'This category cannot be deleted because it is still being used by other records.')
                ->with('active_tab', request()->input('tab', 'categories'));
        }

        AuditLog::record('deleted', $eventCategory);
        return redirect()->back()
            ->with('success', 'Category deleted.')
            ->with('active_tab', request()->input('tab', 'categories'));
    }
}
