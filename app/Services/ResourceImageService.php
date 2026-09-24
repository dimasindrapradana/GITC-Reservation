<?php

namespace App\Services;

use App\Models\ResourceImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ResourceImageService
{
    public function store(
        UploadedFile $file,
        string $resourceType,
        int $resourceId,
        int $sortOrder = 0
    ): ResourceImage {
        $directory = "resources/{$resourceType}/{$resourceId}";

        $extension = strtolower($file->getClientOriginalExtension());

        $image = match ($extension) {
            'jpg', 'jpeg' => imagecreatefromjpeg($file->getRealPath()),
            'png' => imagecreatefrompng($file->getRealPath()),
            'webp' => imagecreatefromwebp($file->getRealPath()),
            default => throw new RuntimeException(
                'The uploaded image format is not supported.'
            ),
        };

        if (!$image) {
            throw new RuntimeException(
                'The uploaded image could not be processed.'
            );
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        $filename = Str::uuid()->toString() . '.webp';
        $path = "{$directory}/{$filename}";

        $temporaryPath = tempnam(
            sys_get_temp_dir(),
            'resource_image_'
        );

        if ($temporaryPath === false) {
            imagedestroy($image);

            throw new RuntimeException(
                'Unable to create a temporary image file.'
            );
        }

        try {
            if (!imagewebp($image, $temporaryPath, 85)) {
                throw new RuntimeException(
                    'The image could not be converted to WebP.'
                );
            }

            $contents = file_get_contents($temporaryPath);

            if ($contents === false) {
                throw new RuntimeException(
                    'The converted image could not be read.'
                );
            }

            Storage::disk('public')->put($path, $contents);
        } finally {
            imagedestroy($image);

            if (file_exists($temporaryPath)) {
                unlink($temporaryPath);
            }
        }

        return ResourceImage::create([
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'file' => $path,
            'sort_order' => $sortOrder,
        ]);
    }

    public function delete(ResourceImage $image): void
    {
        Storage::disk('public')->delete($image->file);

        $image->delete();
    }
}