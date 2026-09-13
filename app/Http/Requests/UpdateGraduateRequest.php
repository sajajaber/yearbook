<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Graduate;
use App\Rules\ConsentGrantedForPublish;
use Illuminate\Validation\Rule;

class UpdateGraduateRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->user()?->role?->role_name === 'editor') {
            $graduate = Graduate::find($this->route('graduate'));
            $this->merge(['consent_status' => $graduate?->consent_status, 'publish_status' => $graduate?->publish_status]);
        }
    }

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'student_reference' => ['nullable', 'string', 'max:255', 'unique:graduates,student_reference,' . $this->route('graduate')],
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
            'future_plans' => 'nullable|string',
            'quote' => 'nullable|string|max:255',
            'portrait' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'consent_status' => ['required', 'in:pending,granted,declined'],
            'publish_status' => ['required', 'in:draft,reviewed,approved,published,archived,rejected', new ConsentGrantedForPublish($this->input('consent_status'))],
            'degree_level' => 'required|in:undergraduate,graduate',
        ];
    }
}
