<?php

namespace App\Support;

class RichText
{
    /**
     * Keep only the formatting vocabulary used by the yearbook editor.
     * Attributes are removed so pasted HTML cannot carry styles or handlers.
     */
    public static function sanitize(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $allowed = ['b', 'strong', 'i', 'em', 'u', 'p', 'br', 'ul', 'ol', 'li'];
        $value = strip_tags($value, '<' . implode('><', $allowed) . '>');

        $pattern = '/<(' . implode('|', $allowed) . ')(?:\s[^>]*)?>/i';
        $value = preg_replace_callback($pattern, static function ($match) {
            return '<' . strtolower($match[1]) . '>';
        }, $value) ?? $value;

        $value = preg_replace_callback('/<\/(' . implode('|', $allowed) . ')\s*>/i', static function ($match) {
            return '</' . strtolower($match[1]) . '>';
        }, $value) ?? $value;

        return trim($value);
    }
}