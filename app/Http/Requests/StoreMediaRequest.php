<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\MediaTypeResolver;

class StoreMediaRequest extends FormRequest
{
    protected ?string $resolvedType = null;

    public function authorize(): bool
    {
        return true; // route middleware already restricts by role
    }

    protected function prepareForValidation(): void
    {
        $tags = $this->input('tags', []);

        if (is_string($tags)) {
            $tags = preg_split('/[,\n]+/', $tags) ?: [];
        }

        $tags = collect($tags)
            ->map(fn($tag) => trim((string) $tag))
            ->filter()
            ->unique(fn($tag) => mb_strtolower($tag))
            ->values()
            ->all();

        $this->merge(['tags' => $tags]);
    }

    public function rules(): array
    {
        $resolver = new MediaTypeResolver();
        $file = $this->file('file');

        $fileRules = ['required', 'file'];

        if ($file) {
            $this->resolvedType = $resolver->resolveType($file);
            $fileRules = array_merge($fileRules, $resolver->rulesFor($this->resolvedType ?? '__unknown__'));
        }

        return [
            'file' => $fileRules,
            'caption' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'credit' => 'nullable|string|max:255',
            'tags' => 'nullable|array|max:30',
            'tags.*' => 'nullable|string|max:50',
        ];
    }

    public function resolvedType(): ?string
    {
        return $this->resolvedType;
    }
}
