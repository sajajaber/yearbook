<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->user()?->role?->role_name === 'editor') {
            $this->merge(['status' => 'draft']);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'academic_year_id' => 'required|exists:academic_years,id',
            'category_id' => 'required|exists:event_categories,id',
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:draft,reviewed,approved,published,archived,rejected',
            'featured' => 'nullable|boolean',
        ];
    }
}
