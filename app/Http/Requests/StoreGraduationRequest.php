<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Support\RichText;

class StoreGraduationRequest extends FormRequest
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
            'speakers' => 'nullable|array',
            'speakers.*' => 'nullable|string|max:255',
            'award_recipients' => 'nullable|array',
            'award_recipients.*.name' => 'required_with:award_recipients.*.award|nullable|string|max:255',
            'award_recipients.*.award' => 'required_with:award_recipients.*.name|nullable|string|max:255',
            'status' => 'nullable|in:active,archived',
            'campus_ids' => 'nullable|array',
            'campus_ids.*' => 'integer|exists:campuses,id',
            'school_ids' => 'nullable|array',
            'school_ids.*' => 'integer|exists:schools,id',
            'media_ids' => 'nullable|array',
            'media_ids.*' => 'integer|distinct|exists:media,id',
        ];
    }
}
