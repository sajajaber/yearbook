<?php

use App\Services\MediaTypeResolver;
use Illuminate\Http\UploadedFile;

test('resolves image extensions to the image type', function () {
    $resolver = new MediaTypeResolver();
    $file = UploadedFile::fake()->image('photo.jpg');

    expect($resolver->resolveType($file))->toBe('image');
});

test('resolves document extensions to the document type', function () {
    $resolver = new MediaTypeResolver();
    $file = UploadedFile::fake()->create('handout.pdf', 100);

    expect($resolver->resolveType($file))->toBe('document');
});

test('returns null for an unrecognized extension', function () {
    $resolver = new MediaTypeResolver();
    $file = UploadedFile::fake()->create('archive.zip', 100);

    expect($resolver->resolveType($file))->toBeNull();
});

test('rulesFor an unknown type prohibits the upload', function () {
    $resolver = new MediaTypeResolver();

    expect($resolver->rulesFor('__unknown__'))->toBe(['prohibited']);
});

test('rulesFor image includes the correct mimes and size limit', function () {
    $resolver = new MediaTypeResolver();
    $rules = $resolver->rulesFor('image');

    expect($rules)->toContain('mimes:jpg,jpeg,png,webp')
        ->and($rules)->toContain('max:5120');
});
