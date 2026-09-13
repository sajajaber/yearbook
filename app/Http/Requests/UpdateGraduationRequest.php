<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Support\RichText;

class UpdateGraduationRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'description' => RichText::sanitize($this->input('description')),
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_year_id' => 'required|exists:academic_years,id',
            'ceremony_date' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,archived',
            'school_ids' => 'nullable|array',
            'school_ids.*' => 'integer|exists:schools,id',
        ];
    }
}
