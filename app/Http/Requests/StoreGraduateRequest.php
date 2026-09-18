<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGraduateRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->user()?->role?->role_name === 'editor') {
            $this->merge(['consent_status' => 'pending', 'publish_status' => 'draft']);
        }

        $lists = collect(['achievements', 'activities', 'projects', 'internships'])
            ->mapWithKeys(function ($field) {
                $value = $this->input($field);
                if (is_string($value)) {
                    $value = preg_split('/\r\n|\r|\n/', $value);
                }
                return [$field => collect($value ?? [])->map(fn ($item) => trim((string) $item))->filter()->values()->all()];
            })->all();

        $links = collect($this->input('approved_links', []))
            ->map(fn ($link) => trim((string) $link))
            ->filter()
            ->values()
            ->all();

        $this->merge([...$lists, 'approved_links' => $links]);
    }

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'student_reference' => 'required|string|max:255|unique:graduates,student_reference',
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'major_id' => 'required|exists:majors,id',
            'campus_id' => 'required|exists:campuses,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'graduation_id' => [
                'nullable',
                Rule::exists('graduations', 'id')->where(fn ($query) => $query->where('academic_year_id', $this->input('academic_year_id'))),
            ],
            'profile_text' => 'nullable|string',
            'achievements' => 'nullable|array',
            'achievements.*' => 'string|max:1000',
            'activities' => 'nullable|array',
            'activities.*' => 'string|max:1000',
            'projects' => 'nullable|array',
            'projects.*' => 'string|max:1000',
            'internships' => 'nullable|array',
            'internships.*' => 'string|max:1000',
            'future_plans' => 'nullable|string',
            'professional_interests' => 'nullable|string|max:2000',
            'certifications_training' => 'nullable|string|max:5000',
            'approved_links' => 'nullable|array|max:10',
            'approved_links.*' => 'nullable|url|max:500',
            'quote' => 'nullable|string|max:255',
            'portrait' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'resume' => 'nullable|file|mimes:pdf|max:10240',
            'consent_status' => ['required', 'in:pending,granted,declined'],
            'publish_status' => ['required', 'in:draft,reviewed,approved,published,archived,rejected'],
            'degree_level' => 'required|in:undergraduate,graduate',
            'gpa' => 'nullable|numeric|min:0|max:4',
        ];
    }
}
