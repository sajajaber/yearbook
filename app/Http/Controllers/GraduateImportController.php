<?php

namespace App\Http\Controllers;

use App\Services\GraduateImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GraduateImportController extends Controller
{
    public function create()
    {
        return view('graduates.import');
    }

    public function preview(Request $request, GraduateImportService $service)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
        ]);

        $path = $request->file('file')->store('imports');
        try {
            $rows = $service->parse(Storage::path($path));
            $result = $service->validate($rows);
        } catch (\Throwable $e) {
            Storage::delete($path);
            return back()->withInput()->with('error', $e->getMessage());
        }

        session(['graduate_import_path' => $path]);
        return view('graduates.import', [
            'result' => $result,
            'headers' => GraduateImportService::HEADERS,
        ]);
    }

    public function store(Request $request, GraduateImportService $service)
    {
        $path = session('graduate_import_path');
        if (! $path || ! Storage::exists($path)) {
            return redirect()->route('graduates.import')->with('error', 'The import preview has expired. Please upload the CSV again.');
        }

        try {
            $rows = $service->parse(Storage::path($path));
            $result = $service->validate($rows);
            if ($result['errors'] || $result['duplicates'] || ! $result['valid']) {
                return redirect()->route('graduates.import')->with('error', 'The import file still contains validation errors or duplicates. Please correct the CSV and preview it again.');
            }

            $created = $service->import($result['valid'])['created'];
        } catch (\Throwable $e) {
            return redirect()->route('graduates.import')->with('error', 'The graduates could not be imported: ' . $e->getMessage());
        } finally {
            Storage::delete($path);
            session()->forget('graduate_import_path');
        }

        return redirect()->route('graduates.index')->with('success', "Successfully imported {$created} graduate " . ($created === 1 ? 'record.' : 'records.'));
    }
}
