<?php

namespace App\Support;

class ImageOptimizer
{
    public static function toWebp(string $sourcePath, string $destinationPath, int $maxDimension = 1200, int $quality = 82): bool
    {
        if (! function_exists('imagewebp')) {
            return false;
        }

        $info = @getimagesize($sourcePath);
        if (! $info) {
            return false;
        }

        [$width, $height] = $info;
        $image = self::open($sourcePath, $info['mime'] ?? '');

        if (! $image || $width < 1 || $height < 1) {
            return false;
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        $scale = min(1, $maxDimension / max($width, $height));
        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));

        if ($newWidth !== $width || $newHeight !== $height) {
            $canvas = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $transparent);
            imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $canvas;
        }

        $result = imagewebp($image, $destinationPath, $quality);
        imagedestroy($image);

        return $result;
    }

    private static function open(string $path, string $mime)
    {
        return match ($mime) {
            'image/jpeg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($path) : false,
            'image/png' => function_exists('imagecreatefrompng') ? @imagecreatefrompng($path) : false,
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };
    }
}
