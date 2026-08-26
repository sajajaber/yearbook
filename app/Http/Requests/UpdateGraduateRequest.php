<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ConsentGrantedForPublish; 

class UpdateGraduateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_reference' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'major_id' => 'required|exists:majors,id',
            'campus_id' => 'required|exists:campuses,id',
            'graduation_id' => 'required|exists:graduations,id',
            'profile_text' => 'nullable|string',
            'future_plans' => 'nullable|string',
            'quote' => 'nullable|string|max:255',
        'consent_status' => [
            'required',
            'in:pending,granted,declined',
        ],
        'publish_status' => [
            'required',
            'in:draft,reviewed,approved,published,archived',
            new ConsentGrantedForPublish($this->input('consent_status')),
        ],
    ];
}
}