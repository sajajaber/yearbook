<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Server-side image processing for the yearbook media library.
 *
 * Images are resized rather than upscaled, thumbnails are generated for
 * previews, and graduate portraits get a consistent 4:5 crop so they fit
 * the yearbook's portrait cards cleanly.
 */
class ImageProcessor
{
    protected int $maxWidth;

    public function __construct(int $maxWidth = 480)
    {
        $this->maxWidth = $maxWidth;
    }

    /**
     * Create a proportional thumbnail and return its disk-relative path.
     */
    public function createThumbnail(string $diskPath, string $disk = 'public'): ?string
    {
        if (! extension_loaded('gd')) {
            Log::warning('ImageProcessor: GD extension not available, skipping thumbnail.', [
                'path' => $diskPath,
            ]);

            return null;
        }

        try {
            $storage = Storage::disk($disk);

            if (! $storage->exists($diskPath)) {
                return null;
            }

            $absolutePath = $storage->path($diskPath);
            $imageInfo = @getimagesize($absolutePath);

            if ($imageInfo === false) {
                return null;
            }

            [$originalWidth, $originalHeight, $imageType] = $imageInfo;
            $source = $this->createSource($absolutePath, $imageType);

            if ($source === false) {
                return null;
            }

            if ($originalWidth <= $this->maxWidth) {
                imagedestroy($source);
                return null;
            }

            $thumbWidth = $this->maxWidth;
            $thumbHeight = (int) round($originalHeight * ($thumbWidth / $originalWidth));
            $thumbnail = imagecreatetruecolor($thumbWidth, $thumbHeight);

            $this->prepareTransparency($thumbnail, $imageType);

            imagecopyresampled(
                $thumbnail,
                $source,
                0,
                0,
                0,
                0,
                $thumbWidth,
                $thumbHeight,
                $originalWidth,
                $originalHeight
            );

            $thumbPath = $this->thumbnailPathFor($diskPath);
            $thumbAbsolutePath = $storage->path($thumbPath);

            $this->ensureDirectory($thumbAbsolutePath);
            $saved = $this->saveImage($thumbnail, $thumbAbsolutePath, $imageType, 82);

            imagedestroy($source);
            imagedestroy($thumbnail);

            return $saved ? $thumbPath : null;
        } catch (\Throwable $e) {
            Log::warning('ImageProcessor: failed to create thumbnail.', [
                'path' => $diskPath,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Create an optimized graduate portrait with a consistent 4:5 ratio.
     *
     * The original upload is center-cropped to the requested portrait ratio,
     * resized to at most 800x1000, and stored as a compressed JPEG. This keeps
     * profile images lightweight while ensuring they fill the library cards
     * without distortion or awkward empty space.
     */
    public function createPortrait(string $diskPath, string $disk = 'public'): ?string
    {
        if (! extension_loaded('gd')) {
            Log::warning('ImageProcessor: GD extension not available, skipping portrait optimization.', [
                'path' => $diskPath,
            ]);

            return null;
        }

        try {
            $storage = Storage::disk($disk);

            if (! $storage->exists($diskPath)) {
                return null;
            }

            $absolutePath = $storage->path($diskPath);
            $imageInfo = @getimagesize($absolutePath);

            if ($imageInfo === false) {
                return null;
            }

            [$originalWidth, $originalHeight, $imageType] = $imageInfo;
            $source = $this->createSource($absolutePath, $imageType);

            if ($source === false || $originalWidth <= 0 || $originalHeight <= 0) {
                return null;
            }

            // Target ratio: 4:5 (portrait).
            $targetRatio = 4 / 5;
            $sourceRatio = $originalWidth / $originalHeight;

            if ($sourceRatio > $targetRatio) {
                // Source is too wide: crop equally from the left/right.
                $cropHeight = $originalHeight;
                $cropWidth = (int) round($originalHeight * $targetRatio);
                $cropX = (int) floor(($originalWidth - $cropWidth) / 2);
                $cropY = 0;
            } else {
                // Source is too tall: crop equally from the top/bottom.
                $cropWidth = $originalWidth;
                $cropHeight = (int) round($originalWidth / $targetRatio);
                $cropX = 0;
                $cropY = (int) floor(($originalHeight - $cropHeight) / 2);
            }

            $maxWidth = 800;
            $maxHeight = 1000;
            $scale = min($maxWidth / $cropWidth, $maxHeight / $cropHeight, 1);
            $targetWidth = max(1, (int) round($cropWidth * $scale));
            $targetHeight = max(1, (int) round($cropHeight * $scale));

            $portrait = imagecreatetruecolor($targetWidth, $targetHeight);
            imagecopyresampled(
                $portrait,
                $source,
                0,
                0,
                $cropX,
                $cropY,
                $targetWidth,
                $targetHeight,
                $cropWidth,
                $cropHeight
            );

            $directory = dirname($diskPath);
            $filename = pathinfo(basename($diskPath), PATHINFO_FILENAME);
            $optimizedPath = ($directory === '.' ? '' : $directory . '/') . 'optimized/' . $filename . '.jpg';
            $optimizedAbsolutePath = $storage->path($optimizedPath);

            $this->ensureDirectory($optimizedAbsolutePath);
            $saved = imagejpeg($portrait, $optimizedAbsolutePath, 84);

            imagedestroy($source);
            imagedestroy($portrait);

            return $saved ? $optimizedPath : null;
        } catch (\Throwable $e) {
            Log::warning('ImageProcessor: failed to optimize portrait.', [
                'path' => $diskPath,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function deleteThumbnail(?string $thumbnailPath, string $disk = 'public'): void
    {
        if ($thumbnailPath) {
            Storage::disk($disk)->delete($thumbnailPath);
        }
    }

    protected function createSource(string $absolutePath, int $imageType)
    {
        return match ($imageType) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($absolutePath),
            IMAGETYPE_PNG => imagecreatefrompng($absolutePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp')
                ? imagecreatefromwebp($absolutePath)
                : false,
            default => false,
        };
    }

    protected function prepareTransparency($image, int $imageType): void
    {
        if (in_array($imageType, [IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            imagealphablending($image, false);
            imagesavealpha($image, true);
            $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
            imagefilledrectangle($image, 0, 0, imagesx($image), imagesy($image), $transparent);
        }
    }

    protected function saveImage($image, string $absolutePath, int $imageType, int $quality): bool
    {
        return match ($imageType) {
            IMAGETYPE_JPEG => imagejpeg($image, $absolutePath, $quality),
            IMAGETYPE_PNG => imagepng($image, $absolutePath, 6),
            IMAGETYPE_WEBP => function_exists('imagewebp')
                ? imagewebp($image, $absolutePath, $quality)
                : false,
            default => false,
        };
    }

    protected function ensureDirectory(string $absolutePath): void
    {
        if (! is_dir(dirname($absolutePath))) {
            mkdir(dirname($absolutePath), 0755, true);
        }
    }

    protected function thumbnailPathFor(string $diskPath): string
    {
        $directory = dirname($diskPath);
        $filename = basename($diskPath);

        return ($directory === '.' ? '' : $directory . '/') . 'thumbs/' . $filename;
    }
}
