<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class MediaTypeResolver
{
    protected array $typeMap = [
        'image' => [
            'extensions' => ['jpg', 'jpeg', 'png', 'webp'],
            'max' => 10240,     // KB (10MB)
        ],
        'video' => [
            'extensions' => ['mp4', 'mov', 'avi'],
            'max' => 102400,    // KB (100MB)
        ],
        'document' => [
            'extensions' => ['pdf', 'doc', 'docx', 'ppt', 'pptx'],
            'max' => 20480,     // KB (20MB)
        ],
    ];

    public function resolveType(UploadedFile $file): ?string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        foreach ($this->typeMap as $type => $rules) {
            if (in_array($extension, $rules['extensions'])) {
                return $type;
            }
        }

        return null;
    }

    public function rulesFor(string $type): array
    {
        $rules = $this->typeMap[$type] ?? null;

        if (! $rules) {
            return ['prohibited'];
        }

        return [
            'mimes:' . implode(',', $rules['extensions']),
            'max:' . $rules['max'],
        ];
    }

    public function limitsInBytes(): array
    {
        return [
            'image' => 10 * 1024 * 1024,
            'video' => 100 * 1024 * 1024,
            'document' => 20 * 1024 * 1024,
        ];
    }
}
