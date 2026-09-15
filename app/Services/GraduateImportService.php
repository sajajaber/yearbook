<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Graduation;
use App\Models\Graduate;
use App\Models\Major;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class GraduateImportService
{
    public const HEADERS = [
        'student_reference', 'name', 'degree_level', 'school', 'major', 'campus',
        'academic_year', 'graduation', 'consent_status', 'gpa', 'profile_text',
        'achievements', 'activities', 'projects', 'internships', 'certifications_training',
        'professional_interests', 'future_plans', 'quote',
    ];

    public function parse(string $path): array
    {
        $handle = fopen($path, 'r');
        if (! $handle) throw new \RuntimeException('The uploaded CSV could not be read.');
        $headers = fgetcsv($handle);
        if (! is_array($headers)) { fclose($handle); throw new \RuntimeException('The CSV file is empty.'); }
        $headers = array_map(fn ($value) => $this->normalizeHeader($value), $headers);
        $missing = array_values(array_diff(['name', 'degree_level', 'school', 'major', 'campus', 'academic_year'], $headers));
        if ($missing) { fclose($handle); throw new \RuntimeException('Missing required CSV columns: ' . implode(', ', $missing) . '.'); }
        $rows = [];
        $line = 1;
        while (($values = fgetcsv($handle)) !== false) {
            $line++;
            if (count(array_filter($values, fn ($value) => trim((string) $value) !== '')) === 0) continue;
            $values = array_pad($values, count($headers), null);
            $row = [];
            foreach ($headers as $index => $header) $row[$header] = trim((string) ($values[$index] ?? ''));
            $row['_line'] = $line;
            $rows[] = $row;
        }
        fclose($handle);
        return $rows;
    }

    public function validate(array $rows): array
    {
        $schools = School::all(); $majors = Major::all(); $campuses = Campus::all(); $years = AcademicYear::all(); $graduations = Graduation::with('academicYear')->get();
        $seenReferences = []; $seenNaturalKeys = []; $duplicateLines = []; $valid = []; $errors = []; $duplicates = [];

        foreach ($rows as $row) {
            $rowErrors = [];
            $studentReference = $this->nullable($row['student_reference'] ?? null);
            $name = trim((string) ($row['name'] ?? ''));
            $degree = strtolower(trim((string) ($row['degree_level'] ?? '')));
            $school = $this->findByName($schools, $row['school'] ?? '');
            $major = $this->findByName($majors, $row['major'] ?? '');
            $campus = $this->findByName($campuses, $row['campus'] ?? '');
            $year = $this->findYear($years, $row['academic_year'] ?? '');
            $graduation = $this->findGraduation($graduations, $row['graduation'] ?? '', $year?->id);

            if ($name === '') $rowErrors[] = 'Name is required.';
            if (! in_array($degree, ['undergraduate', 'graduate'], true)) $rowErrors[] = 'Degree level must be undergraduate or graduate.';
            if (! $school) $rowErrors[] = 'School was not found.';
            if (! $major) $rowErrors[] = 'Major was not found.';
            if (! $campus) $rowErrors[] = 'Campus was not found.';
            if (! $year) $rowErrors[] = 'Academic year was not found.';
            if (($row['graduation'] ?? '') !== '' && ! $graduation) $rowErrors[] = 'Graduation ceremony was not found for the selected academic year.';
            $consent = strtolower(trim((string) ($row['consent_status'] ?? 'pending')));
            if (! in_array($consent, ['pending', 'granted', 'declined'], true)) $rowErrors[] = 'Consent status must be pending, granted, or declined.';
            $gpa = $this->nullable($row['gpa'] ?? null);
            if ($gpa !== null && (! is_numeric($gpa) || (float) $gpa < 0 || (float) $gpa > 4)) $rowErrors[] = 'GPA must be between 0 and 4.';

            if ($studentReference !== null) {
                $key = mb_strtolower($studentReference);
                if (isset($seenReferences[$key])) { $duplicates[] = ['row' => $row['_line'], 'message' => 'Duplicate student reference in this file.']; $duplicateLines[$row['_line']] = true; }
                elseif (Graduate::where('student_reference', $studentReference)->exists()) { $duplicates[] = ['row' => $row['_line'], 'message' => 'Student reference already exists in the database.']; $duplicateLines[$row['_line']] = true; }
                $seenReferences[$key] = true;
            } elseif ($school && $major && $year) {
                $naturalKey = implode('|', [$this->key($name), $school->id, $major->id, $year->id]);
                if (isset($seenNaturalKeys[$naturalKey])) { $duplicates[] = ['row' => $row['_line'], 'message' => 'Duplicate graduate in this file (same name, school, major, and academic year).']; $duplicateLines[$row['_line']] = true; }
                elseif (Graduate::where('school_id', $school->id)->where('major_id', $major->id)->where('academic_year_id', $year->id)->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->exists()) { $duplicates[] = ['row' => $row['_line'], 'message' => 'A graduate with the same name, school, major, and academic year already exists.']; $duplicateLines[$row['_line']] = true; }
                $seenNaturalKeys[$naturalKey] = true;
            }

            if ($rowErrors) { $errors[] = ['row' => $row['_line'], 'messages' => $rowErrors]; continue; }
            if (isset($duplicateLines[$row['_line']])) continue;
            $row['_resolved'] = compact('school', 'major', 'campus', 'year', 'graduation', 'consent', 'gpa');
            $valid[] = $row;
        }

        return compact('valid', 'errors', 'duplicates');
    }

    public function import(array $rows): array
    {
        $created = 0;
        DB::transaction(function () use ($rows, &$created) {
            foreach ($rows as $row) {
                $resolved = $row['_resolved'];
                Graduate::create([
                    'student_reference' => $this->nullable($row['student_reference'] ?? null), 'name' => $row['name'], 'degree_level' => strtolower($row['degree_level']),
                    'school_id' => $resolved['school']->id, 'major_id' => $resolved['major']->id, 'campus_id' => $resolved['campus']->id, 'academic_year_id' => $resolved['year']->id,
                    'graduation_id' => $resolved['graduation']?->id, 'consent_status' => $resolved['consent'], 'publish_status' => 'draft', 'gpa' => $resolved['gpa'],
                    'profile_text' => $this->nullable($row['profile_text'] ?? null), 'achievements' => $this->list($row['achievements'] ?? ''), 'activities' => $this->list($row['activities'] ?? ''),
                    'projects' => $this->list($row['projects'] ?? ''), 'internships' => $this->list($row['internships'] ?? ''), 'certifications_training' => $this->nullable($row['certifications_training'] ?? null),
                    'professional_interests' => $this->nullable($row['professional_interests'] ?? null), 'future_plans' => $this->nullable($row['future_plans'] ?? null), 'quote' => $this->nullable($row['quote'] ?? null),
                ]);
                $created++;
            }
        });
        return ['created' => $created];
    }

    private function findByName($collection, string $value) { $needle = $this->key($value); return $collection->first(fn ($item) => $this->key($item->name) === $needle); }
    private function findYear($years, string $value) { $needle = $this->key($value); return $years->first(fn ($year) => $this->key($year->title) === $needle || (string) $year->id === trim($value)); }
    private function findGraduation($graduations, string $value, ?int $yearId) { if (trim($value) === '') return null; return $graduations->first(fn ($graduation) => (! $yearId || $graduation->academic_year_id === $yearId) && ($this->key($graduation->venue) === $this->key($value) || (string) $graduation->id === trim($value))); }
    private function list(string $value): array { return collect(preg_split('/\s*[|\n;]\s*/', $value))->map(fn ($item) => trim($item))->filter()->values()->all(); }
    private function nullable(?string $value): ?string { $value = trim((string) $value); return $value === '' ? null : $value; }
    private function key(string $value): string { return mb_strtolower(trim(preg_replace('/\s+/', ' ', $value))); }
    private function normalizeHeader($value): string { return strtolower(trim(str_replace([' ', '-'], '_', (string) $value))); }
}
