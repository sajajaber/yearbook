<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Generates a resized thumbnail for an uploaded image using PHP's GD
 * extension. Deliberately dependency-free (no Intervention/Image or
 * similar composer package) so it works with the stack already in
 * composer.lock.
 *
 * Every public method here is non-fatal by design: if GD is missing,
 * the source format is unsupported, or anything goes wrong, methods
 * return null / no-op rather than throwing, so a thumbnail failure
 * never blocks the underlying media upload.
 */
class ImageProcessor
{
    /**
     * Maximum width (px) of the generated thumbnail. Height is scaled
     * proportionally. Images already narrower than this are left alone
     * (no upscaling, and no pointless "thumbnail" that's the same size
     * as the original).
     */
    protected int $maxWidth;

    public function __construct(int $maxWidth = 480)
    {
        $this->maxWidth = $maxWidth;
    }

    /**
     * Create a thumbnail for the image stored at $diskPath on $disk and
     * return the thumbnail's own disk-relative path, or null if no
     * thumbnail was created (unsupported type, already small, or error).
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

            $source = match ($imageType) {
                IMAGETYPE_JPEG => imagecreatefromjpeg($absolutePath),
                IMAGETYPE_PNG => imagecreatefrompng($absolutePath),
                IMAGETYPE_WEBP => function_exists('imagecreatefromwebp')
                    ? imagecreatefromwebp($absolutePath)
                    : false,
                default => false,
            };

            if ($source === false) {
                return null;
            }

            // Don't upscale small images; only shrink larger ones.
            if ($originalWidth <= $this->maxWidth) {
                imagedestroy($source);

                return null;
            }

            $thumbWidth = $this->maxWidth;
            $thumbHeight = (int) round($originalHeight * ($thumbWidth / $originalWidth));

            $thumbnail = imagecreatetruecolor($thumbWidth, $thumbHeight);

            // Preserve transparency for PNG/WEBP sources.
            if (in_array($imageType, [IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
                $transparent = imagecolorallocatealpha($thumbnail, 0, 0, 0, 127);
                imagefilledrectangle($thumbnail, 0, 0, $thumbWidth, $thumbHeight, $transparent);
            }

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

            if (! is_dir(dirname($thumbAbsolutePath))) {
                mkdir(dirname($thumbAbsolutePath), 0755, true);
            }

            $saved = match ($imageType) {
                IMAGETYPE_JPEG => imagejpeg($thumbnail, $thumbAbsolutePath, 82),
                IMAGETYPE_PNG => imagepng($thumbnail, $thumbAbsolutePath, 6),
                IMAGETYPE_WEBP => function_exists('imagewebp')
                    ? imagewebp($thumbnail, $thumbAbsolutePath, 82)
                    : false,
                default => false,
            };

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
     * Delete a previously generated thumbnail, if any. Safe to call with
     * null (e.g. when the media item never got a thumbnail).
     */
    public function deleteThumbnail(?string $thumbnailPath, string $disk = 'public'): void
    {
        if ($thumbnailPath) {
            Storage::disk($disk)->delete($thumbnailPath);
        }
    }

    /**
     * Build the thumbnail's disk-relative path from the original's path,
     * e.g. "media/images/photo.jpg" -> "media/images/thumbs/photo.jpg".
     */
    protected function thumbnailPathFor(string $diskPath): string
    {
        $directory = dirname($diskPath);
        $filename = basename($diskPath);

        return ($directory === '.' ? '' : $directory . '/') . 'thumbs/' . $filename;
    }
}
