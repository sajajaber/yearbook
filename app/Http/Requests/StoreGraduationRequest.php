<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGraduationRequest extends FormRequest
{
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
