<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\MediaTypeResolver;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // route middleware already restricts by role
    }

    public function rules(): array
    {
        $resolver = new MediaTypeResolver();
        $file = $this->file('file');

        $fileRules = ['required', 'file'];

        if ($file) {
            $type = $resolver->resolveType($file);
            $fileRules = array_merge($fileRules, $resolver->rulesFor($type ?? '__unknown__'));
        }

        return [
            'file' => $fileRules,
            'caption' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'credit' => 'nullable|string|max:255',
        ];
    }
}
