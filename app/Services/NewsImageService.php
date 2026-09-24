<?php

namespace App\Services;

use App\Models\NewsImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class NewsImageService
{
    public function store(
        UploadedFile $file,
        int $newsId,
        int $sortOrder = 0
    ): NewsImage {
        $directory = "news/{$newsId}";

        $mimeType = $file->getMimeType();

        $image = match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg(
                $file->getRealPath()
            ),
            'image/png' => imagecreatefrompng(
                $file->getRealPath()
            ),
            'image/webp' => imagecreatefromwebp(
                $file->getRealPath()
            ),
            default => throw new RuntimeException(
                'The uploaded image format is not supported. Please upload a JPG, JPEG, PNG, or WEBP image.'
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
            'news_image_'
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

            Storage::disk('public')->put(
                $path,
                $contents
            );
        } finally {
            imagedestroy($image);

            if (file_exists($temporaryPath)) {
                unlink($temporaryPath);
            }
        }

        return NewsImage::create([
            'news_id' => $newsId,
            'file' => $path,
            'sort_order' => $sortOrder,
        ]);
    }

    public function delete(NewsImage $image): void
    {
        Storage::disk('public')->delete(
            $image->file
        );

        $image->delete();
    }
}