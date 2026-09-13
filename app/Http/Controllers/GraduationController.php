<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\School;
use App\Models\Graduation;
use App\Models\Media;
use App\Models\AuditLog;
use App\Http\Controllers\Concerns\SyncsOrderedMedia;
use App\Http\Requests\UpdateGraduationRequest;
use App\Http\Requests\StoreGraduationRequest;

class GraduationController extends Controller
{
    use SyncsOrderedMedia;

    public function index()
    {
        $graduations = Graduation::with(['academicYear', 'campuses', 'schools', 'media'])
            ->orderByDesc('ceremony_date')
            ->get();
        return view('graduations.index', ['graduations' => $graduations]);
    }

    public function create()
    {
        $academicYears = AcademicYear::where('status', '!=', 'archived')->get();
        $campuses = Campus::where('status', 'active')->get();
        $schools = School::where('status', 'active')->get();
        $mediaItems = Media::orderByDesc('created_at')->orderBy('file_name')->get();

        return view('graduations.create', [
            'academicYears' => $academicYears,
            'campuses' => $campuses,
            'schools' => $schools,
            'mediaItems' => $mediaItems,
        ]);
    }

    public function store(StoreGraduationRequest $request)
    {
        $graduation = Graduation::create($request->validated());

        $graduation->campuses()->sync($request->input('campus_ids', []));
        $graduation->schools()->sync($request->input('school_ids', []));
        $this->syncMediaWithOrder($graduation, $request->input('media_ids', []));

        AuditLog::record('created', $graduation);

        return redirect()->route('graduations.index');
    }

    public function edit(string $id)
    {
        $graduation = Graduation::with('media')->findOrFail($id);
        $academicYears = AcademicYear::all();
        $campuses = Campus::all();
        $schools = School::all();
        $mediaItems = Media::orderByDesc('created_at')->orderBy('file_name')->get();
        $selectedMediaIds = $graduation->media->pluck('id')->all();

        return view('graduations.edit', [
            'graduation' => $graduation,
            'academicYears' => $academicYears,
            'campuses' => $campuses,
            'schools' => $schools,
            'mediaItems' => $mediaItems,
            'selectedMediaIds' => $selectedMediaIds,
        ]);
    }

    public function update(UpdateGraduationRequest $request, string $id)
    {
        $graduation = Graduation::findOrFail($id);

        $graduation->update($request->validated());

        $graduation->campuses()->sync($request->input('campus_ids', []));
        $graduation->schools()->sync($request->input('school_ids', []));
        $this->syncMediaWithOrder($graduation, $request->input('media_ids', []));

        AuditLog::record('updated', $graduation);

        return redirect()->route('graduations.index');
    }

    public function destroy(string $id)
    {
        $graduation = Graduation::findOrFail($id);
        $graduation->archive();
        AuditLog::record('archived', $graduation);

        return redirect()->route('graduations.index');
    }

    public function unarchive(string $id)
    {
        $graduation = Graduation::findOrFail($id);
        $graduation->unarchive();
        AuditLog::record('unarchived', $graduation);

        return redirect()->route('graduations.index');
    }
}
